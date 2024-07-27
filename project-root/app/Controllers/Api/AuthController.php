<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AuthController extends ResourceController
{
    public function login()
    {
    }

    public function register()
    {

        $rules = [
            'username' => 'required|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'status' => false,
                'message' => 'Validation Failed',
                'errors' => $errors
            ];
            exit;
        }

        $user = model(UserModel::class);

        $validatedData = $this->validator->getValidated();

        $userEntity = new User($validatedData);

        if ($user->save($userEntity)) {
            $response = [
                'status' => true,
                'message' => 'Success',
            ];
        };

        return $this->respondCreated($response);
    }

    public function profile()
    {
    }

    public function logout()
    {
    }
}
