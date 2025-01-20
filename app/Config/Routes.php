<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/services', 'Home::services');
$routes->get('/plants', 'Plants::index');

//register
$routes->get('login/register/index', 'Register::index');
$routes->get('register/proses', 'register::proses');
$routes->get('register_page.php', 'register::index');
// $routes->get('/trefle', 'plant::index');


//login
$routes->get('login/index', 'login::index');
$routes->get('login/proses', 'login::proses');
$routes->get('login_page.php', 'login::index');


$routes->get('login/logout', 'login::logout');
$routes->get('register/logout', 'register::logout');


$routes->get('user_page.php', 'user_page_controller::index');