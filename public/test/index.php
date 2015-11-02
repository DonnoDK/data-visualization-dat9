<!DOCTYPE html>
<html>
<head>
	<title>Emotion Test</title>
	<link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<link href="jquery-ui.min.css" rel="stylesheet">
	<script src="jquery.min.js"></script>
	<script type="text/javascript">
	//All images

	var images = [];
	images.push({url: "iaps/2981.jpg", arousal: 5.97, valence: 2.76, type: "Negative"});
	images.push({url: "iaps/3062.jpg", arousal: 5.78, valence: 1.87, type: "Negative"});
	images.push({url: "iaps/9040.jpg", arousal: 5.82, valence: 1.67, type: "Negative"});
	images.push({url: "iaps/9183.jpg", arousal: 6.58, valence: 1.69, type: "Negative"});
	images.push({url: "iaps/9325.jpg", arousal: 6.01, valence: 1.89, type: "Negative"});
	images.push({url: "iaps/4660.jpg", arousal: 6.58, valence: 7.40, type: "Positive"});
	images.push({url: "iaps/4680.jpg", arousal: 6.02, valence: 7.25, type: "Positive"});
	images.push({url: "iaps/5210.jpg", arousal: 4.60, valence: 8.03, type: "Positive"});
	images.push({url: "iaps/5825.jpg", arousal: 5.46, valence: 8.03, type: "Positive"});
	images.push({url: "iaps/8492.jpg", arousal: 7.31, valence: 7.21, type: "Positive"});
	images.push({url: "iaps/6314.jpg", arousal: 4.09, valence: 4.60, type: "Neutral"});
	images.push({url: "iaps/7484.jpg", arousal: 4.29, valence: 4.99, type: "Neutral"});
	images.push({url: "iaps/8121.jpg", arousal: 4.63, valence: 4.14, type: "Neutral"});
	images.push({url: "iaps/8466.jpg", arousal: 4.92, valence: 4.86, type: "Neutral"});
	images.push({url: "iaps/9171.jpg", arousal: 4.72, valence: 4.01, type: "Neutral"});
	images.push({url: "iaps/done.png", arousal: 0, valence: 0, type: "NA"});
	
	
	
	
	var results = {startTime: 0, endTime: 0, time: 0, data: [] };
	var startTime; 
	var endTime;

	var currentImgIndex = 0;
	var time = 0;	
	var timerId; 
	var prevTime; 

	function timeNow(){
		var d = new Date();
		var t = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes(), d.getSeconds(), d.getMilliseconds());
		return t;
	}

	function deltaTime(){
		return timeNow() - results.startTime;
	}
	$( document ).ready(function() {
		$(".result").hide();
		$(".valance-selection").hide();
		$(".start").on("click", function(){
			var d = new Date();
			startTime = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes(), d.getSeconds(), d.getMilliseconds());
			prevTime = startTime;
			results.startTime = startTime;
			$(".start").hide();
			$(".submit").click();
		})
		
		var typeS = ["Negative", "Positive", "Neutral"];
		var imageType = "";
		var timeShown = 0;
		$(".submit").on("click", function(){
			$(".valance-selection").slideUp();
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
				results.data.push({"img": $(".image-container > img").attr("src"), "image_type": images[currentImgIndex].type, "valence": $(".valence-input").val(), "control_valence": images[currentImgIndex].valence, "arousal": $(".arousal-input").val(), "control_arousal": images[currentImgIndex].arousal, "time_image_shown": timeShown, "time_clicked_next": timeClicked });
			
			$(".submit").attr("disabled", true);
			$(".submit").addClass("disabled");
			var id = setInterval(function(){
				timeShown = deltaTime();
				var img = images[currentImgIndex].url;
				
				$(".image-container > img").attr("src", img);
				currentImgIndex++;
				//console.log("Positive: " + numP + " - Negative: " + numN + " - Neutral: " + numNeu);
				var watIdx = setInterval(function(){
					$(".image-container > img").css("visibility", "visible");
					$(".submit").attr("disabled", false);
					$(".submit").removeClass("disabled");					
					clearInterval(watIdx);
					clearInterval(id);

					var valSelIdx = setInterval(function(){
						$(".valance-selection").slideDown();
						clearInterval(valSelIdx);
					}, 4000);

				}, 300)
			}, Math.floor(Math.floor(Math.random()* 1000 * 5)));
		})

		$(".export").on("click", function(){
			var d =  new Date();
			results.time = time;
			results.endTime = Date.UTC(d.getFullYear(), d.getMonth(), d.getDate(), d.getHours(), d.getMinutes(), d.getSeconds(), d.getMilliseconds());  
			$(".result > textarea").text((JSON.stringify(results)));
			$(".result").fadeIn("fast");
		});

		var currentActiveSamButton;
		$(".samvalencebuttons button").on("click", function(){
			$(this).addClass("button--active");
			$(".valence-input").val($(this).val());	
			if(currentActiveSamButton){
				currentActiveSamButton.removeClass("button--active");
				currentActiveSamButton = $(this);
			} else {
				currentActiveSamButton = $(this);
			}
		});

		var currentActiveArousalButton;
		$(".samarousalbuttons button").on("click", function(){
			$(this).addClass("button--active");
			$(".arousal-input").val($(this).val());
			if(currentActiveArousalButton){
				currentActiveArousalButton.removeClass("button--active");
				currentActiveArousalButton = $(this);
			} else {
				currentActiveArousalButton = $(this);
			}
		});

	})
	
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
.button:hover{
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
.samvalence {
	background: url('images/samvalence.png') no-repeat;
	transform: scaleX(-1);
	width: 498px;
	height: 102px;
	margin: 0 auto;
}
.samarousal {
	background: url('images/samarousal.png') no-repeat;
	width: 498px;
	transform: scaleX(-1);
	height: 102px;
	margin: 0 auto;
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
</style>
</head>

<body>
<div class="wrap">
	<div class="image-container">
	<button class="start button" style="width: 100%;">Start</button>
		<img src="" width="640" height="480" data-control-valence="0" data-control-arousal="0" />
		<div class="valance-selection" style="display:inline-block;">
			<div class="valence-buttons">
				<div class="samvalence"></div>
				<div class="samvalencebuttons">
					<input type="hidden" class="valence-input" value="0" />
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
					<input type="hidden" class="arousal-input" value="0" />
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
			</div>
			<button class="submit button">Next</button>
		</div>
	</div>
	<button class="export button" style="width: 200px; margin-top: 400px;">Export Data</button>
	<div class="result">
		<textarea style="width: 100%; height: 300px;"></textarea>
	</div>
</div>

<script src="jquery-ui.min.js"></script>
</body>
</html>