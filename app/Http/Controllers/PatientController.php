<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Illuminate\Http\Request;

class PatientController extends Controller
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
        $patients = $this->db->fetch('patients');

        return view('patients.index', [
            'patients'      => $patients->toArray(),
            'totalPatients' => $patients->count(),
        ]);
    }

    // -------------------------------------------------------------------------
    // Create form
    // -------------------------------------------------------------------------

    public function create()
    {
        return view('patients.create');
    }

    // -------------------------------------------------------------------------
    // Store
    // -------------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'firstName'  => 'required|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'lastName'   => 'required|string|max:100',
            'birthDate'  => 'required|date',
            'address'    => 'nullable|string|max:255',
            'deviceID'   => 'nullable|string|max:100',
        ]);

        $fullName = trim(
            $request->firstName . ' ' .
            ($request->middleName ? $request->middleName . ' ' : '') .
            $request->lastName
        );

        $this->db->create('patients', [
            'firstName'  => $request->firstName,
            'middleName' => $request->middleName ?? '',
            'lastName'   => $request->lastName,
            'fullName'   => $fullName,
            'birthDate'  => $request->birthDate,
            'address'    => $request->address ?? '',
            'deviceID'   => $request->deviceID ?? '',
        ]);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient added successfully.');
    }

    // -------------------------------------------------------------------------
    // Edit form
    // -------------------------------------------------------------------------

    public function edit(string $id)
    {
        $all      = $this->db->fetch('patients');
        $patient  = $all->firstWhere('docID', $id);

        if (! $patient) {
            abort(404, 'Patient not found.');
        }

        return view('patients.edit', [
            'patient'   => $patient,
            'patientID' => $id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    public function update(Request $request, string $id)
    {
        $request->validate([
            'firstName'  => 'required|string|max:100',
            'middleName' => 'nullable|string|max:100',
            'lastName'   => 'required|string|max:100',
            'birthDate'  => 'required|date',
            'address'    => 'nullable|string|max:255',
            'deviceID'   => 'nullable|string|max:100',
        ]);

        $fullName = trim(
            $request->firstName . ' ' .
            ($request->middleName ? $request->middleName . ' ' : '') .
            $request->lastName
        );

        $this->db->edit('patients', $id, [
            'firstName'  => $request->firstName,
            'middleName' => $request->middleName ?? '',
            'lastName'   => $request->lastName,
            'fullName'   => $fullName,
            'birthDate'  => $request->birthDate,
            'address'    => $request->address ?? '',
            'deviceID'   => $request->deviceID ?? '',
        ]);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Delete
    // -------------------------------------------------------------------------

    public function destroy(string $id)
    {
        $this->db->destroy('patients', $id);

        return redirect()->route('patients.index')
                         ->with('success', 'Patient deleted successfully.');
    }
}
