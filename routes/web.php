<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

Route::view('/', 'landing');
Route::view('/panduan', 'panduan')->name('panduan');

Auth::routes(['verify' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/change-password', 'HomeController@changePassword')->name('change-password');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
    Route::get('/home/recap/{tahun}', ['as' => 'home.recap', 'uses' => 'HomeController@recap']);

    // Periode
    Route::group(['prefix' => 'periode', 'as' => 'period.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'PeriodController@index']);
        Route::get('/show', ['as' => 'show', 'uses' => 'PeriodController@show']);
        Route::post('/store', ['as' => 'store', 'uses' => 'PeriodController@store']);
        Route::put('/update', ['as' => 'update', 'uses' => 'PeriodController@update']);
    });
    
    // Proposal
    Route::group(['prefix' => 'usulan', 'as' => 'usulan.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'ProposalController@index']);
    
        Route::get('/review/{id}', ['as' => 'review', 'uses' => 'ProposalController@review']);
        Route::post('/review', ['as' => 'review.store', 'uses' => 'ProposalController@reviewStore']);
        Route::post('/review-acc', ['as' => 'review.acc', 'uses' => 'ProposalController@reviewAcc']);
    
        Route::get('/show/{id}', ['as' => 'show', 'uses' => 'ProposalController@show']);
        Route::put('/update', ['as' => 'update', 'uses' => 'ProposalController@update']);
        Route::put('/nilai', ['as' => 'nilai', 'uses' => 'ProposalController@nilai']);
        Route::get('/print/{tahun}', ['as' => 'print', 'uses' => 'ProposalController@print']);
        Route::delete('/', ['as' => 'delete', 'uses' => 'ProposalController@destroy']);
        Route::get('/download', ['as' => 'download', 'uses' => 'ProposalController@download']);
        Route::get('/download/form', ['as' => 'download.form', 'uses' => 'ProposalController@downloadForm']);
        Route::get('/download/berita', ['as' => 'download.berita', 'uses' => 'ProposalController@downloadBerita']);
    });

    // User
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'UserController@index']);
        Route::get('/create/{role}', ['as' => 'create', 'uses' => 'UserController@create']);
        Route::get('/show/{role}/{id}', ['as' => 'show', 'uses' => 'UserController@show']);
        Route::post('/store/{role}', ['as' => 'store', 'uses' => 'UserController@store']);
        Route::put('/update/{role}', ['as' => 'update', 'uses' => 'UserController@update']);
        Route::post('/import', ['as' => 'import', 'uses' => 'UserController@import']);
        Route::get('/sim/{id}', ['as' => 'getsim', 'uses' => 'UserController@showSim']);
        Route::put('/sim', ['as' => 'sim', 'uses' => 'UserController@updateSim']);
    });

    // Major
    Route::group(['prefix' => 'major', 'as' => 'major.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'MajorController@index']);
        Route::post('/store', ['as' => 'store', 'uses' => 'MajorController@store']);
        Route::put('/update', ['as' => 'update', 'uses' => 'MajorController@update']);
        Route::put('/toggle', ['as' => 'toggle', 'uses' => 'MajorController@toggle']);
    });

    // Ref Skema
    Route::group(['prefix' => 'ref-skema', 'as' => 'refskema.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'RefSkemaController@index']);
        Route::post('/store', ['as' => 'store', 'uses' => 'RefSkemaController@store']);
        Route::put('/update', ['as' => 'update', 'uses' => 'RefSkemaController@update']);
        Route::put('/toggle', ['as' => 'toggle', 'uses' => 'RefSkemaController@toggle']);
    });
    
    // Ref Permission
    Route::group(['prefix' => 'ref-permission', 'as' => 'permission.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'RefPermissionController@index']);
        Route::post('/store', ['as' => 'store', 'uses' => 'RefPermissionController@store']);
        Route::put('/update', ['as' => 'update', 'uses' => 'RefPermissionController@update']);
    });
    
    // Ref Role
    Route::group(['prefix' => 'ref-role', 'as' => 'role.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'RefRoleController@index']);
        Route::post('/store', ['as' => 'store', 'uses' => 'RefRoleController@store']);
        Route::put('/update', ['as' => 'update', 'uses' => 'RefRoleController@update']);
    });
    
    // Monitoring
    Route::group(['prefix' => 'monitoring', 'as' => 'monitoring.'], function () {
        Route::get('/log-error', ['as' => 'log-error', 'uses' => '\Opcodes\Monitoring\Http\Controllers\LogViewerController@index']);
        
        // Database
        Route::group(['prefix' => 'database', 'as' => 'database.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'BackupController@index']);
            Route::get('/download/{fileName}', ['as' => 'download', 'uses' => 'BackupController@download']);
            Route::get('/backup', ['as' => 'backup', 'uses' => 'BackupController@backup']);
        });
        
    });

    // Rekapitulasi
    Route::group(['prefix' => 'recap', 'as' => 'recap.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'RecapController@index']);
        Route::post('/download', ['as' => 'download', 'uses' => 'RecapController@download']);
    });

    // Chart
    Route::get('/chart', ['as' => 'chart.index', 'uses' => 'HomeController@chart']);
});

