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
Route::get('/Api/Eeg/get/{testcase_id}/{channel_id}/{pure?}', function($testCaseId, $channel_id, $pure = 0){

	$eeg_data = App\EegReading::with(array('testCase', 'channel'))->where(array('channel_id' => $channel_id, 'test_case_id' => $testCaseId))->get();


	$return_json = array('label'=> $eeg_data[0]->channel->name, 'data' => array(), 'cnt' => 0);

	array_push($return_json['data'], [$eeg_data[0]->timestamp, $eeg_data[0]->value]);
	
	$cnt = count($eeg_data);
	for($i = 1; $i < $cnt - 1; $i++){
		$prev = $eeg_data[$i - 1];
		$next = $eeg_data[$i + 1];

		$avg = ($prev->value + $next->value ) / 2;
		$perc = $avg * 0.001;
		$x0 = $eeg_data[$i]->timestamp;
		$y0 = $eeg_data[$i]->value;
		$x1 = $prev->timestamp;
		$y1 = $prev->value;
		$x2 = $next->timestamp;
		$y2 = $next->value;

		$dist = abs(($y2-$y1)*$x0 - ($x2-$x1)*$y0 + $x2*$y1 - $y2*$x1) / sqrt(pow($y2-$y1,2) + pow($x2 - $x1, 2));
		//if($eeg_data[$i]->value <= ($avg + $perc) && $eeg_data[$i]->value >= ($avg - $perc) )
		if ($dist < $perc)
		{
			//var_dump("ja");
			//var_dump($avg);
			//var_dump($perc);
			//var_dump($eeg_data[$i]->value);
			//die;
			//if($pure == 1)
			//	array_push($return_json['data'], [$eeg_data[$i]->timestamp, $eeg_data[$i]->value]);
		}
		else
		{
			////var_dump($return_json);
			array_push($return_json['data'], [$eeg_data[$i]->timestamp, $eeg_data[$i]->value]);
			//var_dump($return_json);
			//var_dump("nej");
			//var_dump($avg);
			//var_dump($perc);
			//var_dump($eeg_data[$i]->value);
			//die;
		}

			
	}
	
	array_push($return_json['data'], [$eeg_data[$cnt-1]->timestamp, $eeg_data[$cnt-1]->value]);

	$return_json['cnt'] = count($return_json['data']);
	/*foreach($eeg_data as $reading){
		array_push($return_json['data'], array($reading->timestamp, $reading->value));
	}*/
	
	//echo count($return_json['data']) . "<br>" . count($eeg_data);

	//die;

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
	$testData = App\TestData::whereRaw('timestamp_start <= ? AND timestamp_end >= ? ', [$ms, $ms])->get();
	return response()->json($testData);
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
	$fileContents = Storage::get("/data/dennisDataTorsdag.json");

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
		foreach($eeg_root as $channel){
			$c = App\EegChannel::firstOrCreate(['name' => $channel['HEADER']]);
			$headerRows = array();
			$i = 0;
			foreach($channel['data'] as $reading){
				array_push($headerRows, array('test_case_id' => $testCase->id, 'channel_id'=>$c->id, 'value'=>$reading, 'timestamp' => $i));
				$i++;
				if($i % 200 == 0){
					DB::table('eeg_reading')->insert($headerRows);
					$headerRows = [];
				}
			}

			DB::table('eeg_reading')->insert($headerRows);
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

Route::get('Api/TestData/push/{test_case_id}/{person_id}', function($testCaseId, $personId){
	$fileContents = Storage::get("/data/dennisTorsdagTestDataOrg.json");

	//$fileContents;
	$json = json_decode($fileContents);

	$testDataRows = array();
	foreach($json as $testImage){
		array_push($testDataRows, 
			array(
				'test_person_id' => $personId, 
				'test_case_id' => $testCaseId, 
				'image_path' => $testImage->img,
				'image_type' => $testImage->image_type,
				'image_control_valence' => $testImage->control_valence,
				'test_person_valence' => $testImage->valence,
				'image_control_arousal' => $testImage->control_arousal,
				'test_person_arousal' => $testImage->control_arousal,
				'timestamp_start' => $testImage->time_image_shown,
				'timestamp_end' => $testImage->time_clicked_next));
	}

	DB::table('test_data')->insert($testDataRows);
});

Route::get('Api/User/get/{id}', function($id){
	$testPerson = App\testPerson::with('test_case')->where('id', $id)->get();
	return response()->json($testPerson);
});


