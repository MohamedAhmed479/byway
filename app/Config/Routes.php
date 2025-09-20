<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group("api", ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post("register", "AuthController::register");
    $routes->post("login", "AuthController::login");
});