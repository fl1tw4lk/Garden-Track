<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/services', 'Home::services');

//tanaman
$routes->get('plants.php', 'Plants::index');
$routes->get('/plants', 'Plants::index');
$routes->get('plants/search', 'Plants::search');

//register google
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
$routes->get('logout', 'Login::logout');

//user page
$routes->get('user_page', 'User_page_controller::index');