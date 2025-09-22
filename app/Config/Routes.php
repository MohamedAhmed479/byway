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
        // $routes->post("update-password", "InstructorProfileController::updatePassword");

        // Course Management
        $routes->group("courses", function ($routes) {
            $routes->post("update/(:num)", "CourseManagementController::updateCourse/$1");
            $routes->get("", "CourseManagementController::getCourses");
            $routes->get("deleted", "CourseManagementController::getDeletedCourses");
            $routes->post("add-course", "CourseManagementController::addCourse");
            $routes->post("restore/(:num)", "CourseManagementController::restoreCourse/$1");
            $routes->delete("(:num)", "CourseManagementController::deleteCourse/$1");
            $routes->get("(:num)", "CourseManagementController::getCourseDetails/$1");
        });

        // Lessons Management
        $routes->group("lessons", function ($routes) {
            $routes->get("", "LessonsManagementController::getLessons");

            $routes->get("(:num)/(:num)", "LessonsManagementController::getLessonDetails/$1/$2");

            $routes->post("(:num)/add", "LessonsManagementController::addLesson/$1");

            $routes->post("(:num)/(:num)/update", "LessonsManagementController::updateLesson/$1/$2");

            $routes->delete("(:num)/(:num)", "LessonsManagementController::deleteLesson/$1/$2");
        });
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