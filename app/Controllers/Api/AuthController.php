<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\User;
use App\Traits\ApiResponses;
use App\Traits\Tokenable;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;

class AuthController extends BaseController
{
    use ApiResponses, Tokenable;

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

            $userData = [
                'name' => $this->request->getJSON(true)['name'] ?? $this->request->getPost('name'),
                'email' => $this->request->getJSON(true)['email'] ?? $this->request->getPost('email'),
                'password' => $this->request->getJSON(true)['password'] ?? $this->request->getPost('password'),
                'role' => $this->request->getJSON(true)['role'] ?? $this->request->getPost('role'),
            ];

            log_message("debug", $userData["password"]);

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

    public function verifyAccount()
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $validationRules = [
                "email" => "required|valid_email",
                "otp" => "required|numeric|exact_length[6]",
            ];

            if(!$this->validate($validationRules)) {
                return $this->respondWithValidationError($this->validator->getErrors());
            }

            $email = $this->request->getJSON(true)['email'] ?? $this->request->getPost('email');
            $otp = $this->request->getJSON(true)['otp'] ?? $this->request->getPost('otp');

            if ($this->userModel->verifyOtp($email, $otp)) {
                $user = $this->userModel->where('email', $email)->first();
                if ($user) {
                    $this->userModel->update($user['id'], ['email_verified_at' => Time::now()]);
                    
                    $db->transCommit();
                    return $this->respondWithSuccess(['email_verified' => true], 'Email verified successfully');
                } else {
                    $db->transRollback();
                    return $this->respondWithError('User not found', 404);
                }
            } else {
                $db->transRollback();
                return $this->respondWithError('Invalid or expired OTP', 400);
            }
        } catch (\Throwable $th) {
            $db->transRollback();
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function login()
    {
        $db = db_connect();
        $db->transBegin();

        try {
            $validationRules = [
                "email" => "required|valid_email",
                "password" => "required",
            ];

            if(!$this->validate($validationRules)) {
                return $this->respondWithValidationError($this->validator->getErrors());
            }

            $email = $this->request->getJSON(true)['email'] ?? $this->request->getPost('email');
            $password = $this->request->getJSON(true)['password'] ?? $this->request->getPost('password');

            $user = $this->userModel->where('email', $email)->first();

            if (! $user || ! password_verify($password, $user['password'])) {
                return $this->respondWithError("email or password is incorrect", 401);
            }

            if($user["email_verified_at"] == NULL) {
                $this->userModel->sendEmailVerificationNotification($user['email']);
                return $this->respondWithError("your account is not verified, We have sent it now. Check your email. ", 403);

            }

            $tokenResult = $this->createToken($user["id"], "login_" . $user["role"]);

            unset($user['password']);

            $data = [
                "user" => $user,
                "token_info" => $tokenResult,
            ];

            $db->transCommit();
            return $this->respondWithSuccess($data, 'User logged in successfully');

        } catch (\Throwable $th) {
            $db->transRollback();
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function forgotPassword()
    {
        try {
            $validationRules = [
                "email" => "required|valid_email|is_not_unique[users.email]",
            ];

            if(!$this->validate($validationRules)) {
                return $this->respondWithValidationError($this->validator->getErrors());
            }

            $email = $this->request->getJSON(true)['email'] ?? $this->request->getPost('email');

            $user = $this->userModel->where('email', $email)->first();

            if (! $user) {
                return $this->respondWithError("email is incorrect", 401);
            }


            if(! $this->userModel->sendForgotPasswordCode($user['email'])) {
                return $this->respondWithError("Failed to send reset password code");
            }

            return $this->respondWithSuccess([], "We have sent the reset password link to your email");

        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }
    }

    public function resetPassword()
    {
        try {
            $validationRules = [
                "email" => "required|valid_email|is_not_unique[users.email]",
                "password" => "required|min_length[8]|max_length[100]|matches[password_confirm]",
                "otp" => "required|numeric|exact_length[6]",
            ];

            if(!$this->validate($validationRules)) {
                return $this->respondWithValidationError($this->validator->getErrors());
            }

            $email = $this->request->getJSON(true)['email'] ?? $this->request->getPost('email');
            $password = $this->request->getJSON(true)['password'] ?? $this->request->getPost('password');
            $otp = $this->request->getJSON(true)['otp'] ?? $this->request->getPost('otp');

            $user = $this->userModel->where('email', $email)->first();

            if (! $user) {
                return $this->respondWithError("User Not Found", 401);
            }

            if (! $this->userModel->verifyOtp($email, $otp)) {
                return $this->respondWithError("Verification code is incorrect", 403);
            }

            $this->userModel->update($user['id'], ['password' => $password]);


            return $this->respondWithSuccess([], "Password reset successfully");

        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(), $th->getCode() ?: 500);
        }

    }
}
