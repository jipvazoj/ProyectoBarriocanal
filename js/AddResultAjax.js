$(document).ready(function(){
	$("#guardar").click(function() {		
		var form = $("#fresul")[0];
        var data = new FormData(form);	
		$.ajax({		
			type:"POST",
			enctype: 'multipart/form-data',
			url: "AddResult.php",
			data: data,
			dataType: "text",
			cache:false,
			contentType: false,
			processData: false,
			success: function(mensaje){
				$("#nick").val('');
				$("#mensaje").empty();
				$('#mensaje').append(mensaje);

			}
		});
	});
});