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
Route::get('/Api/Eeg/get/{testcase_id}/{channel_id}', function($testCaseId, $channel_id){

	$eeg_data = App\EegReading::with(array('testCase', 'channel'))->where(array('channel_id' => $channel_id, 'test_case_id' => $testCaseId))->get();


	$return_json = array('label'=> $eeg_data[0]->channel->name, 'data' => array());
	foreach($eeg_data as $reading){
		array_push($return_json['data'], array($reading->timestamp, $reading->value));
	}

	return response()->json($return_json);
});

Route::get('Api/Eeg/getPoint/{testcase_id}/{channel_id}/{point_id}', function($testCaseId, $channelId, $pointId){
	$eeg_data = App\EegReading::with(array('testCase', 'channel'))->
								where(array('channel_id' => $channelId, 'test_case_id' => $testCaseId))->
								offset($pointId)->
								limit(256)->
								get();

	$json = array('data' => array());
	foreach($eeg_data as $reading){
		array_push($json['data'], $reading->value); 
	}	

	return response()->json($json);
});

Route::get('/Api/Gsr/get/{test_case_id}', function($testCaseId){
	$gsr_data = App\GsrReading::where('test_case_id', $testCaseId)->get();
	
	$return_json = array('label'=> 'GSR', 'data' => array());
	foreach($gsr_data as $reading){
		array_push($return_json['data'], array($reading->timestamp, $reading->value));
	}
	return response()->json($return_json);
});
Route::get('/Api/Eeg/push/{testcase_id}', function($testCaseId){
	$fileContents = Storage::get("/data/eeg_data.json");

	//$fileContents;
	$json = json_decode($fileContents, true);
	$channels = array();
	$msc=microtime(true);



	foreach($json as $entry){
		foreach($entry as $channel){
			$c = App\EegChannel::firstOrCreate(['name' => $channel['header']]);
			$headerRows = array();
			$i = 0;
			foreach($channel['rawData'] as $value){
				array_push($headerRows, array('test_case_id' => $testCaseId, 'channel_id' => $c->id, 'value' => $value['rawData'], 'timestamp' =>$i ));
				$i++;
			}

			DB::table('eeg_reading')->insert($headerRows);
		
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

Route::get('Api/Gsr/push/{test_case_id}', function($testCaseId){
	$fileContents = Storage::get("/data/gsr_data.json");

	//$fileContents;
	$json = json_decode($fileContents, true);
	$msc=microtime(true);

/*
	foreach($entry as $channel){
		$c = App\EegChannel::firstOrCreate(['name' => $channel['header']]);
		$headerRows = array();
		$i = 0;
		foreach($channel['rawData'] as $value){
			array_push($headerRows, array('test_case_id' => $testCaseId, 'channel_id' => $c->id, 'value' => $value['rawData'], 'timestamp' =>$i ));
			$i++;
		}

		DB::table('eeg_reading')->insert($headerRows);
	
	}
*/
	$gsr_data = array();
	foreach($json['data'] as $entry){
		array_push($gsr_data, array('timestamp' => $entry[0], 'value' => $entry[1], 'test_case_id' => $testCaseId));
	}

	DB::table('gsr_reading')->insert($gsr_data);

	$msc=microtime(true)-$msc;

	echo $msc.' seconds'; // in seconds
	echo ($msc*1000).' milliseconds'; // in millseconds
});