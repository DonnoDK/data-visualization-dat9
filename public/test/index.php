<!DOCTYPE html>
<html>
<head>
	<title>Emotion Test</title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href="jquery-ui.min.css" rel="stylesheet">
	<script src="jquery.min.js"></script>
	<script type="text/javascript">
	//All images
	var imageArr = [];
	imageArr[0] = [];
	imageArr[1] = [];
	imageArr[2] = [];
	imageArr[3] = [];
	var controlValenceArr = [];
	controlValenceArr[0] = [];
	controlValenceArr[1] = [];
	controlValenceArr[2] = [];
	controlValenceArr[3] = [];
	
	var results = [];
	var SHOW_IMAGES = 5;

	var numP = 0;
	var numN = 0;
	var numNeu = 0;

	var time = 0;	
	var timerId; 
	$( document ).ready(function() {
		populateArray();
		$(".result").hide();
		$(".start").on("click", function(){
			timerId = setInterval(function(){
				time += 15;
				//console.log(time);
			}, 15);
			$(".start").hide();
			$(".submit").click();
		})

		
		var typeS = ["Negative", "Positive", "Neutral"];
		var timeShown = 0;
		var imageType = "";
		$(".submit").on("click", function(){

			results.push({"img": $(".image-container > img").attr("src"), "image_type": imageType, "valence": $(".valence-input").val(), "control_valence": $(".image-container > img").data("control-valence"),"time_image_shown": timeShown, "time_clicked_next": time });
			$(".submit").attr("disabled", true);
			$(".submit").addClass("disabled");
			var id = setInterval(function(){
				var type = typeS[Math.floor(Math.random() * typeS.length)];
				imageType = type;
				timeShown = time;
				var img = "";
				var imgNum = 0;
				if(type == "Negative"){
					var negativeNum = Math.floor(Math.random() * 2);
					switch(negativeNum){
						case 0:
							imgNum = Math.floor((Math.random() * imageArr[0].length));
							$(".image-container > img").data("control-valence", controlValenceArr[0][imgNum]);
							img = "gaped/A/" + imageArr[0][imgNum];
							numN++;
							break;
						case 1:
							imgNum = Math.floor((Math.random() * imageArr[1].length));
							$(".image-container > img").data("control-valence", controlValenceArr[1][imgNum]);
							img = "gaped/H/" + imageArr[1][imgNum];	
							numN++;
							break;
					}
					if(numN == SHOW_IMAGES)
						typeS.splice(typeS.indexOf("Negative"), 1);

				} else if(type == "Positive"){
					imgNum = Math.floor((Math.random() * imageArr[3].length));
					$(".image-container > img").data("control-valence", controlValenceArr[3][imgNum]);
					img = "gaped/P/" + imageArr[3][imgNum];
					numP++;	
					if(numP == SHOW_IMAGES)
						typeS.splice(typeS.indexOf("Positive"), 1);
				} else if(type == "Neutral") {
					imgNum = Math.floor((Math.random() * imageArr[2].length));
					$(".image-container > img").data("control-valence", controlValenceArr[2][imgNum]);
					img = "gaped/N/" + imageArr[2][imgNum];
					numNeu++;
					if(numNeu == SHOW_IMAGES)
						typeS.splice(typeS.indexOf("Neutral"), 1);
				} else {
					img = "http://nicolasemple.com/wp-content/uploads/2013/12/well-done.jpg";
					clearInterval(timerId);
					$(".submit").hide();
				}
				$(".image-container > img").attr("src", img);

				$(".valence-input").val(50);
				$(".submit").attr("disabled", false);
				$(".submit").removeClass("disabled");
				//console.log("Positive: " + numP + " - Negative: " + numN + " - Neutral: " + numNeu);
				clearInterval(id);
			}, Math.floor(Math.random() * 3000 + 1));
		})

		$(".export").on("click", function(){
			$(".result > textarea").text((JSON.stringify(results)));
			$(".result").fadeIn("fast");
		});

		$(".slider").slider({
			range: "min",
			min: 1,
			max: 100,
			value: 50,
			slide: function(event, ui){
				$(".valence-num").text(ui.value);
				$(".valence-input").val(ui.value);
			}
		});
	})
	
	function populateArray(){
		var dir = "gaped/";
		$.ajax({
		    //This will retrieve the contents of the folder if the folder is configured as 'browsable'
		    url: dir + "A",
		    success: function (data) {
				$(data).find('a:contains(".bmp")').each(function () {
		           imageArr[0].push(this.href.replace(window.location.host, "").replace("http:///", "").replace("test/", ""));
	       		});
		    }     
	  	});

		$.ajax({
		    //This will retrieve the contents of the folder if the folder is configured as 'browsable'
		    url: dir + "H",
		    success: function (data) {
				$(data).find('a:contains(".bmp")').each(function () {
		           imageArr[1].push(this.href.replace(window.location.host, "").replace("http:///", "").replace("test/", ""));
	       		});
		    }     
	  	});
		$.ajax({
		    //This will retrieve the contents of the folder if the folder is configured as 'browsable'
		    url: dir + "N",
		    success: function (data) {
				$(data).find('a:contains(".bmp")').each(function () {
		           imageArr[2].push(this.href.replace(window.location.host, "").replace("http:///", "").replace("test/", ""));
	       		});
		    }     
	  	});
		$.ajax({
		    //This will retrieve the contents of the folder if the folder is configured as 'browsable'
		    url: dir + "P",
		    success: function (data) {
				$(data).find('a:contains(".bmp")').each(function () {
		           imageArr[3].push(this.href.replace(window.location.host, "").replace("http:///", "").replace("test/", ""));
	       		});
		    }     
	  	});

	  	$.get(dir + "A.txt").success(function(response){
	  		var newLines = response.split("\n");
	  		var first = true;
	  		newLines.forEach(function(item){
	  			if(!first)
	  				controlValenceArr[0].push(item.split("\t")[1]);
	  			else
	  				first = false;
	  		});

	  	});

	  	$.get(dir + "H.txt").success(function(response){
	  		var newLines = response.split("\n");
	  		var first = true;
	  		newLines.forEach(function(item){
	  			if(!first)
	  				controlValenceArr[1].push(item.split("\t")[1]);
	  			else
	  				first = false;
	  		});

	  	});

	  	$.get(dir + "N.txt").success(function(response){
	  		var newLines = response.split("\n");
	  		var first = true;
	  		newLines.forEach(function(item){
	  			if(!first)
	  				controlValenceArr[2].push(item.split("\t")[1]);
	  			else
	  				first = false;
	  		});
	  	});
	  	$.get(dir + "P.txt").success(function(response){
	  		var newLines = response.split("\n");
	  		var first = true;
	  		newLines.forEach(function(item){
	  			if(!first)
	  				controlValenceArr[3].push(item.split("\t")[1]);
	  			else
	  				first = false;
	  		});
	  	});
	}

	function pad(n, width, z) {
	  z = z || '0';
	  n = n + '';
	  return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
	}


	</script>
