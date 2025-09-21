<?php

namespace App\Controllers\Api\Instructor;

use App\Controllers\Api\BaseProfileController;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class InstructorProfileController extends BaseProfileController
{
    public function isAuthorize(): bool
    {
        $this->user = $this->request->user;
        if($this->user && $this->isInstructor($this->user)) {
            return true;
        }

        return false;
    }
}
