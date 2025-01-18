<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('auth/register', 'AuthController::register');
$routes->put('auth/update/(:num)', 'AuthController::updateUser/$1');
$routes->get('auth/find/(:num)', 'AuthController::getUser/$1');
$routes->post('auth/login', 'AuthController::login');
$routes->get('auth/users', 'AuthController::users');
$routes->delete('auth/delete/(:num)', 'AuthController::deleteOne/$1');
$routes->get('auth/generate/(:num)', 'AuthController::generate/$1');
$routes->post('auth/password', 'AuthController::password');


$routes->get('company', 'CompanyController::index');
$routes->post('company', 'CompanyController::create');
$routes->put('company/(:num)', 'CompanyController::upgrade/$1');
$routes->delete('company/(:num)', 'CompanyController::deleteOne/$1');
$routes->get('company/find/(:num)', 'CompanyController::find/$1');
$routes->get('company/generateqr/(:num)', 'CompanyController::generateqr/$1');


$routes->get('marcaje', 'HorasExtrasController::display');
$routes->post('marcaje', 'HorasExtrasController::create');
$routes->put('marcaje/(:num)', 'HorasExtrasController::upgrade/$1');
$routes->delete('marcaje/(:num)', 'HorasExtrasController::deleteOne/$1');
$routes->get('marcaje/find/(:num)', 'HorasExtrasController::find/$1');
$routes->get('marcaje/excel', 'HorasExtrasController::generateExcelReport');
$routes->get('marcaje/exceluser/(:num)', 'HorasExtrasController::generateExcelReportUser/$1');
$routes->get('marcaje/excelsupervisor/(:num)', 'HorasExtrasController::generateExcelReportSupervisor/$1');


$routes->get('send-email', 'EmailController::send_email');


$routes->get('territorio', 'TerritorioController::index');
$routes->post('territorio', 'TerritorioController::create');
$routes->put('territorio/(:num)', 'TerritorioController::upgrade/$1');
$routes->delete('territorio/(:num)', 'TerritorioController::deleteOne/$1');
$routes->get('territorio/find/(:num)', 'TerritorioController::find/$1');
