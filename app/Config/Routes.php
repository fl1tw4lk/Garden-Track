<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/services', 'Home::services');
$routes->get('/plants', 'Plants::index');

//register
$routes->get('register', 'Register::index');
$routes->get('register/proses', 'Register::proses');
$routes->get('register_page.php', 'Register::index');
// register biasa
$routes->post('register/auth', 'Register::regis_auth');
// $routes->get('/trefle', 'plant::index');

//login
$routes->get('login', 'Login::index');
$routes->get('login/proses', 'Login::proses');
$routes->post('login/auth', 'Login::auth');


$routes->get('login/logout', 'Login::logout');
$routes->get('register/logout', 'Register::logout');

$routes->get('user_page.php', 'User_page_controller::index');