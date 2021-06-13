<?php

use Illuminate\Http\Request;



Route::post('login','Auth\AuthController@login');
Route::post('registrar','Auth\AuthController@registrar');
Route::get('verificar', 'Auth\AuthController@verificaUsuario');
Route::get('user/show', "Auth\AuthController@getUser");
Route::post('user/update', "Auth\AuthController@userUpdate");


Route::post('nivel/create', 'Api\NivelController@createNivel');



Route::group([
    'namespace' => 'Api',
    'middleware' => 'jwt.auth'
], function () {
    
    //vacinados
	Route::post('vacinados/create', 'VacinadosController@store');
    Route::post('vacinados/update/{id}', 'VacinadosController@update');
    Route::get('vacinados/destroy/{id}', 'VacinadosController@destroy');
    Route::get('vacinados/show/{id}', 'VacinadosController@show');
    Route::get('vacinados/listar', 'VacinadosController@index');
    //
    
    
    //statisticas 
    Route::get('statisticas/small-list', 'StatisticController@smallTasks');
    Route::get('statisticas/chart-vacinados', 'StatisticController@getMouthVacinados');
    Route::get('statisticas/ultimos', 'StatisticController@getUltimosVacinados');
    //
    
    //log
    Route::get('log/listar', 'LogController@index');
    //

    //documentos
    Route::get('documento/create', 'DocumentoController@store');
    Route::get('documento/listar', 'DocumentoController@meusDocs');
    //
    

});
