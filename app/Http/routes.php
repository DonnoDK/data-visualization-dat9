<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'API@index');
Route::get('/API/EEG/Push', 'EEG@Push');
Route::get('/Api/Test/create/{name}', function($name){
	
	$testCase = new App\test_case();
	$testPerson = App\testPerson::firstOrCreate(['name' => $name]);
	$testPerson->test_case()->save($testCase);
	$testPerson->push();
	var_dump($testPerson);
});
