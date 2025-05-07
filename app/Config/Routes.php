<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Halaman login
$routes->get('/', 'LoginController::index', ['filter' => 'noauth']);
$routes->get('/login', 'LoginController::index', ['filter' => 'noauth']);
$routes->post('/LoginController/setSession', 'LoginController::setSession', ['filter' => 'noauth']); // bisa diganti ke /login/setSession kalau mau rapi
$routes->get('register', 'LoginController::register', ['filter' => 'noauth']);

// Area yang butuh login
$routes->get('/dashboard', 'Home::index', ['filter' => 'auth']);
$routes->post('/logout', 'LoginController::logout', ['filter' => 'auth']);

$routes->get('/search', 'SearchController::index', ['filter' => 'auth']);
$routes->get('/produk', 'ProdukController::index', ['filter' => 'auth']);
$routes->get('/edit', 'ProdukController::edit', ['filter' => 'auth']);

$routes->get('/delivery', 'DeliveryController::index', ['filter' => 'auth']);
$routes->get('/add', 'DeliveryController::add', ['filter' => 'auth']);
$routes->get('/deliveryapp', 'DeliveryApp::index', ['filter' => 'auth']);
