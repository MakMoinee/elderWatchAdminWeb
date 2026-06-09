<?php

namespace App\Http\Controllers;

use App\Services\Firebase\Repository\FirestoreRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlertController extends Controller
{
    protected FirestoreRepository $db;

    public function __construct(FirestoreRepository $repo)
    {
        $this->db = $repo;
    }

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

        $history    = $this->db->fetch('activity_history');
        $caregivers = $this->db->fetchWithWhere('users', 'userType', '=', intValue: 2);

        // Build caregiverID -> full name map
        $caregiverMap = [];
        foreach ($caregivers as $c) {
            $caregiverMap[$c['docID']] = trim(($c['firstName'] ?? '') . ' ' . ($c['lastName'] ?? ''));
        }

        // Sort newest-first (createdAt may be a Timestamp or string)
        $sorted = $history->sortByDesc(function ($row) {
            $ts = $row['createdAt'] ?? null;
            if ($ts instanceof \Google\Cloud\Core\Timestamp) {
                return $ts->get()->getTimestamp();
            }
            return strtotime((string) $ts) ?: 0;
        });

        return view('alerts.index', [
            'alerts'       => $sorted->values()->toArray(),
            'totalAlerts'  => $history->count(),
            'caregiverMap' => $caregiverMap,
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $file      = $request->file('image');
        $path      = $file->storeAs('alerts', $file->getClientOriginalName(), 'public');
        $publicUrl = Storage::url($path);

        $this->db->patch('activity_history', $request->input('id'), ['imagePath' => $publicUrl]);

        return response()->json(['success' => true, 'url' => $publicUrl]);
    }
}
