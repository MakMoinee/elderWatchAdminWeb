<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CaregiverController extends Controller
{
    protected FirestoreRepository $db;

    public function __construct(FirestoreRepository $repo)
    {
        $this->db = $repo;
    }

    // -------------------------------------------------------------------------
    // List all caregivers (userType = 2)
    // -------------------------------------------------------------------------

    public function index()
    {
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);
        $activities = $this->db->fetch('activity');
        $patients   = $this->db->fetch('patients');

        // Build patient docID -> full name
        $patientNames = [];
        foreach ($patients as $p) {
            $patientNames[$p['docID']] = $p['fullName'] ?? trim(($p['firstName'] ?? '') . ' ' . ($p['lastName'] ?? ''));
        }

        // Build caregiverDocID -> [patient names] (unique)
        $caregiverPatientMap = [];
        foreach ($activities as $a) {
            $cid = $a['caregiverID'] ?? '';
            $pid = $a['patientID']   ?? '';
            if ($cid && $pid && isset($patientNames[$pid])) {
                $caregiverPatientMap[$cid][] = $patientNames[$pid];
            }
        }
        foreach ($caregiverPatientMap as $cid => $names) {
            $caregiverPatientMap[$cid] = array_unique($names);
        }

        return view('caregivers.index', [
            'caregivers'          => $caregivers,
            'totalCaregivers'     => count($caregivers),
            'caregiverPatientMap' => $caregiverPatientMap,
        ]);
    }

    // -------------------------------------------------------------------------
    // Show add form
    // -------------------------------------------------------------------------

    public function create()
    {
        return view('caregivers.create');
    }

    // -------------------------------------------------------------------------
    // Save new caregiver
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|max:200',
            'password' => 'required|string|min:6|confirmed',
            'phoneNumber' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
        ]);

        $this->db->create('users', [
            'firstName' => $request->firstName,
            'middleName' => $request->middleName ?? '',
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phoneNumber' => $request->phoneNumber ?? '',
            'address' => $request->address ?? '',
            'userType' => 2,
            'registeredDate' => now()->toDateString(),
        ]);

        return redirect()->route('caregivers.index')
            ->with('success', 'Caregiver added successfully.');
    }

    // -------------------------------------------------------------------------
    // Show edit form
    // -------------------------------------------------------------------------

    public function edit(string $id)
    {
        // $id is the Firestore document ID (docID), set by FirestoreRepository::fetch()
        $all      = $this->db->fetch('users');
        $existing = $all->firstWhere('docID', $id);

        if (! $existing) {
            abort(404, 'Caregiver not found.');
        }

        // Pass $userID = $id so the blade can use it in the form action
        return view('caregivers.edit', [
            'caregiver' => $existing,
            'userID'    => $id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Update caregiver
    // -------------------------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $request->validate([
            'firstName' => 'required|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|max:200',
            'phoneNumber' => 'nullable|string|max:30',
            'address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // Fetch existing doc to preserve password + registeredDate
        $all      = $this->db->fetch('users');
        $existing = $all->firstWhere('docID', $id);

        // Only hash + store a new password if the field was filled
        $password = ($request->filled('password'))
            ? Hash::make($request->password)
            : ($existing['password'] ?? '');

        $this->db->edit('users', $id, [
            'firstName' => $request->firstName,
            'middleName' => $request->middleName ?? '',
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber ?? '',
            'address' => $request->address ?? '',
            'userType' => 2,
            'password' => $password,
            'registeredDate' => $existing['registeredDate'] ?? now()->toDateString(),
        ]);

        return redirect()->route('caregivers.index')
            ->with('success', 'Caregiver updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Delete caregiver
    // -------------------------------------------------------------------------

    public function destroy(string $id)
    {
        $this->db->destroy('users', $id);

        return redirect()->route('caregivers.index')
            ->with('success', 'Caregiver deleted successfully.');
    }
}
