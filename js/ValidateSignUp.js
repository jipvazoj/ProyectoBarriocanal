$(document).ready(function(){
	var activarmail = false;
	var activarpass = false;
	
	$('#email').keyup(function(){
		var email = $('#email').val();
		if(email!=""){
			$.ajax({		
				type:"POST",
				url: "ClientVerifyEnrollment.php?email="+email,
				cache:false,
				success: function(mensaje){
					if(mensaje=="SI"){
						activarmail=true;
						$('#emailvip').html("<span style='color:green;'>&#9989; EL EMAIL ES VIP</span>");
						/*
						if(activarmail && activarpass){
							$('#enviar').prop("disabled", true);
						}*/
					}else{
						activarmail=false;
						$('#emailvip').html("<span style='color:red;'>&#10060; EL EMAIL NO ES VIP</span>");
					}
					
					if(activarmail && activarpass){
						$('#enviar').prop("disabled", false);
					}else{
						$('#enviar').prop("disabled", true);
					}
				}
			});
		}
	});
		
	$('#pass1').change(function(){
		var pass = $('#pass1').val();
		var ticket = 1010;
		if(pass!=""){
			$.ajax({		
				type:"POST",
				url: "ClientVerifyPass.php",
				cache:false,
				data:{'pass':pass,'ticket':ticket},
				success: function(mensaje){
					if(mensaje=="VALIDA"){
						activarpass=true;
						$('#passsegura').html("<span style='color:green;'>&#9989; LA CONTRASEÑA ES SEGURA</span>");
					}else{
						activarpass=false;
						$('#passsegura').html("<span style='color:red;'>&#10060; LA CONTRASEÑA NO ES SEGURA</span>");
					}
					
					if(activarmail && activarpass){
						$('#enviar').prop("disabled", false);
					}else{
						$('#enviar').prop("disabled", true);
					}
				}
			});
		}
	});

	$('#email').keyup(function () {
		var input = $('#email').val();
		var eralumno = /^[a-z]+[0-9]{3}@ikasle\.ehu\.(eus|es)$/;
		var erprofesor = /^[a-z]+\.*[a-z]+@ehu\.(eus|es)$/;
		if(input.length==0){
			$('#eemail').html("<span style='color:red;'>&#10060; El campo email no puede estar vacío.</span>");
			return false;
		}
		else if(!(eralumno.test(input)) && !(erprofesor.test(input))){
			$('#eemail').html("<span style='color:red;'>&#10060; El campo email no tiene el formato de EHU.</span>");
			return false;
		}
		else{
			$('#eemail').html("<span style='color:green;'>&#9989; El email cumple el formato.</span>");
			return true;
		}
	});

	$('#nombre').keyup(function () {
		var input = $('#nombre').val();
		if(input.length==0){
			$('#enombre').html("<span style='color:red;'>&#10060; El campo enunciado no puede estar vacío.</span>");
			return false;
		}else{
			$('#enombre').html("<span style='color:green;'>&#9989;</span>");
			return true;
		}
	});

	$('#pass1').keyup(function () {
		var input = $('#pass1').val();
		if(input.length==0){
			$('#epass1').html("<span style='color:red;'>&#10060; Este campo no puede estar vacío.</span>");
			return false;
		}else if(input.length < 6){
			$('#epass1').html("<span style='color:red;'>&#10060; La contraseña es demasiado corta.</span>");
			return false;
		}else{
			$('#epass1').html("<span style='color:green;'>&#9989;</span>");
			return true;
		}
	});

	/*$('#incorrecta2').keyup(function () {
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
	
	
	$("form").submit(function (e) {
		var inputemail = $('#email').val();
		var eralumno = /^[a-z]+[0-9]{3}@ikasle\.ehu\.(eus|es)$/;
		var erprofesor = /^[a-z]+\.*[a-z]+@ehu\.(eus|es)$/;
		var error = false;
		var mensajeerror="Se han encontrado los siguientes errores:\n";
		if(inputemail.length==0){
			error = true;
			mensajeerror=mensajeerror+'-El campo email no puede estar vacío.\n';
			e.preventDefault();
		}
		else if(!(eralumno.test(inputemail)) && !(erprofesor.test(inputemail))){
			error = true;
			mensajeerror=mensajeerror+'-El campo email no tiene el formato de EHU.\n';
			e.preventDefault();
		}
		var inputenunciado = $('#enunciado').val();
		if(inputenunciado.length==0){
			error = true;
			mensajeerror=mensajeerror+'-El campo enunciado no puede estar vacío.\n';
			e.preventDefault();
		}else if(inputenunciado.length<10){
			error = true;
			mensajeerror=mensajeerror+'-El campo enunciado no puede tener menos de 10 caracteres.\n';
			e.preventDefault();
		}
		var inputcorrecta = $('#correcta').val();
		if(inputcorrecta.length==0){
			error = true;
			mensajeerror=mensajeerror+'-El campo respuesta correcta no puede estar vacío.\n';
			e.preventDefault();
		}
		var inputincorrecta1 = $('#incorrecta1').val();
		if(inputincorrecta1.length==0){
			error = true;
			mensajeerror=mensajeerror+'-El campo respuesta incorrecta 1 no puede estar vacío.\n';
			e.preventDefault();
		}
		var inputincorrecta2 = $('#incorrecta2').val();
		if(inputincorrecta2.length==0){
			error = true;
			mensajeerror=mensajeerror+'-El campo respuesta incorrecta 2 no puede estar vacío.\n';
			e.preventDefault();
		}
		var inputincorrecta3 = $('#incorrecta3').val();
		if(inputincorrecta3.length==0){
			error = true;
			mensajeerror=mensajeerror+'-El campo respuesta incorrecta 3 no puede estar vacío.\n';
			e.preventDefault();
		}
		var inputtema = $('#tema').val();
		if(inputtema.length==0){
			error = true;
			mensajeerror=mensajeerror+'-El campo tema no puede estar vacío.\n';
			e.preventDefault();
		}
		var inputtema = $('#file').val();
		if($('#file').get(0).files.length == 0){
			error = true;
			mensajeerror=mensajeerror+'-El campo foto no puede estar vacío.\n';
			e.preventDefault();
		}
		if(error){
			alert(mensajeerror);
		}
	});*/
});

