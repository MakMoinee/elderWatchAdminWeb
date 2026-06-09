<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected FirestoreRepository $db;

    public function __construct(FirestoreRepository $repo)
    {
        $this->db = $repo;
    }

    // -------------------------------------------------------------------------
    // List
    // -------------------------------------------------------------------------

    public function index()
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
        $activities = $this->db->fetch('activity');
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $patients   = $this->db->fetch('patients');

        // Build lookup maps: docID -> display name
        $caregiverMap = [];
        foreach ($caregivers as $c) {
            $caregiverMap[$c['docID']] = trim(($c['firstName'] ?? '') . ' ' . ($c['lastName'] ?? ''));
        }

        $patientMap = [];
        foreach ($patients as $p) {
            $patientMap[$p['docID']] = $p['fullName'] ?? trim(($p['firstName'] ?? '') . ' ' . ($p['lastName'] ?? ''));
        }

        return view('activities.index', [
            'activities'   => $activities->toArray(),
            'total'        => $activities->count(),
            'caregiverMap' => $caregiverMap,
            'patientMap'   => $patientMap,
        ]);
    }

    // -------------------------------------------------------------------------
    // Create form
    // -------------------------------------------------------------------------

    public function create()
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
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $patients   = $this->db->fetch('patients');

        return view('activities.create', [
            'caregivers' => $caregivers,
            'patients'   => $patients->toArray(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Store
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'caregiverID' => 'required|string',
            'patientID'   => 'required|string',
            'date'        => 'required|date',
            'time'        => 'required|string',
        ]);

        $this->db->create('activity', [
            'caregiverID' => $request->caregiverID,
            'patientID'   => $request->patientID,
            'date'        => $request->date,
            'time'        => $request->time,
        ]);

        return redirect()->route('activities.index')
                         ->with('success', 'Schedule added successfully.');
    }

    // -------------------------------------------------------------------------
    // Edit form
    // -------------------------------------------------------------------------

    public function edit(string $id)
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

        $all      = $this->db->fetch('activity');
        $activity = $all->firstWhere('docID', $id);

        if (! $activity) {
            abort(404, 'Activity not found.');
        }

        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $patients   = $this->db->fetch('patients');

        return view('activities.edit', [
            'activity'   => $activity,
            'activityID' => $id,
            'caregivers' => $caregivers,
            'patients'   => $patients->toArray(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $request->validate([
            'caregiverID' => 'required|string',
            'patientID'   => 'required|string',
            'date'        => 'required|date',
            'time'        => 'required|string',
        ]);

        $this->db->edit('activity', $id, [
            'caregiverID' => $request->caregiverID,
            'patientID'   => $request->patientID,
            'date'        => $request->date,
            'time'        => $request->time,
        ]);

        return redirect()->route('activities.index')
                         ->with('success', 'Schedule updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Delete
    // -------------------------------------------------------------------------

    public function destroy(string $id)
    {
        $this->db->destroy('activity', $id);

        return redirect()->route('activities.index')
                         ->with('success', 'Schedule deleted successfully.');
    }
}
