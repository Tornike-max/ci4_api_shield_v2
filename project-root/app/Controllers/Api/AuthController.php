<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AuthController extends ResourceController
{
    public function login()
    {
        if (auth()->loggedIn()) {
            auth()->logout();
        }

        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            $response = [
                'status' => 'false',
                'message' => 'Validation Error',
                'errors' => $errors
            ];
        } else {
            $validatedData = $this->validator->getValidated();

            $authentication = auth()->attempt($validatedData);

            if (!$authentication->isOK()) {
                $response = [
                    'status' => 'false',
                    'message' => 'Incorrect Credentials',
                ];
            } else {
                $user = model(UserModel::class)->findById(auth()->id());

                $token = $user->generateAccessToken('access_token');
                $auth_token = $token->raw_token;

                $response = [
                    'status' => true,
                    'message' => 'User Login Successfully',
                    'data' => [
                        'token' => $auth_token
                    ]
                ];
            }
        }

        return $this->respondCreated($response);
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
        } else {
            $user = model(UserModel::class);

            $validatedData = $this->validator->getValidated();

            $userEntity = new User($validatedData);

            if ($user->save($userEntity)) {
                $response = [
                    'status' => true,
                    'message' => 'Success',
                ];
            };
        }

        return $this->respondCreated($response);
    }

    public function invalidAccess()
    {
        return $this->respondCreated([
            'status' => false,
            'message' => 'You are not authorized to make this action'
        ]);
    }

    public function profile()
    {
        $userId = auth()->id();

        $userObject = model(UserModel::class)->findById($userId);

        if (!isset($userObject)) {
            $response = [
                'status' => false,
                'message' => '404-User Not Found!',
                'data' => []
            ];
        } else {
            $response = [
                'status' => true,
                'message' => 'Success',
                'data' => $userObject,
            ];
        }

        return $this->respondCreated($response);
    }

    public function logout()
    {
        $user = auth()->user();
        if (isset($user)) {
            $user->revokeAllAccessTokens();
            auth()->logout();
            $response = [
                'status' => true,
                'message' => 'User Logged Out',
                'data' => []
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Failed to log out',
                'data' => []
            ];
        }



        return $this->respondCreated($response);
    }
}
