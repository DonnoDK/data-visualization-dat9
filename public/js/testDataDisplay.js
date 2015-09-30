$("#test-data").hide();
function displayTestData(pointId)
{
	var ms = pointId / 128 * 1000;
	$.ajax({
		url: "Api/TestData/get/" + ms,
		dataType: "json",
		success: function(response){
			$("#test-data").slideDown();
			AppendTestData(response);
		}
	});
}
/*
                                    <tr>
                                        <td>Image Type:</td>
                                        <td><span id="test-data-image-type"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Subject Valence:</td>
                                        <td><span id="test-data-subject-valence"></span></td>
                                    </tr>
                                    <tr>
                                        <td>Control Valence:</td>
                                        <td><span id="test-data-control-valence"></span></td>
                                    </tr>                  
                                    <tr>
                                        <td>Shown for:</td>
                                        <td><span id="test-data-shown-for"></span></td>
                                    </tr>       
*/
function AppendTestData(testData){
	if(testData.length == 0)
	{
		$("#test-data > img").attr("src", "images/none.png");
		$("#test-data #test-data-image-type").text("N/A");
		$("#test-data #test-data-subject-valence").text("N/A");
		$("#test-data #test-data-control-valence").text("N/A");
		$("#test-data #test-data-shown-for").text("N/A");
	} else {
		$("#test-data > img").attr("src", "test/" + testData[0].image_path);
		$("#test-data #test-data-image-type").text(testData[0].image_type);
		$("#test-data #test-data-subject-valence").text(testData[0].test_person_valence);
		$("#test-data #test-data-control-valence").text(testData[0].image_control_valence);
		$("#test-data #test-data-shown-for").text((testData[0].timestamp_end - testData[0].timestamp_start)/1000 + "s");	
	}

}