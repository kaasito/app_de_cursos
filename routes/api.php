<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\CursosController;
use App\Http\Controllers\CursosAdquiridosController;
use App\Http\Controllers\VideosController;


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

Route::prefix('usuarios')->group(function(){
                                 Route::put('/crear', [UsuariosController::class, 'crear']);
                                 Route::post('/modificar/{id}', [UsuariosController::class, 'modificar']);
                                 Route::get('/vervideo', [VideosController::class, 'vervideo']);
                                 });

Route::prefix('cursos')->group(function(){
                               Route::put('/crear', [CursosController::class, 'crear']);
                               Route::get('/listar', [CursosController::class, 'listar']);
                               Route::get('/vervideo', [CursosController::class, 'vervideo']);
                               });

Route::prefix('adquirir')->group(function(){
                               Route::put('/solicitar', [CursosAdquiridosController::class, 'solicitar']);
                               Route::get('/miscursos', [CursosAdquiridosController::class, 'miscursos']);
                               });


Route::prefix('video')->group(function(){
                               Route::put('/crear', [VideosController::class, 'crear']);
                               });
