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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/scrap', 'ScrapController@index');
Route::group(['prefix' => '/training'], function () {
    Route::get('/addUrl', 'ArticleController@addUrl')->name('training.addUrl');
    Route::post('/storeUrl', 'ArticleController@storeUrl')->name('training.storeUrl');
    Route::get('/scrap', 'ArticleController@scrap')->name('training.scrap');
    Route::post('/scrap', 'ArticleController@scrapContentKumparan')->name('training.scrapContentKumparan');
    Route::get('/preprocess/{id}', 'ArticleController@preprocess')->name('training.preprocess');
    Route::get('/saveCleaned/{id}', 'WordController@saveWordFromArticleClass')->name('training.saveCleaned');
});

Route::group(['prefix' => 'article'], function () {
Route::post('delete', 'ArticleController@deleteArticle')->name('article.delete');
    Route::post('delete-permanent/{id}', 'ArticleController@deleteArticlePermanent')->name('article.deletePermanent');
    Route::get('show/{id}', 'ArticleController@show')->name('article.show');
});

Route::group(['prefix' => 'classification'], function () {
    Route::get('nbc-modified', 'ClassificationController@indexModified')->name('classification.nbcModifiedIndex');
    Route::post('nbc', 'ClassificationController@store')->name('classification.nbc');
    Route::post('nbc-modified', 'ClassificationController@storeModified')->name('classification.nbcModified');
});

Route::delete('/category/destroy/{id}', 'CategoryController@deletePermanent')->name('category.destroy-permenent');
Route::resource('/category', 'CategoryController');
Route::resource('/classification', 'ClassificationController');
Route::resource('/training', 'TrainingController');