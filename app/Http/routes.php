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

Route::get('/', function(){
	$testPersons = App\testPerson::with('test_case')->get();

	return view('app')->with(array('testPersons' => $testPersons));
});
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

Route::get('Api/TestData/get/{ms}', function($ms){
	return "";
});

Route::get('/Api/Gsr/get/{test_case_id}', function($testCaseId){
	$gsr_data = App\GsrReading::where('test_case_id', $testCaseId)->get();
	
	$return_json = array('label'=> 'GSR', 'data' => array());
	foreach($gsr_data as $reading){
		array_push($return_json['data'], array($reading->timestamp, $reading->value));
	}
	return response()->json($return_json);
});

Route::get('Api/TestPersons/get', function(){
	$persons = App\testPerson::all();

	return response()->json($persons);
});

Route::get('/Api/TestCase/create/', function(){
	$fileContents = Storage::get("/data/datacombined.json");

	//$fileContents;
	$json = json_decode($fileContents, true);


	$channels = array();
	$msc=microtime(true);

	$eeg_root = $json['FusionData']['EEGData'];
	$gsr_root = $json['FusionData']['GSRData'];
	$meta_root = $json['FusionData']['MetaData'];
	
	/*
	 * Create a new test case
	*/
	$testCase = new App\test_case();
	$testCase->location = $meta_root['Location'];
	$testCase->timestamp = $meta_root['Timestamp'];
	$testCase->description = $meta_root['TestDescription'];

	$testPerson = App\testPerson::firstOrCreate(['name' => $meta_root['TestSubjectName']]);
	$testPerson->age = $meta_root['TestSubjectAge'];
	$testPerson->occupation = $meta_root['TestSubjectOccupation'];
	$testPerson->sex = $meta_root['TestSubjectSex'];

	//Save the test_case to the test person, such that the foreign key constraint is retained
	$testPerson->test_case()->save($testCase);

	//Save it to the DB
	$testPerson->push();

	//Get all EEG data
	if(!empty($eeg_root)){
		foreach($eeg_root as $channels){
			foreach($channels as $channel){
				$c = App\EegChannel::firstOrCreate(['name' => $channel['HEADER']]);
				$headerRows = array();
				$i = 0;
				$cnt += count($channel['data']);
				foreach($channel['data'] as $reading){
					array_push($headerRows, array('test_case_id' => $testCase->id, 'channel_id'=>$c->id, 'value'=>$reading, 'timestamp' => $i));
					$i++;
				}

				DB::table('eeg_reading')->insert($headerRows);
			}
		}
	}

	//Create the GSR data
	$gsr_data = array();
	foreach($gsr_root as $entry){
		array_push($gsr_data, array('timestamp' => $entry[0], 'value' => $entry[1], 'test_case_id' => $testCase->id));		
	}

	DB::table('gsr_reading')->insert($gsr_data);

	$msc = microtime(true)-$msc;

	return response()->json(array('success' => true, 'time' => $msc));

});


Route::get('/Api/Test/cases', function(){
	$testCases = App\test_case::with('testPerson')->get();

	echo "<table style='width:800px;text-align:center;'><thead><tr><th>case id</th><th>Tester</th><th>Created</th></tr></thead>";
	foreach($testCases as $case){
		echo "<tr><td>{$case->id}</td><td>{$case->testPerson->name}</td><td>{$case->created_at}</td></tr>";
	}
	echo "</table>";
});

Route::get('Api/User/get/{id}', function($id){
	$testPerson = App\testPerson::with('test_case')->where('id', $id)->get();
	return response()->json($testPerson);
});


