<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);

//api
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->get('profile', 'AuthController::profile');
    $routes->get('logout', 'AuthController::logout');

    $routes->post('add-project', 'ProjectController::addProject');
    $routes->get('list-projects', 'ProjectController::listProjects');
    $routes->get('delete-project(:num)', 'ProjectController::deleteProject/$1');
});
