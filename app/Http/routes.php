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
Route::get('/Api/Eeg/push', function(){
	$fileContents = Storage::get("/data/eeg-data.json");
	var_dump($fileContents);
});
Route::get('/Api/Test/create/{name}', function($name){
	
	$testCase = new App\test_case();
	$testPerson = App\testPerson::firstOrCreate(['name' => $name]);
	$testPerson->test_case()->save($testCase);
	$testPerson->push();
	return $testCase->id . " appended to user " . $testPerson->name . ' with id ' . $testPerson->id;
});
