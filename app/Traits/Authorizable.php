<?php

namespace App\Traits;

Trait Authorizable
{
    public function isAdmin(array $user): bool
    {
        return $user["role"] === "admin" ? true : false;
    }

    public function isInstructor(array $user): bool
    {
        return $user["role"] === "instructor" ? true : false;
    }

    public function isLearner(array $user): bool
    {
        return $user["role"] === "learner" ? true : false;
    }
}