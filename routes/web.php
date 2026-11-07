<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// ==========================================
// TUGAS 3: PHP Lumen Service App
// ==========================================

// Endpoint Status Service: GET /
$router->get('/', 'UsersController@getServiceInfo');

// Route versi Lumen bawaan praktikum
$router->get('/version', function () use ($router) {
    return $router->app->version();
});

// Routing Praktikum 1 & 2
$router->get('/hello-lumen', function () {
    return "<h1>Lumen</h1><p>Hi good developer, thank for using Lumen</p>";
});

$router->get('/hello-lumen/{name}', function ($name) {
    return "<h1>Lumen</h1><p>Hi <b>" . $name . "</b>, thank for using Lumen</p>";
});

// Routing Middleware: GET /scores
$router->get('/scores', ['middleware' => 'login', function () {
    return "<h1>Selamat</h1><p>Nilai anda 100</p>";
}]);

// Routing Users Controller
$router->get('/users', 'UsersController@index');
$router->get('/users/{userId}', 'UsersController@show');

// ==========================================
// TUGAS 4: PHP Lumen Database & Tantangan
// ==========================================

$router->get('/categories', 'CategoriesController@index');
$router->get('/comments', 'CommentsController@index');
$router->get('/tags', 'TagsController@index');
$router->get('/students', 'StudentsController@index');

// ==========================================
// TUGAS 5: PHP Lumen Rest CRUD (Praktikum Post)
// ==========================================

// Read All & Create Post
//$router->get('/posts', 'PostsController@index');
//$router->post('/posts', 'PostsController@store');

// Read Detail, Update, Delete Post (Sesuai rute /post/{id} pada modul dan alias /posts/{id})
//$router->get('/post/{id}', 'PostsController@show');
//$router->put('/post/{id}', 'PostsController@update');
//$router->delete('/post/{id}', 'PostsController@destroy');


// Sub-resource Comments pada Post (Halaman 2 Modul)
$router->get('/post/{postId}/comments', 'PostsController@comments');
$router->post('/post/{postId}/comments', 'PostsController@commentStore');
$router->delete('/post/{postId}/comment/{commentId}', 'PostsController@commentDestroy');

// ==========================================
// TUGAS 5: Tantangan RESTful CRUD (Minimal 7 Field: Product)
// ==========================================

$router->get('/products', 'ProductsController@index');
$router->post('/products', 'ProductsController@store');
$router->get('/products/{id}', 'ProductsController@show');
$router->put('/products/{id}', 'ProductsController@update');
$router->delete('/products/{id}', 'ProductsController@destroy');

$router->get('/product/{id}', 'ProductsController@show');
$router->put('/product/{id}', 'ProductsController@update');
$router->delete('/product/{id}', 'ProductsController@destroy');

// ==========================================
// TUGAS 6: Accept Header pada function show, update, delete
// ==========================================
$router->get('/posts', 'PostsController@index');
$router->post('/posts', 'PostsController@store');

$router->get('/post/{postId}', 'PostsController@show');
$router->put('/post/{postId}', 'PostsController@update');
$router->delete('/post/{postId}', 'PostsController@destroy');