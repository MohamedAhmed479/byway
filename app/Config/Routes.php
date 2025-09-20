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
});