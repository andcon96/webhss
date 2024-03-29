<?php

use App\Http\Controllers\API\ApprovalRusakTruck;
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
route::get('apiapprovaltruck/{wonbr}/{rusaknbr}/{nopolnbr}/{status}/{gandengan}/{gandengancode}',[ApprovalRusakTruck::class,'receiveAPI']);
route::post('qxoutwo',[ApprovalRusakTruck::class,'qxoutstatus']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




