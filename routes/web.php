<?php

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

Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('landing-page', function (){
    return view('landing');
})->name('landing');

Route::group(['middleware' => 'locale'], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::post('/my-phone/{request}/{radio}', 'HomeController@getPhone')->name('phone');
    Route::get('/logout', 'HomeController@index');
    Route::get('change-language/{language}', 'HomeController@changeLanguage')->name('user.change-language');
    Route::post('/header-search', 'HomeController@searchAjax');
    Route::get('/search', 'HomeController@search')->name('search');
    Route::post('/search', 'HomeController@searchPageAjax');

    Route::get('login/framgia', 'Auth\LoginController@redirectToProvider')->name('framgia.login');
    Route::get('login/framgia/callback', 'Auth\LoginController@handleProviderCallback');

    Route::group(['namespace' => 'User'], function () {
        Route::post('change-cover', 'UserController@changeCover')->name('change-cover');
        Route::get('getModalBook/{id}', 'BookController@getModalBook')->name('modal-book');
        Route::resource('books', 'BookController');
        Route::get('books/category/{slug}', 'BookController@getBookCategory')->name('book.category');
        Route::get('books/office/{slug}', 'BookController@getBookOffice')->name('book.office');
        Route::post('/books/{slug}', 'BookController@getDetailData');
        Route::post('/add-favorite/{id}', 'BookController@addFavorite')->name('add-favorite');
        Route::get('/books/{id}/statistic', 'BookController@statisticBook');

        Route::group(['middleware' => 'auth'], function () {
            Route::resource('/books/{slug}/review', 'ReviewBookController');
            Route::resource('/review/{id}/vote', 'VoteController');
            Route::post('/books/sharing/{id}', 'UserController@sharingBook')->name('user.sharing');
            Route::post('/books/remove-owner/{id}', 'UserController@removeOwner')->name('user.remove-owner');
            Route::post('/books/borrowing/{id}', 'UserController@borrowingBook');
            Route::post('/books/cancelBorrowing/{bookId}', 'UserController@cancelBorrowing');
            Route::get('/my-profile', 'UserController@myProfile')->name('my-profile');
            Route::post('/update-bio/{id}', 'UserController@updateBio')->name('update-bio');
            Route::post('/my-profile', 'UserController@myProfiles');
            Route::post('/my-profile/{id}', 'UserController@postMyProfile')->name('profile');
            Route::resource('my-request', 'MyRequestController')->only(['index', 'update']);
            Route::post('/my-profile/{request}/{id}', 'UserController@getBooks');
            Route::post('/my-profiles/{status}/{id}', 'UserController@postFollowProfile');
            Route::get('/users/{id}', 'UserController@getUser')->name('user');
            Route::post('/follow/{id}', 'UserController@follow');
            Route::post('/unfollow/{id}', 'UserController@unfollow');
            Route::post('/notifications/{limit}', 'NotificationController@getLimitNotifications');
            Route::get('/notifications', 'NotificationController@getAllNotifications')->name('notifications');
            Route::post('/notification-update', 'NotificationController@updateNotification');
            Route::get('/notifications/viewed', 'NotificationController@markRead')->name('markread');
            Route::post('books/returning/{id}', 'UserController@returnBook')->name('return-book');
            Route::post('/settings/{phone}/{display}', 'SettingController@postSaveSetting')->name('save-setting');
            Route::post('/setting/display', 'SettingController@postSetting')->name('settings');
            Route::post('/setting-phone/{request}/{radio}', 'SettingController@postPhoneSetting');
            Route::post('language/{language}', 'SettingController@postLanguage')->name('setting.language');
            Route::get('/get-book-title', 'BookController@getBookByTitle')->name('get-book-title');
        });
    });

    Route::group(['namespace' => 'Auth'], function () {
        Route::get('admin/login', 'LoginController@getLoginAdmin')->name('admin.getLoginAdmin');
        Route::post('admin/login', 'LoginController@postLoginAdmin')->name('admin.postLoginAdmin');

        Route::post('admin/logout', 'LoginController@logout')->name('admin.logout');
    });

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('listbook', 'BookController@ajaxShow')->name('book.show');
        Route::resource('books', 'BookController')->except(['create']);
        Route::resource('/category', 'CategoryController');
        Route::get('list-category', 'CategoryController@ajaxIndex')->name('category.ajax-index');
        Route::get('/post', 'HomeController@index');
        Route::get('/reputation', 'HomeController@index');
        Route::get('/tag', 'HomeController@index');
        Route::get('/', 'HomeController@adminIndex')->name('dashboard');
        Route::resource('/roles', 'RoleController');
        Route::resource('/offices', 'OfficeController');
        Route::get('list-offices', 'OfficeController@ajaxIndex')->name('office.ajax-index');
        Route::resource('/users', 'UserController');
        Route::resource('/tags', 'TagController');
        Route::get('/setting', 'SettingController@indexSetting')->name('setting');
        Route::post('/setting/text', 'SettingController@postEditText');
        Route::post('/setting/img', 'SettingController@postEditImg')->name('settingImg');
        Route::post('/setting/text/banner/', 'SettingController@postEditTextBanner');
        Route::post('/setting/app/', 'SettingController@postAddApp');
        Route::post('/setting/delete/app/', 'SettingController@postDeleteApp')->name('deleteApp');
    });
});
