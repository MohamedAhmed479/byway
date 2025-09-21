<?php

namespace App\Controllers\Api\Learner;

use App\Controllers\Api\BaseProfileController;
use App\Controllers\BaseController;
use App\Models\User;
use App\Traits\ApiResponses;
use App\Traits\Authorizable;
use App\Traits\CloudinaryTrait;
use CodeIgniter\HTTP\ResponseInterface;

class LearnerProfileController extends BaseProfileController
{
    public function isAuthorize(): bool
    {
        $this->user = $this->request->user;
        if($this->user && $this->isLearner($this->user)) {
            return true;
        }

        return false;
    }
}
