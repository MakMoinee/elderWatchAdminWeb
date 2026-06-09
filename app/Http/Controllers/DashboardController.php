<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;

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
        
        // Fetch counts from Firestore (userType: 2 = caregiver, 3 = guardian)
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $guardians  = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 3);
        $patients   = $this->db->fetch('patients');
        $cctv       = $this->db->fetch('cctv_devices');
        $alerts     = $this->db->fetch('alerts');
        $reports    = $this->db->fetch('reports');

        // Build recent reports list (latest 5, newest first)
        $recentReports = $reports
            ->sortByDesc(fn ($r) => $r['date'] ?? '')
            ->take(5)
            ->values()
            ->toArray();

        return view('dashboard.index', [
            'caregiverCount' => count($caregivers),
            'guardianCount'  => count($guardians),
            'patientCount'   => $patients->count(),
            'cctvCount'      => $cctv->count(),
            'alertCount'     => $alerts->count(),
            'recentReports'  => $recentReports,
        ]);
    }
}
