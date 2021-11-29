<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/', function(){
   return redirect('/login');
});
Route::group(['middleware' => ['auth', 'confirmedUser']], function() {

    Route::get('/dashboard', [App\Http\Controllers\ResearchController::class, 'show_dashboard']);
    Route::get('/media/{id}/{type}/{file_id}/{filename}', [App\Http\Controllers\ResearchController::class, 'preview_file']);
    Route::post('/search/files', [App\Http\Controllers\ResearchController::class, 'search_files']);
    Route::post('/download/files', [App\Http\Controllers\ResearchController::class, 'download_files']);
    Route::get('/download/archived_files/{path}', [App\Http\Controllers\ResearchController::class, 'download_archived_files']);
});

Route::group(['middleware' => ['auth', 'admin']], function() {
    Route::get('/accounts/{status}', [App\Http\Controllers\AdminController::class, 'show_accounts']);
    Route::get('/rdo-registration', [App\Http\Controllers\AdminController::class, 'show_rdo_registration']);
    Route::post('/user/delete', [App\Http\Controllers\AdminController::class, 'delete_user']);
    Route::post('/user/disable', [App\Http\Controllers\AdminController::class, 'disable_user']);
    Route::post('/user/accept', [App\Http\Controllers\AdminController::class, 'accept_user']);
    Route::post('/add/rdo', [App\Http\Controllers\AdminController::class, 'add_rdo']);
});


Route::group(['middleware' => ['auth', 'researchAccess']], function() {
    Route::post('/update/published', [App\Http\Controllers\ResearchController::class, 'update_published']);
    Route::post('/update/completed', [App\Http\Controllers\ResearchController::class, 'update_completed']);
    Route::post('/update/presented', [App\Http\Controllers\ResearchController::class, 'update_presented']);
    Route::post('/update/ongoing', [App\Http\Controllers\ResearchController::class, 'update_ongoing']);
    Route::post('/update/fpes', [App\Http\Controllers\ResearchController::class, 'update_fpes']);

    Route::post('/update/{type}/status', [App\Http\Controllers\ResearchController::class, 'update_research_status']);
    

    Route::post('/delete/published', [App\Http\Controllers\ResearchController::class, 'delete_published']);
    Route::post('/delete/completed', [App\Http\Controllers\ResearchController::class, 'delete_completed']);
    Route::post('/delete/presented', [App\Http\Controllers\ResearchController::class, 'delete_presented']);
    Route::post('/delete/ongoing', [App\Http\Controllers\ResearchController::class, 'delete_ongoing']);
    Route::post('/delete/fpes', [App\Http\Controllers\ResearchController::class, 'delete_fpes']);


    Route::post('/save/published', [App\Http\Controllers\ResearchController::class, 'save_published']);
    Route::post('/save/presented', [App\Http\Controllers\ResearchController::class, 'save_presented']);
    Route::post('/save/completed', [App\Http\Controllers\ResearchController::class, 'save_completed']);
    Route::post('/save/ongoing', [App\Http\Controllers\ResearchController::class, 'save_ongoing']);
    Route::post('/save/fpes', [App\Http\Controllers\ResearchController::class, 'save_fpes']);

    Route::get('/upload', [App\Http\Controllers\ResearchController::class, 'show_upload_research']);
    Route::get('/files', [App\Http\Controllers\ResearchController::class, 'show_main_folders']);
    // Route::get('/published', [App\Http\Controllers\ResearchController::class, 'show_published_research']);
    // Route::get('/completed', [App\Http\Controllers\ResearchController::class, 'show_completed_research']);
    // Route::get('/presented', [App\Http\Controllers\ResearchController::class, 'show_presented_research']);
    // Route::get('/ongoing', [App\Http\Controllers\ResearchController::class, 'show_ongoing_research']);
    // Route::get('/fpes', [App\Http\Controllers\ResearchController::class, 'show_fpes_research']);
    Route::get('/{type}', [App\Http\Controllers\ResearchController::class, 'show_research']);

    Route::get('/pending/{type}', [App\Http\Controllers\ResearchController::class, 'show_pending_research']);
    Route::get('/returned/{type}', [App\Http\Controllers\ResearchController::class, 'show_returned_research']);
    
    
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Route::get('/', function(){
//     redirect('/login');
// });
Route::get('/register/success', function(){
    return redirect('/login')->with(['data'=>'success']);
});

