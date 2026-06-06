<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Exception;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $db;

    public function __construct(FirestoreRepository $repo)
    {
        $this->db = $repo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Handle login form submission.
     */
    public function store(Request $request)
    {
        if (isset($request->btnLogin)) {
            $email = $request->email;
            $password = $request->password;
            try {
                $data = $this->db->login($email, $password);
            } catch (Exception $e) {
                dd($e);
            }
            if (count($data) > 0) {
                $users = $data[0];
                dd($users->toArray());
                if ($users->userType == 2) {
                    session()->put('errorLoginUnauthorized', true);
                    return redirect('/login');
                } else {
                    session()->put('successLogin', true);
                    session()->put('admin', $users);

                    return redirect()->route('dashboard');
                }
            } else {
                session()->put('errorLogin', true);
            }

            return redirect('/login');
        } else {
            session()->put('errorLogin', true);

            return redirect('/login');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
