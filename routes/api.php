<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScrabeController;
use App\Http\Controllers\AuthController;
use App\Models\Article;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface;
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
//Route::get('/logrequests', function (ServerRequestInterface $request) {
        //
 //   });

Route::post('/register',[AuthController::class ,'register']);
Route::post('/login',[AuthController::class ,'login']);
Route::get('/allScrabedWebsites',[ScrabeController::class ,'index']);


//Protected routes
Route::group(['middleware'=> ['auth:sanctum']],function()
{
    
    Route::post('/ScrabeByLink',[ScrabeController::class,'ScrabeWebsiteByLink']);
    Route::get('/scarbeAwebsite/{id}',[ScrabeController::class,'re_scrabe_byId']);
    Route::get('/allArticlesById/{id}',[ScrabeController::class,'show_scarpedArticle_byWebsiteId']);
    Route::get('/logRequests',[ScrabeController::class,'logRequests']);
    Route::get('/allhistory',[ScrabeController::class,'allhistory']);
    Route::post('/logout',[AuthController::class ,'logout']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
