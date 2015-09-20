$(document).ready(function () {
	$("nav a").on("click", function(){
		$.get("pages/" + $(this).data("content") + ".html", function(response){
			$("#content").html(response);
		});
	});

	$.get("updated-ts.txt", function(response){
		$("#version").text(response);
	});
});