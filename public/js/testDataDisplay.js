function displayTestData(pointId)
{
	var ms = pointId / 128 * 1000;
	alert(ms);
	$.ajax({
		url: "Api/TestData/get/" + ms,
		dataType: "json",
		success: function(response){

		}
	});
}