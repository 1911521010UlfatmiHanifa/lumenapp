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

$router->get('/', function () use ($router) {
    return ["Hello Hai..!!!"];
});

$router->post('/register', 'UserController@register');
$router->post('/login','AuthController@login');

$router->group(['middleware' => 'auth'], function() use ($router){
    $router->get('api/kategori', function () use ($router) {
        $listKategori = new stdClass();
        $results = app('db')->select("SELECT * FROM kategoris");
        $listKategori->kategori = $results;
        return response()->json($listKategori);
    });

    $router->get('api/user/{id}', 'UserController@show');

    $router->get('api/barang/{id_kategori}', function ($id_kategori) use ($router) {
        $listBarang = new stdClass();
        $results = DB::select("SELECT * FROM barangs where id_kategori=$id_kategori");
        $listBarang->barang = $results;
        return response()->json($listBarang);
    });

    $router->post('api/logout', 'AuthController@logout');
});