Route::group(['middleware' => ['student'], 'prefix' => 'student'], function () {
    // Profile
    Route::get('/profile', ['as' => 'profile.index', 'uses' => 'Student\ProfileController@index']);
    Route::put('/profile', ['as' => 'profile.update', 'uses' => 'Student\ProfileController@update']);

    // Proposal
    Route::group(['prefix' => 'usulan', 'as' => 'proposal.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'Student\ProposalController@index']);
        Route::get('/review/{id}', ['as' => 'review', 'uses' => 'Student\ProposalController@review']);
        Route::post('/review', ['as' => 'review.store', 'uses' => 'Student\ProposalController@reviewStore']);
        Route::get('/create', ['as' => 'create', 'uses' => 'Student\ProposalController@create']);
        Route::delete('/delete', ['as' => 'delete', 'uses' => 'Student\ProposalController@destroy']);
        Route::get('/edit/{id}', ['as' => 'edit', 'uses' => 'Student\ProposalController@edit']);
        Route::get('/show/{id}', ['as' => 'show', 'uses' => 'Student\ProposalController@show']);
        Route::put('/update', ['as' => 'update', 'uses' => 'Student\ProposalController@update']);
        Route::post('/store', ['as' => 'store', 'uses' => 'Student\ProposalController@store']);
        Route::post('/member/remove', ['as' => 'member.remove', 'uses' => 'Student\ProposalController@memberRemove']);
        Route::post('/member/add', ['as' => 'member.add', 'uses' => 'Student\ProposalController@memberAdd']);
        Route::get('/member/{id}', ['as' => 'member', 'uses' => 'Student\ProposalController@member']);
        Route::get('/download/form', ['as' => 'download.form', 'uses' => 'Student\ProposalController@download']);
        Route::get('/download/berita', ['as' => 'download.berita', 'uses' => 'Student\ProposalController@download2']);
        Route::get('/download/proposal', ['as' => 'download', 'uses' => 'Student\ProposalController@downloadProposal']);
    });

    // Panduan
    Route::get('/panduan', ['as' => 'panduan.index', 'uses' => 'HomeController@panduan']);
});

Route::group(['middleware' => ['teacher'], 'prefix' => 'teacher', 'as' => 'teacher.'], function () {
    // Profile
    Route::get('/profile', ['as' => 'profile.index', 'uses' => 'Teacher\ProfileController@index']);
    Route::put('/profile', ['as' => 'profile.update', 'uses' => 'Teacher\ProfileController@update']);

    // Proposal
    Route::get('/usulan', ['as' => 'proposal.index', 'uses' => 'Teacher\ProposalController@index']);
    Route::get('/review', ['as' => 'proposal.review', 'uses' => 'Teacher\ProposalController@review']);
    Route::post('/review', ['as' => 'proposal.review.store', 'uses' => 'Teacher\ProposalController@reviewerStore']);
    Route::post('/review-acc', ['as' => 'proposal.review.acc', 'uses' => 'Teacher\ProposalController@reviewerAcc']);
    Route::get('/review/show/{id}', ['as' => 'proposal.review.detail', 'uses' => 'Teacher\ProposalController@reviewer']);
    Route::post('/print', ['as' => 'proposal.print', 'uses' => 'Teacher\ProposalController@print']);
    Route::get('/usulan/show/{id}', ['as' => 'proposal.show', 'uses' => 'Teacher\ProposalController@show']);
    Route::get('/usulan/download/form', ['as' => 'proposal.download.form', 'uses' => 'Teacher\ProposalController@download']);
    Route::get('/usulan/download/berita', ['as' => 'proposal.download.berita', 'uses' => 'Teacher\ProposalController@download2']);
    Route::get('/usulan/download/proposal', ['as' => 'proposal.download', 'uses' => 'Teacher\ProposalController@downloadProposal']);


    Route::get('/panduan', ['as' => 'panduan.index', 'uses' => 'HomeController@panduan']);
});
