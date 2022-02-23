<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use Illuminate\Support\Str;

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
    return $router->app->version();
});
$router->get('/key', function() {
    return str::random(40);
});


//Generate Aplication
$router->post('/login','PenggunaController@login');
$router->post('/register','PenggunaController@register');

$router->get('pengguna/{id}', 'PenggunaController@getdetailData');
$router->get('pengguna', 'PenggunaController@getdAllData');
$router->post('pengguna/{id}', 'PenggunaController@updated');
$router->post('pengguna', 'PenggunaController@insert');
$router->delete('pengguna/{id}', 'PenggunaController@delete');

$router->get('daftarpoid', 'DaftarpoController@getAllbyId');
$router->get('daftarpoall', 'DaftarpoController@getdAllData');
$router->get('daftarpo/{id}', 'DaftarpoController@getdetailData');
$router->post('daftarpo', 'DaftarpoController@insert');
$router->post('daftarpo/{id}', 'DaftarpoController@updated');
$router->delete('daftarpo/{id}', 'DaftarpoController@delete');