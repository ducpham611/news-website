<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\Clients\ShowNewsController;
use App\Http\Controllers\Clients\CommentsController;
use App\Http\Controllers\Clients\ContactAndSearchController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\CommentsAdminController;
use App\Http\Controllers\Admin\FeedbackAdminController;
use App\Http\Controllers\Admin\LogsController;
use App\Http\Controllers\Admin\DashboardController;

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

//Clients route
Route::prefix('/')->group(function(){
    Route::get('/',[HomeController::class,'getHome'])->name('home');
    Route::get('/contact',[ContactAndSearchController::class,'getContact'])->name('contact');
    Route::post('/contact',[ContactAndSearchController::class,'postContact']);

    Route::get('/search-news',[ContactAndSearchController::class,'getSearch'])->name('search');
    Route::get('search-news-results',[ContactAndSearchController::class,'getSearchResults'])->name('search-results');

    Route::get('/login',[HomeController::class,'getLogin'])->name('login');
    Route::post('/login',[HomeController::class,'postLogin']);

    Route::get('/register',[HomeController::class,'getRegister']);
    Route::post('/register',[HomeController::class,'postRegister']);

    Route::get('/logout',[HomeController::class,'getLogout']);

    Route::get('/edit-account/{id}',[HomeController::class,'getEditAccount'])->name('edit-account');
    Route::post('/edit-account/{id}',[HomeController::class,'postEditAccount']);

    Route::get('/edit-account-password/{id}',[HomeController::class,'getEditAccountPassword'])->name('edit-account-password');
    Route::post('/edit-account-password/{id}',[HomeController::class,'postEditAccountPassword']);


    Route::get('/forget-password',[HomeController::class,'getForgetPassword'])->name('forget-password');
    Route::post('/forget-password',[HomeController::class,'postForgetPassword']);
    Route::get('/reset-password/{token}',[HomeController::class,'getResetPassword'])->name('reset-password');
    Route::post('/reset-password/{token}',[HomeController::class,'postResetPassword']);

    //Route for news category
    Route::get('/news/category/{id}',[ShowNewsController::class,'getNewsByCategories'])->name('news-by-categories');

    //Route for news detail
    Route::get('/news-detail/{id}',[ShowNewsController::class,'getNewsDetail'])->name('news-detail');

    //Route for comment
    Route::post('/news-detail/{id}',[CommentsController::class,'postComments'])->name('post-comments');

    Route::get('/single_page',function(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $current_time = date('D, d M Y H:i');
        return view('clients.single_page',compact('current_time'));
    });
});

