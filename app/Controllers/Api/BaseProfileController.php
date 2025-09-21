<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\User;
use App\Traits\ApiResponses;
use App\Traits\Authorizable;
use App\Traits\CloudinaryTrait;
use CodeIgniter\HTTP\ResponseInterface;

class BaseProfileController extends BaseController
{
    use Authorizable, ApiResponses, CloudinaryTrait;

    protected $user;
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function isAuthorize(): bool
    {
        $this->user = $this->request->user;
        if($this->user && $this->isLearner($this->user)) {
            return true;
        }

        return false;
    }

    public function profile()
    {
        if(!$this->isAuthorize())
        {
            return $this->respondWithError("You don't have permission to access this page");
        }

        $userData = [
            "user_id" => $this->user["id"],
            "name" => $this->user["name"],
            "email" => $this->user["email"],
            "role" => $this->user["role"],
            "is_verified" => $this->user["email_verified_at"] ? true : false,
            "profile_picture" => $this->user["profile_picture"],
            "bio" => $this->user["bio"],
            "social_links" => $this->user["social_links"],
        ];

        return $this->respondWithSuccess($userData, "Profile Retrieved Successfully");
    }

    public function updateProfile()
    {
        if (! $this->isAuthorize()) {
            return $this->respondWithError("You don't have permission to access this page", 403);
        }

        $validationRules = [
            "name"            => "required|max_length[100]|min_length[3]",
            "email"           => "required|valid_email|max_length[100]|min_length[6]",
            "bio"             => "permit_empty|string",
            "social_links"    => "permit_empty",
            "profile_picture" => [
                "rules" => "permit_empty|uploaded[profile_picture]|is_image[profile_picture]|max_size[profile_picture,2048]|mime_in[profile_picture,image/jpg,image/jpeg,image/png]",
                "label" => "Profile Picture"
            ],
        ];

        if (! $this->validate($validationRules)) {
            return $this->respondWithValidationError($this->validator->getErrors());
        }

        $data = $this->request->getPost();

        $email = $data['email'];
        if ($email !== $this->user['email']) {
            $existingUser = $this->userModel->where('email', $email)->where('id !=', $this->user['id'])->first();
            if ($existingUser) {
                return $this->respondWithError("This email is already registered by another user");
            }
        }

        if (isset($data['social_links']) && ! is_array(json_decode($data['social_links'], true))) {
            return $this->respondWithError("social_links must be an array (JSON)");
        }

        $profilePictureUrl = $this->user['profile_picture'];
        $profilePictureId  = $this->user['profile_picture_public_id'];

        $profilePicture = $this->request->getFile('profile_picture');

        if ($profilePicture && $profilePicture->isValid() && !$profilePicture->hasMoved()) {
            $uploadResult = $this->updateImage(
                $profilePicture,
                $this->user['profile_picture_public_id'],
                'byway/profile_pictures'
            );

            if ($uploadResult) {
                $profilePictureUrl = $uploadResult['url'];
                $profilePictureId  = $uploadResult['public_id'];
            } else {
                return $this->respondWithError("Failed to upload profile picture");
            }
        }

        $updateData = [
            'name'                      => $data['name'],
            'email'                     => $data['email'],
            'bio'                       => $data['bio'] ?? null,
            'social_links'              => isset($data['social_links']) ? json_decode($data['social_links'], true) : [],
            'profile_picture'           => $profilePictureUrl,
            'profile_picture_public_id' => $profilePictureId,
        ];

        // تعطيل التحقق في النموذج مؤقتاً لتجنب مشاكل is_unique
        $this->userModel->skipValidation(true);

        if (! $this->userModel->update($this->user['id'], $updateData)) {
            $errors = $this->userModel->errors();
            return $this->respondWithError("Failed to update profile: " . implode(', ', $errors));
        }

        // إعادة تفعيل التحقق
        $this->userModel->skipValidation(false);

        $updatedUser = $this->userModel->find($this->user['id']);

        $userData = [
            "user_id" => $updatedUser["id"],
            "name" => $updatedUser["name"],
            "email" => $updatedUser["email"],
            "role" => $updatedUser["role"],
            "is_verified" => $updatedUser["email_verified_at"] ? true : false,
            "profile_picture" => $updatedUser["profile_picture"],
            "bio" => $updatedUser["bio"],
            "social_links" => $updatedUser["social_links"],
        ];

        return $this->respondWithSuccess([
            "message" => "Profile updated successfully",
            "user"    => $userData
        ]);
    }

}
