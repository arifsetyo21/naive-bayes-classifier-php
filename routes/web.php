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
    return redirect()->route('training.index');
    // return view('welcome');
});

Route::group(['prefix' => '/training'], function () {
    Route::get('/add-url', 'ArticleController@addUrl')->name('training.addUrl');
    Route::post('/store-url', 'ArticleController@storeUrl')->name('training.storeUrl');
    Route::get('/scrap', 'ArticleController@scrap')->name('training.scrap');
    Route::post('/scrap', 'ArticleController@scrapContentKumparan')->name('training.scrapContentKumparan');
    Route::get('/preprocess/{id}', 'ArticleController@preprocess')->name('training.preprocess');
    Route::post('/preprocess', 'ArticleController@preprocessAll')->name('training.preprocessAll');
    Route::get('/save-cleaned/{id}', 'WordController@saveWordFromArticleClass')->name('training.saveCleaned');
});

Route::group(['prefix' => 'article'], function () {
    Route::post('delete', 'ArticleController@deleteArticle')->name('article.delete');
    Route::post('delete-permanent/{id}', 'ArticleController@deleteArticlePermanent')->name('article.deletePermanent');
    Route::get('show/{id}', 'ArticleController@show')->name('article.show');
    Route::get('export', 'ArticleController@export')->name('article.export');
    Route::post('import', 'ArticleController@import')->name('article.import');
});

Route::group(['prefix' => 'classification'], function () {
    Route::get('/', 'ClassificationController@index')->name('classification.index');
    Route::post('/', 'ClassificationController@store')->name('classification.store');
    Route::get('list', 'ClassificationController@list')->name('classification.list');
    Route::post('direct-classification', 'ClassificationController@direct')->name('classification.direct');
    Route::get('nbc-modified', 'ClassificationController@indexModified')->name('classification.nbcModifiedIndex');
    Route::post('nbc', 'ClassificationController@nbc')->name('classification.nbc');
    Route::post('nbc-modified', 'ClassificationController@nbcModified')->name('classification.nbcModified');
    Route::get('create', 'ClassificationController@create')->name('classification.create');
    Route::post('store-testing', 'ClassificationController@storeDataTesting')->name('classification.storeDataTesting');
    Route::delete('destroy/{id}', 'ClassificationController@destroy')->name('classification.destroy');
    Route::get('show/{id}', 'ClassificationController@show')->name('classification.show');
    Route::post('classification-all', 'ClassificationController@classificationAll')->name('classification.all');
    Route::get('delete-all', 'ClassificationController@deleteAll')->name('classification.deleteAll');
});

Route::group(['prefix' => 'setting'], function () {
    Route::get('/', 'SettingController@index')->name('setting.index');
    Route::delete('/', 'SettingController@destroy')->name('setting.destroy');
});

Route::group(['prefix' => 'tool'], function () {
    Route::get('/', 'ToolController@index')->name('tool.index');
    Route::post('/convert', 'ToolController@convertToArrayJson')->name('tool.convertjson');
});

Route::group(['prefix' => 'dashboard'], function() {
    Route::get('/', 'DashboardController@index')->name('dashboard.index');
    Route::get('/destroy/{id}', 'DashboardController@destroy')->name('dashboard.destroy');
    Route::get('/detail/{id}', 'DashboardController@show')->name('dashboard.show');
    Route::post('/destroy-all', 'DashboardController@destroyAll')->name('dashboard.destroyAll');
    Route::get('/article-list-chart', 'DashboardController@articleListChart')->name('dashboard.list-chart');
});

Route::delete('/category/destroy/{id}', 'CategoryController@deletePermanent')->name('category.destroy-permenent');
Route::resource('/category', 'CategoryController');
Route::resource('/training', 'TrainingController');