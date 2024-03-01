<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//added by Dev Team
use App\Http\Controllers\api\v1\TranscribeController;
use App\Http\Controllers\api\v1\TestingToolPostController;  //Pending:  testing only

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//this is a new section to test the authentication and authorization features
Route::middleware(['auth:sanctum'])->group(function() {
    //Fact: middlewhere routes are executed in the order they are listed on the array.

});//end of transcriber api calls section 


//************Pending -->>> Move the solution to authentication
Route::prefix('v1')->group(function () { 
    Route::controller(TranscribeController::class)->group(function () {
       Route::post('/mttext', [TranscribeController::class, 'mttext'] );
    });//end of the transcribe controller route 
    //Route::post('mttTest', [TestingToolPostController::class,'readPost']);  //Pending Testing Only. 
});//end of the prefix for v1


