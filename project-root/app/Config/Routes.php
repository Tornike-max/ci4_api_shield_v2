<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);

//api
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    $routes->get('invalid-access', 'AuthController::invalidAccess');

    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    $routes->get('profile', 'AuthController::profile', ['filter' => 'apiAuth']);
    $routes->get('logout', 'AuthController::logout', ['filter' => 'apiAuth']);

    $routes->post('add-project', 'ProjectController::addProject', ['filter' => 'apiAuth']);
    $routes->get('list-projects', 'ProjectController::listProjects', ['filter' => 'apiAuth']);
    $routes->get('delete-project/(:num)', 'ProjectController::deleteProject/$1', ['filter' => 'apiAuth']);
});
