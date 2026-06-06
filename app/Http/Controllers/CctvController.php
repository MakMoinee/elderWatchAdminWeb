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
        $devices    = $this->db->fetch('devices');
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);

        // Build a quick lookup: caregiver docID => caregiver full name
        $caregiverMap = [];
        foreach ($caregivers as $cg) {
            if (!empty($cg['docID'])) {
                $name = trim(($cg['firstName'] ?? '') . ' ' . ($cg['lastName'] ?? '')) ?: 'Unknown';
                $caregiverMap[$cg['docID']] = $name;
            }
        }

        return view('cctv.index', [
            'devices'      => $devices->toArray(),
            'totalDevices' => $devices->count(),
            'caregiverMap' => $caregiverMap,
        ]);
    }

    // -------------------------------------------------------------------------
    // Create form
    // -------------------------------------------------------------------------

    public function create()
    {
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);

        return view('cctv.create', [
            'caregivers' => $caregivers,
        ]);
    }

    // -------------------------------------------------------------------------
    // Store
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'deviceID'    => 'required|string|max:100',
            'ip'          => 'required|string|max:100',
            'username'    => 'required|string|max:100',
            'password'    => 'required|string|max:255',
            'status'      => 'required|in:online,offline',
            'caregiverID' => 'nullable|string|max:100',
        ]);

        $this->db->create('devices', [
            'deviceID' => $request->deviceID,
            'ip'       => $request->input('ip'),
            'username' => $request->username,
            'password' => $request->password,
            'status'   => $request->status,
            'userID'   => $request->caregiverID ?? '',
        ]);

        // If a caregiver was linked, write deviceID back to the caregiver record
        if ($request->filled('caregiverID')) {
            $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
            $caregiver  = collect($caregivers)->firstWhere('docID', $request->caregiverID);
            if ($caregiver) {
                $this->db->patch('users', $request->caregiverID, [
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

        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);

        // Find caregiver currently linked to this device (device stores userID = caregiver's docID)
        $linkedCaregiver = collect($caregivers)->firstWhere('docID', $device['userID'] ?? '');

        return view('cctv.edit', [
            'device'          => $device,
            'deviceDocID'     => $id,
            'caregivers'      => $caregivers,
            'linkedCaregiver' => $linkedCaregiver,
        ]);
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $request->validate([
            'ip'          => 'required|string|max:100',
            'username'    => 'required|string|max:100',
            'status'      => 'required|in:online,offline',
            'caregiverID' => 'nullable|string|max:100',
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
            'userID'   => $request->caregiverID ?? '',
        ]);

        // Update caregiver linkage ----------------------------------------
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $deviceID   = $device['deviceID'] ?? '';

        // Clear deviceID from any caregiver previously linked to this device
        foreach ($caregivers as $cg) {
            if (($cg['deviceID'] ?? '') === $deviceID && $cg['docID'] !== $request->caregiverID) {
                $this->db->patch('users', $cg['docID'], ['deviceID' => '']);
            }
        }

        // Set deviceID on newly linked caregiver
        if ($request->filled('caregiverID')) {
            $newCaregiver = collect($caregivers)->firstWhere('docID', $request->caregiverID);
            if ($newCaregiver) {
                $this->db->patch('users', $request->caregiverID, ['deviceID' => $deviceID]);
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
            $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
            foreach ($caregivers as $cg) {
                if (($cg['deviceID'] ?? '') === $device['deviceID']) {
                    $this->db->patch('users', $cg['docID'], ['deviceID' => '']);
                }
            }
        }

        $this->db->destroy('devices', $id);

        return redirect()->route('cctv.index')
                         ->with('success', 'CCTV device deleted successfully.');
    }
}
