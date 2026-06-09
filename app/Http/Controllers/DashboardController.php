<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected FirestoreRepository $db;

    public function __construct(FirestoreRepository $repo)
    {
        $this->db = $repo;
    }

    public function index()
    {
        if(session()->has('admin')) {
            $admin = session()->get('admin');
            if ($admin->userType != 1) {
                session()->put('errorLoginUnauthorized', true);
                return redirect('/login');
            }
        } else {
            session()->put('errorLoginUnauthorized', true);
            return redirect('/login');
        }

        // Fetch from Firestore
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $guardians  = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 3);
        $patients   = $this->db->fetch('patients');
        $cctv       = $this->db->fetch('devices');
        $history    = $this->db->fetch('activity_history');

        // Caregiver name map: docID → full name
        $caregiverMap = [];
        foreach ($caregivers as $c) {
            $caregiverMap[$c['docID']] = trim(($c['firstName'] ?? '') . ' ' . ($c['lastName'] ?? ''));
        }

        // Sort activity_history newest-first, take latest 5
        $recentAlerts = $history
            ->sortByDesc(function ($row) {
                $ts = $row['createdAt'] ?? null;
                if ($ts instanceof \Google\Cloud\Core\Timestamp) {
                    return $ts->get()->getTimestamp();
                }
                return strtotime((string) $ts) ?: 0;
            })
            ->take(5)
            ->values()
            ->map(function ($row) use ($caregiverMap) {
                $ts  = $row['createdAt'] ?? null;
                $dt  = null;
                if ($ts instanceof \Google\Cloud\Core\Timestamp) {
                    $dt = Carbon::createFromTimestamp($ts->get()->getTimestamp());
                } elseif ($ts) {
                    try { $dt = Carbon::parse((string) $ts); } catch (\Throwable $e) {}
                }

                $status       = strtolower($row['status'] ?? 'unknown');
                $caregiverID  = $row['caregiverID'] ?? '';
                $caregiverName = $caregiverID ? ($caregiverMap[$caregiverID] ?? 'Unknown') : 'Unknown';

                return [
                    'status'        => $status,
                    'caregiverName' => $caregiverName,
                    'ip'            => $row['ip'] ?? '—',
                    'imagePath'     => $row['imagePath'] ?? null,
                    'timestamp'     => $dt ? $dt->format('M d, Y g:i A') : '—',
                    'timeAgo'       => $dt ? $dt->diffForHumans() : '—',
                ];
            })
            ->toArray();

        return view('dashboard.index', [
            'caregiverCount' => count($caregivers),
            'guardianCount'  => count($guardians),
            'patientCount'   => $patients->count(),
            'cctvCount'      => $cctv->count(),
            'alertCount'     => $history->count(),
            'recentAlerts'   => $recentAlerts,
        ]);
    }
}
