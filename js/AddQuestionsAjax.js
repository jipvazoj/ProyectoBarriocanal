function mostrarTabla2(){
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			mostrar(this);
		}
	};
	xhttp.open("GET", "../xml/Questions.xml", true);
	xhttp.send();
	function mostrar(xml){
		document.getElementById("vertabla").innerHTML='';//empty
		var i;
		var xmlDoc = xml.responseXML;
		var tabla="<table border=1><thead><tr><th>Author</th><th>Enunciado</th><th>Respuesta Correcta</th></tr></thead>";
		var x = xmlDoc.documentElement.getElementsByTagName("assessmentItem");
		for (i = 0; i <x.length; i++) { 
			tabla += "<tr><td>" +
			x[i].getAttribute('author') +
			"</td><td>" +
			x[i].getElementsByTagName('p')[0].childNodes[0].nodeValue +
			"</td><td>" +
			x[i].getElementsByTagName('value')[0].childNodes[0].nodeValue +
			"</td></tr>";
		}
		tabla+="</table>";//append tabla
		document.getElementById('vertabla').innerHTML=tabla;
	}
}

$(document).ready(function(){
	$("#enviar").click(function() {		
		var form = $("#fquestion")[0];
        var data = new FormData(form);	
		$.ajax({		
			type:"POST",
			enctype: 'multipart/form-data',
			url: "AddQuestionXMLDB.php",
			data: data,
			dataType: "text",
			cache:false,
			contentType: false,
			processData: false,
			success: function(mensaje){
				$("#enunciado").val('');
				$("#eenunciado").text('');
				$("#correcta").val('');
				$("#ecorrecta").text('');
				$("#incorrecta1").val('');
				$("#eincorrecta1").text('');
				$("#incorrecta2").val('');
				$("#eincorrecta2").text('');
				$("#incorrecta3").val('');
				$("#eincorrecta3").text('');
				$("#tema").val('');
				$("#etema").text('');
				$("#complejidad").val(['1']);
				$("#file").val('');
				$("#imagen").attr("src", '');
				$('#vermensajes').empty();	
				$('#vertabla').empty();
				$('#vermensajes').append(mensaje);
				$('#vermensajes').append(data);
				if(data != ""){
					setTimeout(function(){mostrarTabla2();}, 200);
				}
			}
		});
		
	});
	$("#reset").click(function (event) {
		$("#enunciado").val('');
		$("#eenunciado").text('');
		$("#correcta").val('');
		$("#ecorrecta").text('');
		$("#incorrecta1").val('');
		$("#eincorrecta1").text('');
		$("#incorrecta2").val('');
		$("#eincorrecta2").text('');
		$("#incorrecta3").val('');
		$("#eincorrecta3").text('');
		$("#tema").val('');
		$("#etema").text('');
		$("#complejidad").val(['1']);
		$("#file").val('');
		$("#imagen").attr("src", '');
		$('#vermensajes').empty();
		$('#vertabla').empty();
	});
	$('#email').keyup(function () {
		var input = $('#email').val();
		var eremail = /\S+@\S+\.\S+/;
		if(input.length==0){
			$('#eemail').html("<span style='color:red;'>&#10060; El campo email no puede estar vacío.</span>");
			return false;
		}
		else if(!(eremail.test(input))){
			$('#eemail').html("<span style='color:red;'>&#10060; El campo email no tiene el formato de EHU.</span>");
			return false;
		}
		else{
			$('#eemail').html("<span style='color:green;'>&#9989;</span>");
			return true;
		}
	});

	$('#enunciado').keyup(function () {
		var input = $('#enunciado').val();
		if(input.length==0){
			$('#eenunciado').html("<span style='color:red;'>&#10060; El campo enunciado no puede estar vacío.</span>");
			$('#enviar').attr("disabled", true);
			return false;
		}else if(input.length<10){
			$('#eenunciado').html("<span style='color:red;'>&#10060; El campo enunciado no puede tener menos de 10 caracteres.</span>");
			$('#enviar').attr("disabled", true);
			return false;
		}else{
			$('#eenunciado').html("<span style='color:green;'>&#9989;</span>");
			$('#enviar').attr("disabled", false);
			return true;
		}
	});

	$('#correcta').keyup(function () {
		var input = $('#correcta').val();
		if(input.length==0){
			$('#ecorrecta').html("<span style='color:red;'>&#10060; Este campo no puede estar vacío.</span>");
			return false;
		}else{
			$('#ecorrecta').html("<span style='color:green;'>&#9989;</span>");
			return true;
		}
	});

	$('#incorrecta1').keyup(function () {
		var input = $('#incorrecta1').val();
		if(input.length==0){
			$('#eincorrecta1').html("<span style='color:red;'>&#10060; Este campo no puede estar vacío.</span>");
			return false;
		}else{
			$('#eincorrecta1').html("<span style='color:green;'>&#9989;</span>");
			return true;
		}
	});

	$('#incorrecta2').keyup(function () {
		var input = $('#incorrecta2').val();
		if(input.length==0){
			$('#eincorrecta2').html("<span style='color:red;'>&#10060; Este campo no puede estar vacío.</span>");
			return false;
		}else{
			$('#eincorrecta2').html("<span style='color:green;'>&#9989;</span>");
			return true;
		}
	});

	$('#incorrecta3').keyup(function () {
		var input = $('#incorrecta3').val();
		if(input.length==0){
			$('#eincorrecta3').html("<span style='color:red; text-align:right;'>&#10060; Este campo no puede estar vacío.</span>");
			return false;
		}else{
			$('#eincorrecta3').html("<span style='color:green; text-align:right;'>&#9989;</span>");
			return true;
		}
	});

	$('#tema').keyup(function () {
		var input = $('#tema').val();
		if(input.length==0){
			$('#etema').html("<span style='color:red;'>&#10060; El tema no puede estar vacío.</span>");
			return false;
		}else{
			$('#etema').html("<span style='color:green;'>&#9989;</span>");
			return true;
		}
	});
});