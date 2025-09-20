<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\User;
use App\Traits\ApiResponses;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    use ApiResponses;

    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
        helper(['url', 'form']);
    }

    public function register()
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $validationRules = [
                "name" => "required|min_length[3]|max_length[100]",
                "email" => "required|valid_email|is_unique[users.email]",
                "password" => "required|min_length[8]|max_length[100]|matches[password_confirm]",
                "role" => "required|in_list[learner,instructor]",
            ];

            if(!$this->validate($validationRules)) {
                return $this->respondWithValidationError($this->validator->getErrors());
            }

            $password = $this->request->getJSON(true)['password'] ?? $this->request->getPost('password');
            $userData = [
                'name' => $this->request->getJSON(true)['name'] ?? $this->request->getPost('name'),
                'email' => $this->request->getJSON(true)['email'] ?? $this->request->getPost('email'),
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $this->request->getJSON(true)['role'] ?? $this->request->getPost('role'),
            ];

            if ($this->userModel->save($userData)) {
                $user = $this->userModel->find($this->userModel->getInsertID());

                $this->userModel->sendEmailVerificationNotification($user['email']);

                unset($user['password']);

                $db->transCommit();
                return $this->respondWithSuccess($user, 'User registered successfully, We have sent the otp code', 201);
            } else {
                $db->transRollback();
                return $this->respondWithError('Failed to register user', 500);
            }
        } catch (\Throwable $th) {
            $db->transRollback();
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function login()
    {
        //
    }
}
