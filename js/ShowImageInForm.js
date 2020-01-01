var verImagen = function(event) {
	$('#showfile').html("<br><img id='imagen' name='imagen' width='100' src=''/>");
	var src = URL.createObjectURL(event.target.files[0]);
	$("#imagen").attr("src", src);
	$("imagen").show();
};