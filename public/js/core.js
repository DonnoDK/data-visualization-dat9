$(document).ready(function () {
	$("nav a").on("click", function(){
		$.get("pages/" + $(this).data("content") + ".html", function(response){
			$("#content").html(response);
		});
	});

	$.get("updated-ts.txt", function(response){
		$("#version").text(response);
	});
	$("#eeg_filters").on('click', function(){
		$(this).next('div').slideToggle("slow");
	});
	var dataSets = [];
	var dataSetsCache = [];
	$("#eeg_filters_table input[type=checkbox]").on('click', function(){
		var self = $(this);
		if($(this).is(':checked')){
			var grabData = true;
			dataSetsCache.forEach(function(set){
				if(set.label == $(this).attr('name')){
					grabData = false;
					dataSets.push(set); //add set to active list
				}
			});
			if(grabData){
				$.ajax({
			        //This will retrieve the contents of the folder if the folder is configured as 'browsable'
			        url: "Api/Eeg/get/1/" + self.val(),
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

		dataSets.forEach(function(set){
			var dx = [];
		    var dy = [];

		    set.data.forEach(function(item) {
		        dx.push(item[0]);
		        dy.push(item[1]);
		    });

		    maxX = (Array.max(dx) > maxX) ? Array.max(dx) : maxX;
		    maxY = (Array.max(dy) > maxY) ? Array.max(dy) : maxY;

		    data.push({ data:set.data, label: set.label, lines:{show:true} })
		});

	    var options = {
	        series: { lines: { show: true }, shadowSize: 0 },
	        xaxis: { zoomRange: null, panRange: [0, maxX + 100] },
	        yaxis: { zoomRange: null, panRange: [0, maxY + 100] },
	        zoom: {
	            interactive: true
	        },
	        pan: {
	            interactive: true
	        }
	    };
	   var el = $("#eeg-chart");
	   
	   $.plot(el, data, options);

	   plotGSR();
	}

	function plotGSR(){
		$.ajax({
	        url: "Api/Gsr/get/1/",
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
});