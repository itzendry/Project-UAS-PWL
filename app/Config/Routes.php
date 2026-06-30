<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');


// ===================== AUTH =====================
$routes->get('/login', 'Auth::login');
$routes->post('/login/proses', 'Auth::prosesLogin');

$routes->get('/register', 'Auth::register');
$routes->post('/register/proses', 'Auth::prosesRegister');

$routes->get('/logout', 'Auth::logout');


// ===================== KULINER (LOGIN REQUIRED) =====================
$routes->group('kuliner', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Kuliner::index');

    $routes->get('my-favorites', 'Kuliner::myFavorites');

    $routes->get('create', 'Kuliner::create');
    $routes->post('save', 'Kuliner::save');

    $routes->get('detail/(:num)', 'Kuliner::detail/$1');

    $routes->get('edit/(:num)', 'Kuliner::edit/$1');
    $routes->post('update/(:num)', 'Kuliner::update/$1');

    $routes->get('delete/(:num)', 'Kuliner::delete/$1');

    $routes->get('favorite/(:num)', 'Kuliner::favorite/$1');

    // AJAX GEOCODING (NOMINATIM)
    $routes->post('get-coordinate', 'Kuliner::getCoordinate');
});


// ===================== ADMIN =====================
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {

    $routes->get('dashboard', 'Admin::dashboard');

    $routes->get('kuliner', 'Admin::kuliner');
    $routes->get('approve/(:num)', 'Admin::approve/$1');
    $routes->get('reject/(:num)', 'Admin::reject/$1');

    $routes->get('reviews', 'Admin::reviews');
    $routes->get('review/delete/(:num)', 'Admin::deleteReview/$1');

    $routes->get('categories', 'Category::index');
    $routes->post('categories/save', 'Category::save');
});


// ===================== API (WEB SERVICE SERVER) =====================
$routes->group('api', static function ($routes) {

    // list + radius kuliner
    $routes->get('kuliner', 'Api\KulinerApi::index');

    // detail kuliner
    $routes->get('kuliner/(:num)', 'Api\KulinerApi::detail/$1');
});


// ===================== WEB SERVICE CLIENT =====================
$routes->get('client-kuliner', 'ClientKuliner::index');