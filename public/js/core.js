$(document).ready(function () {

	$("nav a").on("click", function(){
		$.get("pages/" + $(this).data("content") + ".html", function(response){
			$("#content").html(response);
		});
	});

	$.get("updated-ts.txt", function(response){
		$("#version").text(response);
	});
	var dataSets = [];
	var dataSetsCache = [];
	$("#eeg_filters_table input[type=checkbox]").on('click', function(){
		var self = $(this);
		if($(this).is(':checked')){
			var grabData = true;
			dataSetsCache.forEach(function(set){
				if(set.label == self.attr('name')){
					grabData = false;
					dataSets.push(set); //add set to active list
					console.log("Cache hit, should retrive.");
					plotEeg();
				}
			});
			if(grabData){
				$.ajax({
			        //This will retrieve the contents of the folder if the folder is configured as 'browsable'
			        url: "Api/Eeg/get/" + $("#selected-case").data('selected-person-selected-case') + "/" + self.val(),
			        dataType: "json",
			        success: function (response) {
			        	dataSets.push(response);
			        	dataSetsCache.push(response);
			        	plotEeg();
		        	}
	        	});
			}
		} else {
			dataSets.forEach(function(set){
				if(set.label == self.attr('name')){
					dataSets.splice(dataSets.indexOf(set), 1);
					plotEeg();
				}
			});
		}     	
	});

    Array.max = function( array ){
        return Math.max.apply( Math, array );
    };

	function plotEeg(){
		var maxX = 0;
		var maxY = 0;
	    var data = [];
	    var done = false;
		dataSets.forEach(function(set){
			var dx = [];
		    var dy = [];

		    set.data.forEach(function(item) {
		        dx.push(item[0]);
		        dy.push(item[1]);
		    });

		    maxX = (Array.max(dx) > maxX) ? Array.max(dx) : maxX;
		    maxY = (Array.max(dy) > maxY) ? Array.max(dy) : maxY;

		    data.push({ data:set.data, label: set.label })
		});
	    /*$.ajax({
	        url: 'Api/Eeg/get/1/14',
	        method: 'GET',
	        success: function(response){
	            console.log("Got data!");
	            var bandGraph = [
	                {label: response.label + " - Delta", data: [] },
	                {label: response.label + " - Theta", data: [] },
	                {label: response.label + " - Alpha", data: [] },
	                {label: response.label + " - Beta", data: [] },
	            ];
	            var num = 0;
	            var deltaDef = {lowerLimit:1, upperLimit:3, label:"Delta"},
	                thetaDef = {lowerLimit:4, upperLimit:8, label:"Theta"},
	                alphaDef = {lowerLimit:9, upperLimit:12, label:"Alpha"},
	                betaDef = {lowerLimit:13, upperLimit:27, label:"Beta"};            
	            response.data.forEach(function(reading){
	                if(num != -1){
		             	var tmp = [];
		                if(num + 128 <= response.data.length){
		                    for(var i = num; i < (128 + num) ; i++){
		                        tmp.push(response.data[i][1]);
		                    }
		                }
		                var fft = myFFT(tmp);
		                
		                var absoluteBandPower = {};
		                    absoluteBandPower.Delta = computeAbsoluteBandPower(deltaDef, fft);
		                    absoluteBandPower.Theta = computeAbsoluteBandPower(thetaDef, fft);
		                    absoluteBandPower.Alpha = computeAbsoluteBandPower(alphaDef, fft);
		                    absoluteBandPower.Beta = computeAbsoluteBandPower(betaDef, fft);
		                
		                bandGraph[0].data.push([num, absoluteBandPower.Delta]);
		                bandGraph[1].data.push([num, absoluteBandPower.Theta]);
		                bandGraph[2].data.push([num, absoluteBandPower.Alpha]);
		                bandGraph[3].data.push([num, absoluteBandPower.Beta]);
		                   	
	                }

	                num++;
	            });

	            data.push(bandGraph[0]);
	            //data.push(bandGraph[1]);
	            //data.push(bandGraph[2]);
	            //data.push(bandGraph[3]);
	            console.log("Done");
	            done = true;
	        }
	    });*/


	    var options = {
	    	series: {lines: {show: true }, shadowSize: 0 },
	        xaxis: { show: true, zoomRange: null, panRange: [0, maxX + 1000] },
	        yaxis: { zoomRange: null, panRange: [-500, maxY + 10000] },
	        axisLabels: {
	            show: true
	        },
	        xaxes: [{
	            axisLabel: 'Readings (128 r/s)',
	        }],
	        yaxes: [{
	            position: 'left',
	            axisLabel: 'MicroVolts',
	        }],
	    	points: {show: false },
	    	lines: { show: true },
	    	zoom: { interactive: true },
	        pan: { interactive: true }, 
			grid: { 
				hoverable: true, 
				clickable: true,
		        markings: [
					{ xaxis: {from: 2196, to: 3057}, color: "#808066" },
					{ xaxis: {from: 5242, to: 6243}, color: "#FF9966" },
					{ xaxis: {from: 8531, to: 10248}, color: "#FF9966" },
					{ xaxis: {from: 12475, to: 13503}, color: "#808066" },
					{ xaxis: {from: 15481, to: 16618}, color: "#FF9966" },
					{ xaxis: {from: 18544, to: 19687}, color: "#FF9966" },
					{ xaxis: {from: 21655, to: 23837}, color: "#808066" },
					{ xaxis: {from: 26231, to: 27489}, color: "#808066" },
					{ xaxis: {from: 29894, to: 30666}, color: "#808066" },
					{ xaxis: {from: 32920, to: 34589}, color: "#66FF33" },
					{ xaxis: {from: 37018, to: 38033}, color: "#66FF33" },
					{ xaxis: {from: 40525, to: 41735}, color: "#FF9966" },
					{ xaxis: {from: 43948, to: 45561}, color: "#66FF33" },
					{ xaxis: {from: 47733, to: 48496}, color: "#66FF33" },
					{ xaxis: {from: 50713, to: 51821}, color: "#66FF33" }
	        	]				

			}
	    };
	   var el = $("#eeg-chart");
	   var idxx = setInterval(function(){
	   	done = true;
	   	if(done){
	   		$.plot(el, data, options);	
	   		clearInterval(idxx);
	   	}
	   }, 3000);
	   
	   

	   plotGSR();
	}

	function plotGSR(){
		$.ajax({
	        url: "Api/Gsr/get/" + $("#selected-case").data('selected-person-selected-case'),
	        dataType: "json",
	        success: function (response) {
			var dx = [];
		    var dy = [];

		    response.data.forEach(function(item) {
		        dx.push(item[0]);
		        dy.push(item[1]);
		    });

			var options = {
		        series: { lines: { show: true }, shadowSize: 0 },
		        xaxis: { zoomRange: null, panRange: [0, Array.max(dx) + 100] },
		        yaxis: { zoomRange: null, panRange: [0, Array.max(dy) + 100] },
    	        axisLabels: {
	            	show: true
		        },
		        xaxes: [{
		            axisLabel: 'm/s',
		        }],
		        yaxes: [{
		            position: 'left',
		            axisLabel: 'Ohm',
		        }],
		        zoom: {
		            interactive: true	
		        },
		        pan: {
		            interactive: true
		        }
		    };
		   	var el = $("#gsr-chart");
		   
		    $.plot(el, [response], options);
		    
	    	}
		});
	}

	//Used to identify id's in our routes.
	var channels = {"AF3": 4, "AF4": 17, "FC5": 7, "FC6": 14, "F3": 6, "F4": 15 , "F7": 5, "F8": 16, "T7": 8, "T8": 13, "P7": 9, "P8": 12, "O1": 10, "O2": 11 };
	
	$("#eeg-chart").bind("plotclick", function (event, pos, item) {
		//channels[item.series.label]
		computeFFT(1, channels[item.series.label], item.dataIndex);
		displayTestData(item.dataIndex);
	});

	//$("#test-cases-ui").hide();
	$("#test-cases-link").on('click', function(){
		$("#test-cases-ui").slideToggle();
	});
	
	$("#test-cases-ui").hide();
	$("#selected-case").on('click', function(){
		if($(this).data('selected-person-id') == -1){
			alert("You have to select a test person");
		} else {
			$("#test-cases-ui").fadeToggle();
		}
	});

	$(".test-persons").on('click', function(){
		var id = $(this).find(">a").data("person-id");
		var name = $(this).find(">a").data("person-name");

		$("#selected-case").data('selected-person-id', id);
		$("#selected-case >img").attr("src", "images/" + name + ".jpg");
		$("#selected-case >span").text(name);
		$("#meta-heading > img").attr("src", "images/" + name + ".jpg");
		$("#meta-heading > span").text(name);

		getPersonData(id);
	});

	$("#fucking_filters").hide()
	$(document).on('click', '.select-case-btn', function(){
		$("#fucking_filters").fadeIn();
		$('#selected-case').data('selected-person-selected-case', $(this).data('test-case-id'));
		$("#test-cases-ui").fadeToggle();
		
	});

	function getPersonData(id){
		$.ajax({
	        url: "Api/User/get/" + id,
	        dataType: "json",
	        success: function (response) {
	        	//console.log(response);
	        	$("#person-meta-created").text(response[0].created_at);
	        	$("#person-meta-name").text(response[0].name);
	        	$("#person-meta-age").text(response[0].age);
	        	$("#person-meta-occupation").text(response[0].occupation);

	        	var str = "";
	        	for(var i = 0; i <  response[0].test_case.length; i++){
	        		response[0].test_case[i]
	        		str += "<tr><td>" + response[0].test_case[i].created_at + "</td><td>NA</td><td><a href='#' data-test-case-id='"+ response[0].test_case[i].id +"' class='select-case-btn btn btn-default'>Select Test</a></td>";
	        	}
	        	$("#person-meta-tests").html(str);
	        }
	    });                                
	}
});