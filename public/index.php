<?php

date_default_timezone_set("Africa/Cairo");

require_once __DIR__ . '/../app/config/session.php';
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/config/encryption.php';
require_once __DIR__ . '/../app/config/router.php';

Session::start();
Encryption::init();

$router = new Router();

$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');

$router->get('/auth/login', 'AuthController@loginForm');
$router->post('/auth/login', 'AuthController@login');
$router->get('/auth/register', 'AuthController@registerForm');
$router->post('/auth/register', 'AuthController@register');
$router->get('/auth/preferences', 'AuthController@preferencesForm');
$router->post('/auth/preferences', 'AuthController@savePreferences');
$router->get('/auth/logout', 'AuthController@logout');

// AJAX Routes
$router->post('/ajax/check-username', 'AjaxController@checkUsername');

$router->get('/profile', 'ProfileController@index');
$router->get('/profile/{id}', 'ProfileController@index');
$router->get('/profile/apply-role', 'ProfileController@applyRoleForm');
$router->post('/profile/apply-role', 'ProfileController@applyRole');
$router->post('/profile/update', 'ProfileController@update');
$router->post('/profile/updatePhoto', 'ProfileController@updatePhoto');

$router->get('/marketplace', 'MarketplaceController@index');
$router->get('/marketplace/show/{id}', 'MarketplaceController@show');

$router->get('/item/closet', 'ItemController@closet');
$router->get('/item/create', 'ItemController@createForm');
$router->post('/item/create', 'ItemController@create');
$router->get('/item/edit/{id}', 'ItemController@editForm');
$router->post('/item/edit/{id}', 'ItemController@edit');
$router->post('/item/assess/{id}', 'ItemController@assessItem');
$router->post('/item/closet/add', 'ItemController@addToCloset');
$router->post('/item/closet/remove', 'ItemController@removeFromCloset');

$router->get('/order', 'OrderController@index');
$router->get('/order/show/{id}', 'OrderController@show');
$router->post('/order/buy', 'OrderController@buy');
$router->post('/order/confirm', 'OrderController@confirmDelivery');

$router->get('/swap', 'SwapController@index');
$router->get('/swap/show/{id}', 'SwapController@show');
$router->post('/swap/request', 'SwapController@request');
$router->post('/swap/accept', 'SwapController@accept');
$router->post('/swap/reject', 'SwapController@reject');

$router->get('/community', 'CommunityController@index');
$router->get('/community/create', 'CommunityController@createForm');
$router->post('/community/create', 'CommunityController@create');
$router->post('/community/mentor', 'CommunityController@requestMentor');
$router->post('/community/comment', 'CommunityController@addComment');

$router->get('/upcycle', 'UpcycleController@index');
$router->get('/upcycle/track', 'UpcycleController@track');
$router->get('/upcycle/mentor', 'UpcycleController@mentor');
$router->post('/upcycle/log', 'UpcycleController@logTransformation');

$router->get('/admin', 'AdminController@index');
$router->post('/admin/approve-upcycler', 'AdminController@approveUpcycler');
$router->post('/admin/reject-upcycler', 'AdminController@rejectUpcycler');
$router->post('/admin/make-admin', 'AdminController@makeAdmin');
$router->post('/admin/revoke-admin', 'AdminController@revokeAdmin');
$router->get('/admin/reports', 'AdminController@reports');

$router->get('/moderator', 'ModeratorController@index');
$router->post('/moderator/resolve', 'ModeratorController@resolveReport');
$router->post('/report/create', 'ModeratorController@createReport');

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$router->dispatch($method, $uri);


