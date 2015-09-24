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
Route::get('/Api/Eeg/push/{testcase_id}', function($testCaseId){
	$fileContents = Storage::get("/data/eeg_data.json");

	//$fileContents;
	$json = json_decode($fileContents, true);
	$channels = array();
	$msc=microtime(true);



	foreach($json as $entry){
		foreach($entry as $channel){
			$c = App\EegChannel::firstOrCreate(['name' => $channel['header']]);

			foreach($channel['rawData'] as $value){
				$eegReading = App\EegReading::create(
					[
						'test_case_id' => $testCaseId,
						'channel_id'	=> $c->id,
						'value' 	=> $value['rawData']
					]
				);
				
				$eegReading->push();

			}
		
		}

	}
	$msc=microtime(true)-$msc;

	echo $msc.' seconds'; // in seconds
	echo ($msc*1000).' milliseconds'; // in millseconds

});

Route::get('/Api/Test/cases', function(){
	$testCases = App\test_case::with('testPerson')->get();

	echo "<table style='width:800px;text-align:center;'><thead><tr><th>case id</th><th>Tester</th><th>Created</th></tr></thead>";
	foreach($testCases as $case){
		echo "<tr><td>{$case->id}</td><td>{$case->testPerson->name}</td><td>{$case->created_at}</td></tr>";
	}
	echo "</table>";
});

Route::get('/Api/Test/create/{name}', function($name){
	
	$testCase = new App\test_case();
	$testPerson = App\testPerson::firstOrCreate(['name' => $name]);
	$testPerson->test_case()->save($testCase);
	$testPerson->push();
	return $testCase->id . " appended to user " . $testPerson->name . ' with id ' . $testPerson->id;
});
