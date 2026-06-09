<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuardianController extends Controller
{
    protected FirestoreRepository $db;

    public function __construct(FirestoreRepository $repo)
    {
        $this->db = $repo;
    }

    // -------------------------------------------------------------------------
    // List all guardians (userType = 3)
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

        $guardians = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 3);

        return view('guardians.index', [
            'guardians' => $guardians,
            'totalGuardians' => count($guardians),
        ]);
    }

    // -------------------------------------------------------------------------
    // Create form
    // -------------------------------------------------------------------------

    public function create()
    {
        return view('guardians.create');
    }

    // -------------------------------------------------------------------------
    // Store new guardian
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
            'userType' => 3,
            'registeredDate' => now()->toDateString(),
        ]);

        return redirect()->route('guardians.index')
            ->with('success', 'Guardian added successfully.');
    }

    // -------------------------------------------------------------------------
    // Edit form
    // -------------------------------------------------------------------------

    public function edit(string $id)
    {
        $all = $this->db->fetch('users');
        $existing = $all->firstWhere('docID', $id);

        if (! $existing) {
            abort(404, 'Guardian not found.');
        }

        return view('guardians.edit', [
            'guardian' => $existing,
            'userID' => $id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Update
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

        $all = $this->db->fetch('users');
        $existing = $all->firstWhere('docID', $id);

        $password = $request->filled('password')
            ? Hash::make($request->password)
            : ($existing['password'] ?? '');

        $this->db->edit('users', $id, [
            'firstName' => $request->firstName,
            'middleName' => $request->middleName ?? '',
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phoneNumber' => $request->phoneNumber ?? '',
            'address' => $request->address ?? '',
            'userType' => 3,
            'password' => $password,
            'registeredDate' => $existing['registeredDate'] ?? now()->toDateString(),
        ]);

        return redirect()->route('guardians.index')
            ->with('success', 'Guardian updated successfully.');
    }

    // -------------------------------------------------------------------------
    // Delete
    // -------------------------------------------------------------------------

    public function destroy(string $id)
    {
        $this->db->destroy('users', $id);

        return redirect()->route('guardians.index')
            ->with('success', 'Guardian deleted successfully.');
    }
}
