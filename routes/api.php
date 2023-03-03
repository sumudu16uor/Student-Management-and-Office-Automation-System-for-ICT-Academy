<?php

use App\Helpers\RouteHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1.0')
    ->group(function (){
        RouteHelper::includeRouteFiles(__DIR__.'/api/v1.0');
//      require_once __DIR__.'/api/v1.0/branches.php';
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
