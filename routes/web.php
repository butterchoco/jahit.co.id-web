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

Route::get('/', 'PageController@homepage')->name('homepage');
Route::get('/about', 'PageController@aboutpage')->name('aboutpage');
Route::get('/user/login', 'PageController@userLoginPage')->name('userLoginPage');
Route::get('/user/register', 'PageController@userRegisterPage')->name('userRegisterPage');
Route::get('/user/choice', 'PageController@choicePage')->name('choicePage');
Route::get('/user/customer/register', 'PageController@userCustomerRegisterPage')->name('userCustomerRegisterPage');
Route::get('/user/project/123', 'PageController@userProjectDetailPage')->name('userProjectDetailPage');
Route::get('/user/transaction/123', 'PageController@userTransactionDetailPage')->name('userTransactionDetailPage');
Route::get('/notfound', 'PageController@notfound')->name('notfound');
Route::get('/warning/{type}', 'PageController@warning')->name('warning');
Route::get('/admin/user-verification', 'PageController@adminUserVerification')->name('adminUserVerification');

Auth::routes();

Route::group(['prefix' => 'register', 'as' => 'register.'], function () {
    Route::get('/choice', 'Auth\RegisterController@registerChoicePage')->name('choice.page');
    Route::get('/partner', 'Auth\RegisterController@registerPartnerPage')->name('partner.page');
    Route::get('/customer', 'Auth\RegisterController@registerCustomerPage')->name('customer.page');
    Route::get('/customer/project', 'Auth\RegisterController@registerProjectPage')->name('customer.project.page');
    Route::post('/choice', 'Auth\RegisterController@registerChoiceSubmit')->name('choice.submit');
    Route::post('/partner', 'Auth\RegisterController@registerPartnerSubmit')->name('partner.submit');
    Route::post('/customer', 'Auth\RegisterController@registerCustomerSubmit')->name('customer.submit');
    Route::post('/customer/project', 'Auth\RegisterController@registerProjectSubmit')->name('customer.project.submit');
});

Route::group(['prefix' => 'home', 'as' => 'home'], function () {
    Route::get('', 'HomeController@index')->name('');

    Route::group(['prefix' => 'project', 'as' => '.project'], function () {
        Route::get('/{projectId}', 'ProjectController@index')->name('.detail');
        Route::post('/add', 'ProjectController@store')->name('.add');
        Route::post('/edit', 'ProjectController@update')->name('.edit');
        Route::post('/start/{projectId}', 'ProjectController@startProject')->name('.start');
        Route::post('/finish/{projectId}', 'ProjectController@finishProject')->name('.finish');
        Route::post('/send/{projectId}', 'ProjectController@sendProject')->name('.send');
    });

    Route::group(['prefix' => 'sample', 'as' => '.sample'], function () {
        Route::post('/start/{sampleId}', 'ProjectController@startSample')->name('.start');
        Route::post('/finish/{sampleId}', 'ProjectController@finishSample')->name('.finish');
        Route::post('/send/{sampleId}', 'ProjectController@sendSample')->name('.send');
    });

    Route::group(['prefix' => 'administrator', 'as' => '.administrator'], function () {
        Route::group(['prefix' => 'verification', 'as' => '.verification'], function () {
            Route::post('/payment', 'AdministratorController@paymentVerification')->name('.payment.submit');
            Route::post('/materialRequest', 'AdministratorController@materialRequestVerification')->name('.material.request');
            Route::post('/user/activate', 'AdministratorController@activateUser')->name('.user.activate');
            Route::post('/user/deactivate', 'AdministratorController@deactivateUser')->name('.user.deactivate');
            Route::post('/project/pay', 'AdministratorController@payProject')->name('.project.pay');
        });
    });

    Route::group(['prefix' => 'inbox', 'as' => '.inbox'], function () {
        Route::get('', 'InboxController@userInbox')->name('');
        Route::post('/chatAdmin/{inboxId}', 'InboxController@chatAdmin')->name('.chatAdmin');
        Route::post('/replyAdmin/{inboxId}', 'InboxController@replyAdmin')->name('.replyAdmin');
        
        Route::group(['prefix' => 'nego', 'as' => '.nego'], function () {
            Route::post('/offer', 'InboxController@offerNego')->name('.offer');
            Route::post('/reject', 'InboxController@rejectNego')->name('.reject');
            Route::post('/accept', 'InboxController@acceptNego')->name('.accept');
        });
        Route::group(['prefix' => 'sample', 'as' => '.sample'], function () {
            Route::post('/request', 'InboxController@requestSample')->name('.request');
            Route::post('/deal', 'InboxController@dealSample')->name('.deal');
        });

        Route::post('/review', 'InboxController@reviewProject')->name('.review');
    });

    Route::group(['prefix' => 'transaction', 'as' => '.transaction'], function () {
        Route::get('', 'TransactionController@userTransaction')->name('');
        Route::get('/download/mou/{mouId}', 'TransactionController@downloadMou')->name('.download.mou');
        Route::get('/download/invoice/{invoiceId}', 'TransactionController@downloadInvoice')->name('.download.invoice');
        Route::get('/requestMaterial', 'TransactionController@requestMaterialPage')->name('.material.request.page');
        Route::post('/uploadPaymentSlip', 'TransactionController@uploadPaymentSlip')->name('.slip.submit');
        Route::post('/requestMaterial', 'TransactionController@requestMaterial')->name('.material.request');
    });

    Route::group(['prefix' => 'material', 'as' => '.material'], function () {
        Route::get('', 'AdministratorController@materialRequestVerificationPage')->name('');
    });
});
