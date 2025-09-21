<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group("api", ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post("register", "AuthController::register");
    $routes->post("verify-account", "AuthController::verifyAccount");
    $routes->post("login", "AuthController::login");

    $routes->post("forgot-password", "AuthController::forgotPassword");
    $routes->post("reset-password", "AuthController::resetPassword");

    $routes->post("logout", "AuthController::logout", ["filter" => "authToken"]);
});


$routes->group(
    "api/learner",
    [
        'namespace' => 'App\Controllers\Api\Learner',
        'filter'    => 'authToken'
    ],
    function ($routes) {
        // Learner Routes
        $routes->get("profile", "LearnerProfileController::profile");
        $routes->post("update-profile", "LearnerProfileController::updateProfile");
    }
);


$routes->group(
    "api/instructor",
    [
        'namespace' => 'App\Controllers\Api\Instructor',
        'filter'    => 'authToken'
    ],
    function ($routes) {
        // Instructor Routes
        $routes->get("profile", "InstructorProfileController::profile");
        $routes->post("update-profile", "InstructorProfileController::updateProfile");
    }
);

$routes->group(
    "api/admin",
    [
        'namespace' => 'App\Controllers\Api\Admin',
        'filter'    => 'authToken'
    ],
    function ($routes) {
        // Instructor Routes
});