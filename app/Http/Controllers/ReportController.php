<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected FirestoreRepository $db;

    public function __construct(FirestoreRepository $repo)
    {
        $this->db = $repo;
    }

    public function index(Request $request)
    {
        if (session()->has('admin')) {
            $admin = session()->get('admin');
            if ($admin->userType != 1) {
                session()->put('errorLoginUnauthorized', true);
                return redirect('/login');
            }
        } else {
            session()->put('errorLoginUnauthorized', true);
            return redirect('/login');
        }

        // ── Date filter resolution ────────────────────────────────────────
        $preset = $request->input('preset', 'last_6_months');
        $now    = Carbon::now();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            // Explicit custom dates always take priority
            $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
            $endDate   = Carbon::parse($request->input('end_date'))->endOfDay();
        } else {
            // Derive from preset
            switch ($preset) {
                case 'this_week':
                    $startDate = $now->copy()->startOfWeek();
                    $endDate   = $now->copy()->endOfDay();
                    break;
                case 'this_month':
                    $startDate = $now->copy()->startOfMonth();
                    $endDate   = $now->copy()->endOfDay();
                    break;
                case 'last_3_months':
                    $startDate = $now->copy()->subMonths(3)->startOfDay();
                    $endDate   = $now->copy()->endOfDay();
                    break;
                case 'this_year':
                    $startDate = $now->copy()->startOfYear();
                    $endDate   = $now->copy()->endOfDay();
                    break;
                case 'all':
                    $startDate = Carbon::create(2020, 1, 1)->startOfDay();
                    $endDate   = $now->copy()->endOfDay();
                    break;
                case 'last_6_months':
                default:
                    $preset    = 'last_6_months';
                    $startDate = $now->copy()->subMonths(6)->startOfDay();
                    $endDate   = $now->copy()->endOfDay();
                    break;
            }
        }

        // Ensure start <= end
        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        // ── Raw data ──────────────────────────────────────────────────────
        $patients   = $this->db->fetch('patients')->toArray();
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $activities = $this->db->fetch('activity')->toArray();
        $devices    = $this->db->fetch('devices')->toArray();
        $alerts     = $this->db->fetch('activity_history')->toArray();

        // ── Caregiver name map ────────────────────────────────────────────
        $caregiverMap = [];
        foreach ($caregivers as $c) {
            $caregiverMap[$c['docID']] = trim(($c['firstName'] ?? '') . ' ' . ($c['lastName'] ?? ''));
        }

        // ── Total (unfiltered) counts for static cards ────────────────────
        $totalPatients   = count($patients);
        $totalCaregivers = count($caregivers);
        $totalDevices    = count($devices);

        // ── Helper: parse activity date → Carbon ──────────────────────────
        $parseActDate = function (array $act): ?Carbon {
            $d = $act['date'] ?? null;
            if (!$d) return null;
            try { return Carbon::parse($d); } catch (\Throwable $e) { return null; }
        };

        $parseAlertDate = function (array $alert): ?Carbon {
            $ts = $alert['createdAt'] ?? null;
            if (!$ts) return null;
            try {
                if ($ts instanceof \Google\Cloud\Core\Timestamp) {
                    return Carbon::createFromTimestamp($ts->get()->getTimestamp());
                }
                return Carbon::parse((string) $ts);
            } catch (\Throwable $e) { return null; }
        };

        // ── Filter activities & alerts by date range ──────────────────────
        $filteredActivities = array_filter($activities, function ($act) use ($startDate, $endDate, $parseActDate) {
            $d = $parseActDate($act);
            return $d && $d->between($startDate, $endDate);
        });

        $filteredAlerts = array_filter($alerts, function ($alert) use ($startDate, $endDate, $parseAlertDate) {
            $d = $parseAlertDate($alert);
            return $d && $d->between($startDate, $endDate);
        });

        $totalActivities = count($filteredActivities);
        $totalAlerts     = count($filteredAlerts);

        // ── Trend chart: build month buckets for the filtered range ────────
        $rangeMonths  = (int) $startDate->copy()->startOfMonth()
                            ->diffInMonths($endDate->copy()->startOfMonth()) + 1;
        $rangeMonths  = max(1, min($rangeMonths, 24)); // cap at 24

        $monthLabels      = [];
        $monthCountsKeyed = [];
        $cursor = $startDate->copy()->startOfMonth();
        for ($i = 0; $i < $rangeMonths; $i++) {
            $key                    = $cursor->format('Y-m');
            $monthLabels[]          = $cursor->format('M Y');
            $monthCountsKeyed[$key] = 0;
            $cursor->addMonth();
        }

        foreach ($filteredActivities as $act) {
            $d = $parseActDate($act);
            if ($d) {
                $key = $d->format('Y-m');
                if (isset($monthCountsKeyed[$key])) $monthCountsKeyed[$key]++;
            }
        }

        $alertCountsKeyed = array_fill_keys(array_keys($monthCountsKeyed), 0);
        foreach ($filteredAlerts as $alert) {
            $d = $parseAlertDate($alert);
            if ($d) {
                $key = $d->format('Y-m');
                if (isset($alertCountsKeyed[$key])) $alertCountsKeyed[$key]++;
            }
        }

        $monthCounts = array_values($monthCountsKeyed);
        $alertCounts = array_values($alertCountsKeyed);

        // ── Activities per caregiver (top 8, filtered) ─────────────────────
        $actPerCaregiver = [];
        foreach ($filteredActivities as $act) {
            $cid  = $act['caregiverID'] ?? '';
            $name = $cid ? ($caregiverMap[$cid] ?? 'Unknown') : 'Unknown';
            $actPerCaregiver[$name] = ($actPerCaregiver[$name] ?? 0) + 1;
        }
        arsort($actPerCaregiver);
        $actPerCaregiver = array_slice($actPerCaregiver, 0, 8, true);

        // ── Unique patients per caregiver (top 8, filtered) ───────────────
        $cgPatients = [];
        foreach ($filteredActivities as $act) {
            $cid = $act['caregiverID'] ?? '';
            $pid = $act['patientID']   ?? '';
            if ($cid && $pid) $cgPatients[$cid][$pid] = true;
        }
        $patPerCaregiver = [];
        foreach ($cgPatients as $cid => $pids) {
            $name = $caregiverMap[$cid] ?? 'Unknown';
            $patPerCaregiver[$name] = count($pids);
        }
        arsort($patPerCaregiver);
        $patPerCaregiver = array_slice($patPerCaregiver, 0, 8, true);

        // ── Patient age distribution (always unfiltered — based on birthDate) ─
        $ageGroups = ['0–20' => 0, '21–40' => 0, '41–60' => 0, '61–80' => 0, '81+' => 0, 'Unknown' => 0];
        foreach ($patients as $p) {
            $bd = $p['birthDate'] ?? null;
            if (!$bd) { $ageGroups['Unknown']++; continue; }
            try {
                $age = Carbon::parse($bd)->age;
                if      ($age <= 20) $ageGroups['0–20']++;
                elseif  ($age <= 40) $ageGroups['21–40']++;
                elseif  ($age <= 60) $ageGroups['41–60']++;
                elseif  ($age <= 80) $ageGroups['61–80']++;
                else                 $ageGroups['81+']++;
            } catch (\Throwable $e) {
                $ageGroups['Unknown']++;
            }
        }

        // ── Pass filter state back to the view ────────────────────────────
        $filterStart = $startDate->format('Y-m-d');
        $filterEnd   = $endDate->format('Y-m-d');
        $filterLabel = $startDate->format('M d, Y') . ' – ' . $endDate->format('M d, Y');

        return view('reports.index', compact(
            'totalPatients', 'totalCaregivers', 'totalActivities', 'totalDevices', 'totalAlerts',
            'monthLabels', 'monthCounts', 'alertCounts',
            'actPerCaregiver', 'patPerCaregiver',
            'ageGroups',
            'preset', 'filterStart', 'filterEnd', 'filterLabel',
        ));
    }
}
