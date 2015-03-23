$(function(){
	$(".btn").on("click",function(){
		dat = $(this).val();
		id = $(this).attr("id");
		id_status = "status_"+id;
		$.ajax({
			async : true,
			beforeSend : function(request){
				$("#"+id_status).text("Scraping Data for this Country");
			},
			complete: function(){
				//alert( "The request is complete!" );
			}, 
			data : {"asd":dat}, 
			dataType : "text",
			error: function( xhr, status, errorThrown ) {
				alert( "Sorry, there was a problem!" );
				console.log( "Error: " + errorThrown );
				console.log( "Status: " + status );
				console.dir( xhr );
			},
			success : function(response) {
				//alert(response);
				if (response === "1"){
					$("#"+id_status).text("Data for this Country is scraped successfully");
					$("#"+id_status).css("color","green");
				}
				else if (response === "2"){
					$("#"+id_status).text("Could not open file for writing. Please check the file permissions and try again.");
					$("#"+id_status).css("color","red");
				}
				else if (response === "3"){
					$("#"+id_status).text("Errors while scraping. Could not read the website content. Please try again.");
					$("#"+id_status).css("color","red");
				}
				else {
					$("#"+id_status).text("Error Occured. Please try again.");
					$("#"+id_status).css("color","red");
				}
			},
			type : "POST",
			url : "1.php",
			contentType : "application/x-www-form-urlencoded; charset=UTF-8"
		});
	});


	$("#download").on("click",function(){
		$.ajax({
			
		});
	});
});