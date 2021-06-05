<?php

use Illuminate\Http\Request;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */
Route::post('login','Auth\AuthController@login');
Route::post('registrar','Auth\AuthController@registrar');


Route::post('nivel/create', 'Api\NivelController@createNivel');


Route::group([
    'namespace' => 'Api',
    'middleware' => 'jwt.auth'
], function () {

    //vacinados
	Route::post('vacinados/create', 'VacinadosController@store');
    Route::post('vacinados/update/{id}', 'VacinadosController@update');
    Route::get('vacinados/destroy/{id}', 'VacinadosController@destroy');
    Route::get('vacinados/listar', 'VacinadosController@index');
    //




});
