<!DOCTYPE html>
<html>
<head>
	<title>Emotion Test</title>
	<meta charset="UTF-8" />
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href="jquery-ui.min.css" rel="stylesheet">
	<script src="jquery.min.js"></script>
	<script type="text/javascript">
	//All images

	var stack = [
		{url: "iaps/8492.jpg", arousal: 7.31, valence: 7.21, type: "Positive"},
		{url: "iaps/8185.jpg", arousal: 7.27, valence: 7.57, type: "Positive"},
		{url: "iaps/8030.jpg", arousal: 7.33, valence: 7.35, type: "Positive"},
		{url: "iaps/4290.jpg", arousal: 7.20, valence: 7.61, type: "Positive"},
		{url: "iaps/4220.jpg", arousal: 7.17, valence: 8.02, type: "Positive"},
		{url: "iaps/8080.jpg", arousal: 6.65, valence: 7.73, type: "Positive"},
		{url: "iaps/8501.jpg", arousal: 6.44, valence: 7.91, type: "Positive"},
		{url: "iaps/8190.jpg", arousal: 6.28, valence: 8.10, type: "Positive"},
		{url: "iaps/4660.jpg", arousal: 6.58, valence: 7.40, type: "Positive"},
		{url: "iaps/4659.jpg", arousal: 6.93, valence: 6.87, type: "Positive"},

		{url: "iaps/6350.jpg", arousal: 7.29, valence: 1.90, type: "Negative"},
		{url: "iaps/3000.jpg", arousal: 7.34, valence: 1.59, type: "Negative"},
		{url: "iaps/6230.jpg", arousal: 7.35, valence: 2.37, type: "Negative"},
		{url: "iaps/3080.jpg", arousal: 7.22, valence: 1.48, type: "Negative"},
		{url: "iaps/3170.jpg", arousal: 7.21, valence: 1.46, type: "Negative"},
		{url: "iaps/3010.jpg", arousal: 7.26, valence: 1.79, type: "Negative"},
		{url: "iaps/3060.jpg", arousal: 7.12, valence: 1.79, type: "Negative"},
		{url: "iaps/9410.jpg", arousal: 7.07, valence: 1.51, type: "Negative"},
		{url: "iaps/3500.jpg", arousal: 6.99, valence: 2.21, type: "Negative"},
		{url: "iaps/3530.jpg", arousal: 6.82, valence: 1.80, type: "Negative"},


		{url: "iaps/7175.jpg", arousal: 1.72, valence: 4.87, type: "Neutral"},
		{url: "iaps/7031.jpg", arousal: 2.03, valence: 4.52, type: "Neutral"},
		{url: "iaps/9360.jpg", arousal: 2.63, valence: 4.03, type: "Neutral"},
		{url: "iaps/7010.jpg", arousal: 1.76, valence: 4.94, type: "Neutral"},
		{url: "iaps/7110.jpg", arousal: 2.27, valence: 4.55, type: "Neutral"},
		{url: "iaps/5130.jpg", arousal: 2.51, valence: 4.45, type: "Neutral"},
		{url: "iaps/7060.jpg", arousal: 2.55, valence: 4.43, type: "Neutral"},
		{url: "iaps/2039.jpg", arousal: 3.46, valence: 3.65, type: "Neutral"},
		{url: "iaps/2440.jpg", arousal: 2.63, valence: 4.49, type: "Neutral"},
		{url: "iaps/7020.jpg", arousal: 2.17, valence: 4.97, type: "Neutral"},


	];
	var images = [];
	populateList();
	function populateList(){

		var rndIndex = Math.floor(Math.random() * stack.length);
		images.push(stack[rndIndex]);
		stack.splice(rndIndex, 1)
		
		if(stack.length > 0)
			populateList();
		else 
			images.push({url: "iaps/done.png", arousal: 0, valence: 0, type: ""});
	}

	
	

	var results = {startTime: 0, endTime: 0, time: 0, data: [] };
	var startTime; 
	var endTime;

	var currentImgIndex = 0;
	var time = 0;	
	var timerId; 
	var prevTime; 

	var TIME_TO_SAM = 1000; // 7000 def
	var RANDOM_TIME = 1; // 5 def
	var FIXED_TIME = 1000; // 20000 def
	var RELAX_TIME = 1000;


	function timeNow(){
		var d = new Date();
		var t = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes(), d.getSeconds(), d.getMilliseconds());
		return t;
	}

	function deltaTime(){
		return timeNow() - results.startTime;
	}

	function startTest(){
		$(".image-container > img").attr("src", "images/relax.jpg");
		var relaxIdx = setInterval(function(){
			$("#thebutton").show();
			var d = new Date();
			$("#nameBox").hide();
			startTime = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes(), d.getSeconds(), d.getMilliseconds());
			prevTime = startTime;
			results.startTime = startTime;
			$(".start").hide();
			$(".submit").click();
			clearInterval(relaxIdx);
		}, RELAX_TIME);
	}
	$( document ).ready(function() {
		$(".export").hide();
		$("#thebutton").hide();
		$(document).tooltip();	
		$(".result").hide();
		$(".sam").hide();

		$("#nameBox").on('keyup', function(){
			if($("#nameBox").text().length > 3){
				$(".start").attr("disabled", false);
				$(".start").removeClass("disabled");
			}
		})
		var testStarted = false;
		$(".start").on("click", function(){
			$(".start").attr("disabled", true).addClass("disabled").text("Please wait");
			var tstidx = setInterval(function(){
				$.ajax({
				    url:'/data-visualization-dat9/public/test/colrdy.txt',
				    type:'HEAD',
				    error: function()
				    {
				        console.log("File don't exist");
				    },
				    success: function()
				    {
				        console.log("File found!");
				        if(!testStarted){
				        	startTest();
				        	testStarted = true;
				        }

				        clearInterval(tstidx);
				    }
				});
			}, 30);
			
		})
		
		var typeS = ["Negative", "Positive", "Neutral"];
		var imageType = "";
		var timeShown = 0;
		$(".submit").on("click", function(){
			$(".tip").attr("title", "Du skal vælge aruosal/valence værdier inden du kan gå videre. Vælg ved at trykke på en cirkel-formet knap");
			
			$(".sam").slideUp();
			var d = new Date();  
			var timeClicked = deltaTime();
			if(currentActiveSamButton){
				currentActiveSamButton.removeClass("button--active");
				currentActiveSamButton = null;
			}
			if(currentActiveArousalButton){
				currentActiveArousalButton.removeClass("button--active");
				currentActiveArousalButton = null;	
			}


			$(".image-container > img").css("visibility", "hidden");
			if(currentImgIndex > 0)
				results.data.push({"img": $(".image-container > img").attr("src"), "image_type": images[currentImgIndex - 1].type, "valence": $(".valence-input").val(), "control_valence": images[currentImgIndex - 1].valence, "arousal": $(".arousal-input").val(), "control_arousal": images[currentImgIndex - 1].arousal, "time_image_shown": timeShown, "time_clicked_next": timeClicked });
			
			$(".submit").attr("disabled", true);
			$(".submit").addClass("disabled");
			var id = setInterval(function(){
				timeShown = deltaTime();
				var img = images[currentImgIndex].url;
				
				$(".image-container > img").attr("src", img);
				currentImgIndex++;
				clearInterval(id);
				//console.log("Positive: " + numP + " - Negative: " + numN + " - Neutral: " + numNeu);
				var watIdx = setInterval(function(){
					$(".image-container > img").css("visibility", "visible");					
					clearInterval(watIdx);
					

					if(currentImgIndex != images.length){
						var valSelIdx = setInterval(function(){
							$(".sam").slideDown();
							clearInterval(valSelIdx);
						}, TIME_TO_SAM);
					}
					if(currentImgIndex == images.length){
						$(".export").show();
						$.ajax({
				           type: "get",
				           url: "/data-visualization-dat9/public/saveTest/" + $("#nameBox > input").val() + "/" + JSON.stringify(results),
				           dataType: "json",
				           success: function (response) {
				               if(response.status == 200){
				               		$(".savedFile").addClass("ok").css("visibility", "visible");
				               		$(".savedFile").text(response.response);
				               }
				           },
				       });
					}

				}, 300)
			}, Math.floor(Math.floor(Math.random()* 1000 * RANDOM_TIME) + FIXED_TIME));
		})

		$(".export").on("click", function(){
			var d =  new Date();
			results.time = time;
			results.endTime = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes(), d.getSeconds(), d.getMilliseconds());  
			$(".result > textarea").text((JSON.stringify(results)));
			$(".result").fadeIn("fast");
		});

		$(".sambuttonparent").on("click", function(){
			TriggerValence($(this).find("button"));
		});
		$(".samvalencebuttons button").on("click", function(){
			TriggerValence($(this));
		});

		$(".arousalbuttonparent").on("click", function(){
			TriggerArousal($(this).find("button"));
		});
		$(".samarousalbuttons button").on("click", function(){
			TriggerArousal($(this));
		});

	})
	var currentActiveSamButton;
	var currentActiveArousalButton;

	function TriggerArousal(el){
		if($(el).val() != $(currentActiveArousalButton).val()){
			$(el).addClass("button--active");
			$(".arousal-input").val($(el).val());
			if(currentActiveArousalButton){
				currentActiveArousalButton.removeClass("button--active");
				currentActiveArousalButton = $(el);
			} else {
				currentActiveArousalButton = $(el);
			}
			if(currentActiveSamButton != undefined && currentActiveArousalButton != undefined){
				$(".submit").attr("disabled", false);
				$(".submit").removeClass("disabled");
				$(".tip").attr("title", "");
			}
		}
	}

	function TriggerValence(el){
		if($(el).val() != $(currentActiveSamButton).val()){
			
			$(el).addClass("button--active");
			$(".valence-input").val($(el).val());	
			if(currentActiveSamButton){
				currentActiveSamButton.removeClass("button--active");
				currentActiveSamButton = $(el);
			} else {
				currentActiveSamButton = $(el);
			}

			if(currentActiveSamButton != undefined && currentActiveArousalButton != undefined){
				$(".submit").attr("disabled", false);
				$(".submit").removeClass("disabled");
				$(".tip").attr("title", "");
			}
		}
	}
	/*function populateArray(){
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
	  			if(!first){
	  				controlArousalArr[0].push(item.split("\t")[2]);
	  				controlValenceArr[0].push(item.split("\t")[1]);
	  			}
	  			else
	  				first = false;
	  		});

	  	});

	  	$.get(dir + "H.txt").success(function(response){
	  		var newLines = response.split("\n");
	  		var first = true;
	  		newLines.forEach(function(item){
	  			if(!first){
	  				controlArousalArr[1].push(item.split("\t")[2]);
	  				controlValenceArr[1].push(item.split("\t")[1]);
	  			}
	  			else
	  				first = false;
	  		});

	  	});

	  	$.get(dir + "N.txt").success(function(response){
	  		var newLines = response.split("\n");
	  		var first = true;
	  		newLines.forEach(function(item){
	  			if(!first){
	  				controlArousalArr[2].push(item.split("\t")[2]);
	  				controlValenceArr[2].push(item.split("\t")[1]);
	  			}
	  			else
	  				first = false;
	  		});
	  	});
	  	$.get(dir + "P.txt").success(function(response){
	  		var newLines = response.split("\n");
	  		var first = true;
	  		newLines.forEach(function(item){
	  			if(!first){
	  				controlArousalArr[3].push(item.split("\t")[2]);
	  				controlValenceArr[3].push(item.split("\t")[1]);
	  			}
	  			else
	  				first = false;
	  		});
	  	});
	}
	*/
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
	margin-top: 5px;
	background-color: #808080;
	font-family: 'Open Sans', sans-serif;
	color: #808080;
}
.wrap {
	width: 1200px;
	margin: 0 auto;
}
.image-container{
	margin: 20px;
	border-radius: 10px;
	background-color: #fafafa;
	border: 1px solid #cdcdcd;
	width: 1150px;
	min-height: 300px;
	margin: 0 auto;
	padding: 2px;
}
.image-container > img{
	width: 640px;
	height: 480px;
	border-radius: 5px;
	position: relative;
	margin-left: 250px;
}
.valance-selection{
	width: 100%;
	margin: 0 auto;
	padding: 5px;
}
.button {
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
.button:hover:enabled{
	background: #66C1EA;
	background: -moz-linear-gradient(top,#66C1EA 0%,#188BC0 100%);
	background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#66C1EA),color-stop(100%,#188BC0));
	background: -webkit-linear-gradient(top,#66C1EA 0%,#188BC0 100%);
	background: -o-linear-gradient(top, #66C1EA 0%,#188BC0 100%);
	background: -ms-linear-gradient(top, #66C1EA 0%,#188BC0 100%);
	background: linear-gradient(top, #66C1EA 0%,#188BC0 100%);
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
.samvalencebuttons button, .samarousalbuttons button{
	border-radius: 50%;
	height: 30px;
	width: 30px;
	background: #eeeeee;
	background: -moz-linear-gradient(top,  #eeeeee 0%, #cccccc 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#eeeeee), color-stop(100%,#cccccc));
	background: -webkit-linear-gradient(top,  #eeeeee 0%,#cccccc 100%);
	background: -o-linear-gradient(top,  #eeeeee 0%,#cccccc 100%);
	background: -ms-linear-gradient(top,  #eeeeee 0%,#cccccc 100%);
	background: linear-gradient(to bottom,  #eeeeee 0%,#cccccc 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#eeeeee', endColorstr='#cccccc',GradientType=0 );
	border: 1px solid #cecece;
}
.samvalencebuttons > div, .samarousalbuttons > div {
	width: 120px;
	display: inline-block;
}
.samvalencebuttons > div >img, .samarousalbuttons > div > img {
	display: block;
	border: 1px solid #cecece;
}

.samvalencebuttons > div > button, .samarousalbuttons > div > button {
	margin: 5px auto;
	position: relative;
	display: block;
}
.button--active{
	background: #25A6E1 !important;
	background: -moz-linear-gradient(top,#25A6E1 0%,#188BC0 100%) !important;
	background: -webkit-gradient(linear,left top,left bottom,color-stop(0%,#25A6E1),color-stop(100%,#188BC0)) !important;
	background: -webkit-linear-gradient(top,#25A6E1 0%,#188BC0 100%) !important;
	background: -o-linear-gradient(top,#25A6E1 0%,#188BC0 100%) !important;
	background: -ms-linear-gradient(top,#25A6E1 0%,#188BC0 100%) !important;
	background: linear-gradient(top,#25A6E1 0%,#188BC0 100%) !important;
	filter: progid: DXImageTransform.Microsoft.gradient( startColorstr='#25A6E1',endColorstr='#188BC0',GradientType=0) !important;
	border: 1px solid #15719C;
}
label {
	font-size: 18px;
}
#nameBox {
	width: 100%;
	text-align: center;
	padding: 10px;
}
.savedFile {
	width: 400px;
	margin: 0 auto;
	padding: 20px;
	font-size: 18px;
	font-weight:bold;
	text-transform: uppercase;
	border-radius: 10px;
	text-align: center;
	position: absolute;
	z-index: 100;
	top: 50%;
	right: 38%;
	visibility: hidden;
}
.ok {
	border: 1px solid #104000;
	background-color: #A1D490;
	color: #D1FFC2;
}
</style>
</head>

<body>
<div class="wrap">
	<div class="image-container">
	<button class="start button disabled" style="width: 100%;" disabled>Start</button>
	<div id="nameBox"><label for="name">Navn: </label><input type="text" name="name" style="padding: 10px; width: 150px;border-radius: 5px;font-size:18px;font-weight:bold;color:#808080;"/></div>
		<img src="" width="640" height="480" data-control-valence="0" data-control-arousal="0" />
		<!--<div class="valance-selection" style="display:inline-block;">
			<div class="valence-buttons">
				<div class="samvalence"></div>
				<div class="samvalencebuttons">
					<button style="margin-left: 107px;" value="1"></button>
					<button style="margin-left: 15px;" value="2"></button>
					<button style="margin-left: 15px;" value="3"></button>
					<button style="margin-left: 15px;" value="4"></button>
					<button style="margin-left: 14px;" value="5"></button>
					<button style="margin-left: 15px;" value="6"></button>
					<button style="margin-left: 15px;" value="7"></button>
					<button style="margin-left: 15px;" value="8"></button>
					<button style="margin-left: 15px;" value="9"></button>
				</div>
				<div class="samarousal"></div>
				<div class="samarousalbuttons">
					
					<button style="margin-left: 105px;" value="1"></button>
					<button style="margin-left: 17px;" value="2"></button>
					<button style="margin-left: 15px;" value="3"></button>
					<button style="margin-left: 15px;" value="4"></button>
					<button style="margin-left: 14px;" value="5"></button>
					<button style="margin-left: 15px;" value="6"></button>
					<button style="margin-left: 15px;" value="7"></button>
					<button style="margin-left: 15px;" value="8"></button>
					<button style="margin-left: 15px;" value="9"></button>
				</div>				
			</div>-->
			<div class="sam" style="text-align:center;">
				<div class="samvalencebuttons">
					<input type="hidden" class="valence-input" value="0" />
					<?php
						for($i = 1; $i < 10; $i++){
					?>
					<div class="sambuttonparent">
						<img src="images/SAM/V<?=$i;?>.png" />
						<button value="<?=$i;?>"></button>
					</div>
					<?php
					}
					?>
				</div>
				<div class="samarousalbuttons">
					<input type="hidden" class="arousal-input" value="0" />
					<?php
						for($i = 1; $i < 10; $i++){
					?>
					<div class="arousalbuttonparent">
						<img src="images/SAM/A<?=$i;?>.png" />
						<button value="<?=$i;?>"></button>
					</div>
					<?php
					}
					?>
				</div>
			</div>
			<div class="tip" style="font-size:12px;" title="Du skal vælge aruosal/valence værdier inden du kan gå videre. Vælg ved at trykke på en cirkel-formet knap"><button class="submit button" id="thebutton" style="width:100%;">Next</button></div>
		</div>
	</div>
	<div class="savedFile ok">
	</div>
	<button class="export button" style="width: 200px; margin-top: 400px;">Export Data</button>
	<div class="result">
		<textarea style="width: 100%; height: 300px;"></textarea>
	</div>
</div>

<script src="jquery-ui.min.js"></script>
</body>
</html>