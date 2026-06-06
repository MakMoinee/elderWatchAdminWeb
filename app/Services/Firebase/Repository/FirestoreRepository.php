<?php

namespace App\Services\Firebase\Repository;

use App\Models\Users;
use App\Services\Firebase\Contracts\FireStore;
use Exception;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

final class FirestoreRepository implements FireStore
{
    protected FirestoreClient $db;

    public function __construct()
    {
        // credentialsConfig.keyFile bypasses ADC entirely — gRPC's C-extension
        // cannot read Laravel's cached env, so we pass the service-account JSON
        // path directly into CredentialsWrapper::build().
        //
        // transport => 'rest' avoids the gRPC PHP extension segfault on Windows
        // ZTS builds. The REST transport uses Guzzle over HTTPS and is fully
        // supported by google/cloud-firestore.
        $this->db = new FirestoreClient([
            'projectId'         => config(
                'firebase.projects.app.project_id',
                env('FIREBASE_PROJECT_ID', 'elderwatch-6d7b5')
            ),
            'credentialsConfig' => [
                'keyFile' => base_path('elderwatch.json'),
            ],
            'transport'         => 'rest',
        ]);
    }

    // -------------------------------------------------------------------------
    // Create
    // -------------------------------------------------------------------------

    public function create(string $collection, array $data): string
    {
        $docRef = $this->db
            ->collection($collection)
            ->newDocument();

        $docRef->set($data);

        return $docRef->id();
    }

    // -------------------------------------------------------------------------
    // Read – all documents
    // -------------------------------------------------------------------------

    public function fetch(string $collection): Collection
    {
        $snapshot = $this->db
            ->collection($collection)
            ->documents();

        if ($snapshot->isEmpty()) {
            return collect();
        }

        $rows = [];
        foreach ($snapshot as $doc) {
            $row = $doc->data();
            $row['docID'] = $doc->id();
            $rows[] = $row;
        }

        return collect($rows);
    }

    // -------------------------------------------------------------------------
    // Read – with where clause
    // -------------------------------------------------------------------------

    public function fetchWithWhere(
        string $collection,
        string $fieldName,
        string $whereOperator,
        int $intValue = 0,
        string $strValue = ''
    ): array {
        $actualValue = ($strValue !== '') ? $strValue : $intValue;

        $snapshot = $this->db
            ->collection($collection)
            ->where($fieldName, $whereOperator, $actualValue)
            ->documents();

        $result = [];
        foreach ($snapshot as $doc) {
            $row = $doc->data();
            $row['docID'] = $doc->id();
            $result[] = $row;
        }

        return $result;
    }

    // -------------------------------------------------------------------------
    // Update
    // -------------------------------------------------------------------------

    public function edit(string $collection, string $documentId, array $data): string
    {
        $this->db
            ->collection($collection)
            ->document($documentId)
            ->set($data, ['merge' => true]);

        return $documentId;
    }

    // -------------------------------------------------------------------------
    // Delete
    // -------------------------------------------------------------------------

    public function destroy(string $collection, string $documentId): void
    {
        $this->db
            ->collection($collection)
            ->document($documentId)
            ->delete();
    }

    // -------------------------------------------------------------------------
    // Login
    // -------------------------------------------------------------------------

    public function login(string $email, string $password): array
    {
        try {
            $snapshot = $this->db
                ->collection('users')
                ->where('email', '=', $email)
                ->documents();

            if ($snapshot->isEmpty()) {
                return [];
            }

            $result = [];
            foreach ($snapshot as $doc) {
                $data = $doc->data();

                if (! password_verify($password, $data['password'])) {
                    continue;
                }

                $user = new Users;
                $user->userID = $doc->id();
                $user->email = $data['email'];
                $user->password = $data['password'];
                $user->firstName = $data['firstName'];
                $user->middleName = $data['middleName'];
                $user->lastName = $data['lastName'];
                $user->userType = $data['userType'];

                $result[] = $user;
            }

            return $result;
        } catch (Exception $e) {
            dd($e);
        }

    }

    // -------------------------------------------------------------------------
    // Seed default admin if none exists
    // -------------------------------------------------------------------------

    public function checkIfThereIsAnAdmin(): bool
    {
        $snapshot = $this->db
            ->collection('users')
            ->where('userType', '=', 1)
            ->documents();

        if ($snapshot->isEmpty()) {
            $admin = new Users;
            $admin->email = 'admin@demo.com';
            $admin->password = Hash::make('admin123');
            $admin->firstName = 'Administrator';
            $admin->middleName = 'Administrator';
            $admin->lastName = 'Administrator';
            $admin->secret = 'Administrator';
            $admin->userType = 1;

            $this->create('users', $admin->toArray());
        }

        return true;
    }
}
