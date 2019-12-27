$(document).ready(function(){
	var activarmail = false;
	var activarpass = false;
		
	$('#pass1').keyup(function(){
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
						$('#passsegura').html("<span style='color:green;'>&#9989; LA CONTRASEÑA ES SEGURA</span>");
					}else{
						$('#passsegura').html("<span style='color:red;'>&#10060; LA CONTRASEÑA NO ES SEGURA</span>");
					}
				}
			});
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

	
});

