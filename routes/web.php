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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/scrape','HomeController@showScrape')->name('scrape');
Route::post('scrape/process','HomeController@processScrape')->name('scrape.process');

Route::get('/summarize','HomeController@showSummarize')->name('summarize');
Route::get('/summarize/{id}','HomeController@showSummarizeNote')->name('show.summarize.note');
Route::get('/summarize/{id}/process','HomeController@summarizeNote')->name('summarize.note');

Route::get('/notes','HomeController@showNotes')->name('notes');
Route::get('/notes/{id}','HomeController@showNote')->name('show.note');

Route::post('/notes/{id}/update','HomeController@updateNoteAttributes')->name('update.note.attributes');
Route::delete('/notes/{id}/delete','HomeController@deleteNote')->name('delete.note');
Route::post('/notes/{id}/publish','HomeController@publish')->name('publish.note');


Route::get('/question/{id}','HomeController@showQuestion')->name('show.question');

Route::post('/question/actions/approve','HomeController@approve')->name('question.approve');
Route::post('/question/actions/getTypeSuggestion','HomeController@getTypeSuggestion')->name('question.get.type.suggestion');
Route::post('/question/actions/delete','HomeController@deleteQuestion')->name('question.delete');
Route::post('/question/actions/saveType','HomeController@saveType')->name('question.save.type');
