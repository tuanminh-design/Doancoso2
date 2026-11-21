<?php
/**
 * Web Routes Definition
 */

use App\Router;

$router = new Router();

// ---------------------------------------------------------
// Customer & Public Routes
// ---------------------------------------------------------

// Homepage
$router->get('/', 'HomeController@index');

// Products
$router->get('/products', 'ProductController@index');
$router->get('/product/{slug}', 'ProductController@detail');

// Shopping Cart
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->post('/cart/clear', 'CartController@clear');

// Checkout
$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout/process', 'CheckoutController@process');
$router->get('/checkout/success', 'CheckoutController@success');

// Authentication
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Customer Account (Protected)
$router->get('/account', 'AccountController@profile');
$router->post('/account/update', 'AccountController@updateProfile');
$router->post('/account/password', 'AccountController@changePassword');
$router->get('/account/orders', 'AccountController@orders');
$router->get('/account/orders/{code}', 'AccountController@orderDetail');

// Technology News
$router->get('/news', 'NewsController@index');
$router->get('/news/{slug}', 'NewsController@detail');

// Static / Policy Pages & Contact
$router->get('/contact', 'PageController@contact');
$router->post('/contact/submit', 'PageController@submitContact');
$router->get('/policy/{type}', 'PageController@policy');

// ---------------------------------------------------------
// Admin Dashboard Routes (Protected via AdminMiddleware)
// ---------------------------------------------------------
$router->get('/admin', 'Admin\DashboardController@index');

// Admin Products
$router->get('/admin/products', 'Admin\ProductController@index');
$router->get('/admin/products/create', 'Admin\ProductController@create');
$router->post('/admin/products/store', 'Admin\ProductController@store');
$router->get('/admin/products/edit/{id}', 'Admin\ProductController@edit');
$router->post('/admin/products/update/{id}', 'Admin\ProductController@update');
$router->post('/admin/products/delete/{id}', 'Admin\ProductController@delete');

// Admin Categories
$router->get('/admin/categories', 'Admin\CategoryController@index');
$router->post('/admin/categories/store', 'Admin\CategoryController@store');
$router->post('/admin/categories/update/{id}', 'Admin\CategoryController@update');
$router->post('/admin/categories/delete/{id}', 'Admin\CategoryController@delete');

// Admin Brands
$router->get('/admin/brands', 'Admin\BrandController@index');
$router->post('/admin/brands/store', 'Admin\BrandController@store');
$router->post('/admin/brands/update/{id}', 'Admin\BrandController@update');
$router->post('/admin/brands/delete/{id}', 'Admin\BrandController@delete');

// Admin Orders
$router->get('/admin/orders', 'Admin\OrderController@index');
$router->get('/admin/orders/{id}', 'Admin\OrderController@detail');
$router->post('/admin/orders/status/{id}', 'Admin\OrderController@updateStatus');
$router->post('/admin/orders/payment/{id}', 'Admin\OrderController@updatePayment');
$router->post('/admin/orders/cancel/{id}', 'Admin\OrderController@cancel');

// Admin Users
$router->get('/admin/users', 'Admin\UserController@index');
$router->post('/admin/users/status/{id}', 'Admin\UserController@updateStatus');
$router->post('/admin/users/role/{id}', 'Admin\UserController@updateRole');

// Admin News
$router->get('/admin/news', 'Admin\NewsController@index');
$router->get('/admin/news/create', 'Admin\NewsController@create');
$router->post('/admin/news/store', 'Admin\NewsController@store');
$router->get('/admin/news/edit/{id}', 'Admin\NewsController@edit');
$router->post('/admin/news/update/{id}', 'Admin\NewsController@update');
$router->post('/admin/news/delete/{id}', 'Admin\NewsController@delete');

// Admin Settings
$router->get('/admin/settings', 'Admin\SettingController@index');
$router->post('/admin/settings/update', 'Admin\SettingController@update');

return $router;