//Admin routes
Route::get('/admin/login',[AdminController::class,'getAdminLogin'])->name('admin.login');
Route::post('/admin/login',[AdminController::class,'postAdminLogin']);
Route::middleware('auth.user')->prefix('/admin')->name('admin.')->group(function(){
    Route::get('/logout',[AdminController::class,'getAdminLogOut']);

    //Route for user-management
    Route::get('/user-management',[AdminController::class,'getUserManagement'])->name('user-management')->middleware('auth.admin');
    Route::get('/user-register',[AdminController::class,'getUserRegister'])->name('user-register')->middleware('auth.admin');
    Route::post('/user-register',[AdminController::class,'postUserRegister']);
    Route::get('/user-edit/{id}',[AdminController::class,'getUserEdit'])->name('user-edit')->middleware('auth.admin');
    Route::post('/user-edit/{id}',[AdminController::class,'postUserEdit']);
    Route::get('/user-delete/{id}',[AdminController::class,'deleteUser'])->name('user-delete')->middleware('auth.admin');
    Route::get('/user-search',[AdminController::class,'searchUser'])->name('user-search')->middleware('auth.admin');

    //Route for reader-management
    Route::get('/reader-management',[AdminController::class,'getReaderManagement'])->name('reader-management')->middleware('auth.admin');
    Route::get('/reader-delete/{id}',[AdminController::class,'getReaderDelete'])->name('reader-delete')->middleware('auth.admin');
    Route::get('/reader-search',[AdminController::class,'searchReader'])->name('reader-search')->middleware('auth.admin');

    //Route for news-management
    Route::get('/news-create',[NewsController::class,'getNewsCreate'])->name('news-create')->middleware('auth.journalist');
    Route::post('/news-create',[NewsController::class,'postNewsCreate'])->middleware('auth.journalist');
    Route::get('/news-management',[NewsController::class,'getNewsManagement'])->name('news-management');
    Route::get('/news-detail/{id}',[NewsController::class,'getNewsDetail'])->name('news-detail');
    Route::post('/news-detail/{id}',[NewsController::class,'postNewsDetail']);
    Route::get('/news-edit/{id}',[NewsController::class,'getNewsEdit'])->name('news-edit');
    Route::post('/news-edit/{id}',[NewsController::class,'postNewsEdit']);
    Route::get('/news-delete/{id}',[NewsController::class,'getNewsDelete'])->name('news-delete');
    Route::get('/news-search',[NewsController::class,'getNewsSearch'])->name('news-search');

    //Route for categories-management
    Route::get('/categories-management',[NewsController::class,'getCategoriesManagement'])->name('categories-management')->middleware('auth.editorial-director');
    Route::get('/categories-create',[NewsController::class,'getCategoriesCreate'])->name('categories-create')->middleware('auth.editorial-director');
    Route::post('/categories-create',[NewsController::class,'postCategoriesCreate'])->middleware('auth.editorial-director');
    Route::get('/categories-edit/{id}',[NewsController::class,'getCategoriesEdit'])->name('categories-edit')->middleware('auth.editorial-director');
    Route::post('/categories-edit/{id}',[NewsController::class,'postCategoriesEdit'])->middleware('auth.editorial-director');
    Route::get('/categories-delete/{id}',[NewsController::class,'getCategoriesDelete'])->name('categories-delete')->middleware('auth.editorial-director');
    Route::get('/categories-search',[NewsController::class,'getCategoriesSearch'])->name('categories-search')->middleware('auth.editorial-director');
    
    Route::get('/categories-assign-manage',[NewsController::class,'getCategoriesAssignManage'])->name('categories-assign-manage')->middleware('auth.editorial-director');
    Route::get('/categories-assign/{id}',[NewsController::class,'getCategoriesAssign'])->name('categories-assign')->middleware('auth.editorial-director');
    Route::post('/categories-assign/{id}',[NewsController::class,'postCategoriesAssign'])->name('post-categories-assign')->middleware('auth.editorial-director');

    
    //Route for comments-management
    Route::get('/comments-management',[CommentsAdminController::class,'getCommentsManagement'])->name('comments-management')->middleware('auth.admin');
    Route::get('/delete-comments/{id}',[CommentsAdminController::class,'getCommentsDelete'])->name('comments-delete')->middleware('auth.admin');
    Route::get('/comments-search',[CommentsAdminController::class,'getCommentsSearch'])->name('comments-search')->middleware('auth.admin');

    //Route for feedbacks-management
    Route::get('/feedbacks-management',[FeedbackAdminController::class,'getFeedbacksManagement'])->name('feedbacks-management')->middleware('auth.admin');
    Route::get('/feedbacks-delete/{id}',[FeedbackAdminController::class,'getFeedbacksDelete'])->name('feedbacks-delete')->middleware('auth.admin');
    Route::get('/feedbacks-search',[FeedbackAdminController::class,'getFeedbacksSearch'])->name('feedbacks-search')->middleware('auth.admin');

    //Route for logs-management
    Route::get('/logs-management',[LogsController::class,'getLogsManagement'])->name('logs-management');
    Route::get('/log-search',[LogsController::class,'getLogsSearch'])->name('logs-search');

    //Route for dashboard-management
    Route::get('/dashboard',[DashboardController::class,'getDashboard'])->name('dashboard');

    Route::get('/simple',function(){
        return view('admin.simple');
    });  
    Route::get('/general',function(){
        return view('admin.general');
    });
    
}); 