<style type="text/css">
body {
	padding: 0;
	margin: 0;
	margin-top: 25px;
	background-color: #808080;
	font-family: 'Open Sans', sans-serif;
	color: #808080;
}
.wrap {
	width: 900px;
	margin: 0 auto;
}
.image-container{
	margin: 20px;
	border-radius: 10px;
	background-color: #fafafa;
	border: 1px solid #cdcdcd;
	width: 640px;
	min-height: 300px;
	margin: 0 auto;
	padding: 15px;
}
.image-container > img{
	width: 640px;
	height: 480px;
	border-radius: 5px;
}
.valance-selection{
	width: 100%;
	margin: 0 auto;
	padding: 10px;
}
button {
	background: #25A6E1;
	background: -moz-linear-gradient(top,#25A6E1 0%,#188BC0 100%);
	background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#25A6E1),color-stop(100%,#188BC0));
	background: -webkit-linear-gradient(top,#25A6E1 0%,#188BC0 100%);
	background: -o-linear-gradient(top,#25A6E1 0%,#188BC0 100%);
	background: -ms-linear-gradient(top,#25A6E1 0%,#188BC0 100%);
	background: linear-gradient(top,#25A6E1 0%,#188BC0 100%);
	filter: progid: DXImageTransform.Microsoft.gradient( startColorstr='#25A6E1',endColorstr='#188BC0',GradientType=0);
	padding:8px 13px;
	color:#fff;
	font-family:'Helvetica Neue',sans-serif;
	font-size:17px;
	border-radius:4px;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	border:1px solid #1A87B9;
	padding: 5px;
	width: 75px;
	margin: 10px 0px 0px 0px;
}  
button:hover{
	background: #66C1EA;
	background: -moz-linear-gradient(top,#66C1EA 0%,#188BC0 100%);
	background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#66C1EA),color-stop(100%,#188BC0));
	background: -webkit-linear-gradient(top,#66C1EA 0%,#188BC0 100%);
	background: -o-linear-gradient(top,#66C1EA 0%,#188BC0 100%);
	background: -ms-linear-gradient(top,#66C1EA 0%,#188BC0 100%);
	background: linear-gradient(top,#66C1EA 0%,#188BC0 100%);
}
.disabled {
	border: 1px solid #252525;
	background: #cecece;
	background: -moz-linear-gradient(top,#cecece 0%,#808080 100%);
	background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#66C1EA),color-stop(100%,#188BC0));
	background: -webkit-linear-gradient(top,#cecece 0%,#808080 100%);
	background: -o-linear-gradient(top,#cecece 0%,#808080 100%);
	background: -ms-linear-gradient(top,#cecece 0%,#808080 100%);
	background: linear-gradient(top,#cecece 0%,#808080 100%) !important;
}
.result{
	margin: 20px;
	border-radius: 10px;
	background-color: #fafafa;
	border: 1px solid #cdcdcd;
	width: 640px;
	min-height: 300px;
	margin: 0 auto;
	padding: 15px;
}
</style>
</head>
<body>
<div class="wrap">
	<div class="image-container">
	<button class="start" style="width: 100%;">Start</button>
		<img src="" width="640" height="480" data-control-valence="0" />
		<div class="valance-selection" style="display:inline-block;">
			<div style="margin: 5px 0px 5px 0px;">Valence: <span class="valence-num">50</span><input class="valence-input" type="hidden" maxlength="3" /></div>
				<div class="slider" style="width: 100%;"></div> 	<button class="submit">Next</button>
		</div>
	</div>
	<button class="export" style="width: 200px; margin-top: 200px;">Export Data</button>
	<div class="result">
		<textarea style="width: 100%; height: 300px;"></textarea>
	</div>
</div>
<script src="jquery-ui.min.js"></script>
</body>
</html>