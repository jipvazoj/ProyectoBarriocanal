<!DOCTYPE html>
<html>
<head>
  <?php include '../html/Head.html'?>
  <script src="../js/jquery-3.4.1.min.js"></script>
  <script src="../js/ValidateFieldsQuestion.js"></script>
  <script src="../js/ShowImageInForm.js"></script>
	<?php include '../php/DbConfig.php' ?>
</head>
<body>
  <?php include '../php/Menus.php' ?>
  <section class="main" id="s1">
	<?php
		if(!isset($_GET['email'])){
			echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página se necesita haber iniciado sesión.</div>";
		}else{
			$conexion = mysqli_connect($server, $user, $pass, $basededatos);
			// Check connection
			if (!$conexion) {
				die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
			}

			$sql = "SELECT * FROM usuarios";
			$query = mysqli_query($conexion, $sql);

			if (mysqli_num_rows($query) > 0) {
				
				$encontrado = 0;
				while($row = mysqli_fetch_assoc($query)){
					if(strcmp($row['email'],$_GET['email'])==0){
						$encontrado=1;
						break;	
					}
				}
				
				if($encontrado){
					echo "	<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; text-align:left;'> <p style='text-align:center;'>DATOS DE LA PREGUNTA</p>
								<form method='POST' action='AddQuestionWithImage.php?email=".$_GET['email']."' id='fquestion' name='fquestion' enctype='multipart/form-data' accept-charset='UTF-8'>
									Email*: <input type='text' id='email' name='email' size=35><span id='eemail'></span><br>
									Enunciado de la pregunta*: <input type='text' id='enunciado' name='enunciado' size=55 value='".$_GET['email']."' readonly><span id='eenunciado'></span><br>
									Respuesta correcta*: <input type='text' id='correcta' name='correcta' size=55><span id='ecorrecta'></span><br>
									Respuesta incorrecta 1*: <input type='text' id='incorrecta1' name='incorrecta1' size=55><span id='eincorrecta1'></span><br>
									Respuesta incorrecta 2*: <input type='text' id='incorrecta2' name='incorrecta2' size=55><span id='eincorrecta2'></span><br>
									Respuesta incorrecta 3*: <input type='text' id='incorrecta3' name='incorrecta3' size=55><span id='eincorrecta3'></span><br>
									Complejidad de la pregunta*: 	<input type='radio' name='complejidad' value='1' id='complejidad' checked> Baja 
																	<input type='radio' name='complejidad' value='2' id='complejidad'> Media 
																	<input type='radio' name='complejidad' value='3' id='complejidad'> Alta <br>
									Tema de la pregunta*: <input type='text' id='tema' name='tema' size=55><span id='etema'></span><br>
									Imagen relacionada con la pregunta*: <input type='file' id='file' accept='image/*' name='foto' onchange='verImagen(event)'>
									<p style='text-align:center;'><input type='submit' id='enviar' name='enviar' value='Enviar'></p>	
								</form>
							</div>";

				}else{
					echo"<div style='color:white; background-color:#ff0000'>El usuario no está registrado.</div>";
				}
				mysqli_close($conexion);
				
			}else{
				echo"<div style='color:white; background-color:#ff0000'>El usuario no está registrado.</div>";
			}	
		}
	?>	
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
