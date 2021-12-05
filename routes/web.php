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

// Route::view('/', 'auth.login2');
Route::view('/', 'landing');
Route::view('/panduan', 'panduan')->name('panduan');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('verified')->name('home');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
  Route::get('/home/recap/{tahun}', ['as' => 'home.recap', 'uses' => 'HomeController@recap']);

  // Period
  Route::get('/periode', [ 'as' => 'period.index', 'uses' => 'PeriodController@index']);
  Route::get('/periode/show', [ 'as' => 'period.show', 'uses' => 'PeriodController@show']);
  Route::post('/periode/store', [ 'as' => 'period.store', 'uses' => 'PeriodController@store']);
  Route::put('/periode/update', [ 'as' => 'period.update', 'uses' => 'PeriodController@update']);

  // Proposal
  Route::get('/usulan', ['as' => 'usulan.index', 'uses' => 'ProposalController@index']);
  Route::get('/usulan/show/{id}', ['as' => 'usulan.show', 'uses' => 'ProposalController@show']);
  Route::put('/usulan/update', ['as' => 'usulan.update', 'uses' => 'ProposalController@update']);
  Route::put('/usulan/nilai', ['as' => 'usulan.nilai', 'uses' => 'ProposalController@nilai']);
  Route::get('/usulan/print/{tahun}', ['as' => 'usulan.print', 'uses' => 'ProposalController@print']);
  Route::delete('/usulan', ['as' => 'usulan.delete', 'uses' => 'ProposalController@destroy']);

  // User
  Route::get('/user', ['as' => 'user.index', 'uses' => 'UserController@index']);
  Route::get('/user/create/{role}', ['as' => 'user.create', 'uses' => 'UserController@create']);
  Route::get('/user/show/{role}/{id}', ['as' => 'user.show', 'uses' => 'UserController@show']);
  Route::post('/user/store/{role}', ['as' => 'user.store', 'uses' => 'UserController@store']);
  Route::put('/user/update/{role}', ['as' => 'user.update', 'uses' => 'UserController@update']);
  Route::post('/user/import', ['as' => 'user.import', 'uses' => 'UserController@import']);
  Route::get('/user/sim/{id}', ['as' => 'user.getsim', 'uses' => 'UserController@showSim']);
  Route::put('/user/sim', ['as' => 'user.sim', 'uses' => 'UserController@updateSim']);

  // Major
  Route::get('/major', ['as' => 'major.index', 'uses' => 'MajorController@index']);
  Route::post('/major/store', ['as' => 'major.store', 'uses' => 'MajorController@store']);
  Route::get('/major/show/{id}', ['as' => 'major.show', 'uses' => 'MajorController@show']);
  Route::put('/major/update', ['as' => 'major.update', 'uses' => 'MajorController@update']);

  // Rekapitulasi
  Route::get('/recap', ['as' => 'recap.index', 'uses' => 'RecapController@index']);
  Route::post('/recap/download', ['as' => 'recap.download', 'uses' => 'RecapController@download']);

  // Chart
  Route::get('/chart', ['as' => 'chart.index', 'uses' => 'HomeController@chart']);
});

Route::group(['middleware' => ['student', 'verified'], 'prefix' => 'student'], function () {
  // Profile
  Route::get('/profile', ['as' => 'profile.index', 'uses' => 'Student\ProfileController@index']);
  Route::put('/profile', ['as' => 'profile.update', 'uses' => 'Student\ProfileController@update']);
  Route::put('/password-update', ['as' => 'student.password.update', 'uses' => 'Student\ProfileController@updatePassword']);

  // Proposal
  Route::get('/usulan', ['as' => 'proposal.index', 'uses' => 'Student\ProposalController@index']);
  Route::get('/usulan/create', ['as' => 'proposal.create', 'uses' => 'Student\ProposalController@create']);
  Route::delete('/usulan/delete', ['as' => 'proposal.delete', 'uses' => 'Student\ProposalController@destroy']);
  Route::get('/usulan/edit/{id}', ['as' => 'proposal.edit', 'uses' => 'Student\ProposalController@edit']);
  Route::get('/usulan/show/{id}', ['as' => 'proposal.show', 'uses' => 'Student\ProposalController@show']);
  Route::put('/usulan/update', ['as' => 'proposal.update', 'uses' => 'Student\ProposalController@update']);
  Route::post('/usulan/store', ['as' => 'proposal.store', 'uses' => 'Student\ProposalController@store']);
  Route::post('/usulan/member/remove', ['as' => 'proposal.member.remove', 'uses' => 'Student\ProposalController@memberRemove']);
  Route::post('/usulan/member/add', ['as' => 'proposal.member.add', 'uses' => 'Student\ProposalController@memberAdd']);
  Route::get('/usulan/member/{id}', ['as' => 'proposal.member', 'uses' => 'Student\ProposalController@member']);
  Route::get('/usulan/download/form', ['as' => 'proposal.download.form', 'uses' => 'Student\ProposalController@download']);
  Route::get('/usulan/download/berita', ['as' => 'proposal.download.berita', 'uses' => 'Student\ProposalController@download2']);

  // Panduan
  Route::get('/panduan', ['as' => 'panduan.index', 'uses' => 'HomeController@panduan']);
});

Route::group(['middleware' => ['teacher'], 'prefix' => 'teacher', 'as' => 'teacher.'], function () {
  // Profile
  Route::get('/profile', ['as' => 'profile.index', 'uses' => 'Teacher\ProfileController@index']);
  Route::put('/profile', ['as' => 'profile.update', 'uses' => 'Teacher\ProfileController@update']);
  Route::put('/password-update', ['as' => 'password.update', 'uses' => 'Teacher\ProfileController@updatePassword']);

  // Proposal
  Route::get('/usulan', ['as' => 'proposal.index', 'uses' => 'Teacher\ProposalController@index']);
  Route::get('/review', ['as' => 'proposal.review', 'uses' => 'Teacher\ProposalController@review']);
  Route::post('/print', ['as' => 'proposal.print', 'uses' => 'Teacher\ProposalController@print']);
  Route::get('/usulan/show/{id}', ['as' => 'proposal.show', 'uses' => 'Teacher\ProposalController@show']);
  Route::get('/usulan/download/form', ['as' => 'proposal.download.form', 'uses' => 'Teacher\ProposalController@download']);
  Route::get('/usulan/download/berita', ['as' => 'proposal.download.berita', 'uses' => 'Teacher\ProposalController@download2']);

  Route::get('/panduan', ['as' => 'panduan.index', 'uses' => 'HomeController@panduan']);
});
