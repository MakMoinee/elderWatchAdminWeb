<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Illuminate\Http\Request;

class CctvController extends Controller
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
        $devices  = $this->db->fetch('devices');
        $patients = $this->db->fetch('patients');


        // Build a quick lookup: deviceID => patient fullName
        $patientMap = [];
        foreach ($patients as $p) {
            if (!empty($p['deviceID'])) {
                $patientMap[$p['deviceID']] = $p['fullName'] ?? 'Unknown';
            }
        }

        return view('cctv.index', [
            'devices'      => $devices->toArray(),
            'totalDevices' => $devices->count(),
            'patientMap'   => $patientMap,
        ]);
    }

    // -------------------------------------------------------------------------
    // Create form
    // -------------------------------------------------------------------------

    public function create()
    {
        $patients = $this->db->fetch('patients');

        return view('cctv.create', [
            'patients' => $patients->toArray(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Store
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'deviceID'  => 'required|string|max:100',
            'ip'        => 'required|string|max:100',
            'username'  => 'required|string|max:100',
            'password'  => 'required|string|max:255',
            'status'    => 'required|in:online,offline',
            'patientID' => 'nullable|string|max:100',
        ]);

        $this->db->create('devices', [
            'deviceID' => $request->deviceID,
            'ip'       => $request->input('ip'),
            'username' => $request->username,
            'password' => $request->password,
            'status'   => $request->status,
            'userID'   => $request->patientID ?? '',
        ]);

        // If a patient was linked, write deviceID back to the patient record
        if ($request->filled('patientID')) {
            $patients = $this->db->fetch('patients');
            $patient  = $patients->firstWhere('docID', $request->patientID);
            if ($patient) {
                $this->db->patch('patients', $request->patientID, [
                    'deviceID' => $request->deviceID,
                ]);
            }
        }

        return redirect()->route('cctv.index')
                         ->with('success', 'CCTV device added successfully.');
    }

    // -------------------------------------------------------------------------
    // Edit form
    // -------------------------------------------------------------------------

    public function edit(string $id)
    {
        $all     = $this->db->fetch('devices');
        $device  = $all->firstWhere('docID', $id);

        if (! $device) {
            abort(404, 'Device not found.');
        }

        $patients = $this->db->fetch('patients');

        // Find patient currently linked to this device
        $linkedPatient = $patients->firstWhere('deviceID', $device['deviceID'] ?? '');

        return view('cctv.edit', [
            'device'        => $device,
            'deviceDocID'   => $id,
            'patients'      => $patients->toArray(),
            'linkedPatient' => $linkedPatient,
        ]);
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ip'        => 'required|string|max:100',
            'username'  => 'required|string|max:100',
            'status'    => 'required|in:online,offline',
            'patientID' => 'nullable|string|max:100',
        ]);

        // Fetch current device
        $all    = $this->db->fetch('devices');
        $device = $all->firstWhere('docID', $id);
        if (! $device) {
            abort(404, 'Device not found.');
        }

        $newPassword = $request->filled('password')
            ? $request->password
            : ($device['password'] ?? '');

        $this->db->edit('devices', $id, [
            'ip'       => $request->input('ip'),
            'username' => $request->username,
            'password' => $newPassword,
            'status'   => $request->status,
            'userID'   => $request->patientID ?? '',
        ]);

        // Update patient linkage -------------------------------------------
        $patients = $this->db->fetch('patients');
        $deviceID = $device['deviceID'] ?? '';

        // Clear deviceID from any patient previously linked to this device
        foreach ($patients as $p) {
            if (($p['deviceID'] ?? '') === $deviceID && $p['docID'] !== $request->patientID) {
                $this->db->patch('patients', $p['docID'], ['deviceID' => '']);
            }
        }

        // Set deviceID on newly linked patient
        if ($request->filled('patientID')) {
            $newPatient = $patients->firstWhere('docID', $request->patientID);
            if ($newPatient) {
                $this->db->patch('patients', $request->patientID, ['deviceID' => $deviceID]);
            }
        }

        return redirect()->route('cctv.index')
                         ->with('success', 'CCTV device updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Delete
    // -------------------------------------------------------------------------

    public function destroy(string $id)
    {
        // Unlink patient before deleting
        $all    = $this->db->fetch('devices');
        $device = $all->firstWhere('docID', $id);

        if ($device && !empty($device['deviceID'])) {
            $patients = $this->db->fetch('patients');
            foreach ($patients as $p) {
                if (($p['deviceID'] ?? '') === $device['deviceID']) {
                    $this->db->patch('patients', $p['docID'], ['deviceID' => '']);
                }
            }
        }

        $this->db->destroy('devices', $id);

        return redirect()->route('cctv.index')
                         ->with('success', 'CCTV device deleted successfully.');
    }
}
