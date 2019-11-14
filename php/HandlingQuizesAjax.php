<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<script src="../js/jquery-3.4.1.min.js"></script>
	<script src="../js/AddQuestionsAjax.js"></script>
	<script src="../js/ShowQuestionsAjax.js"></script>
	<script src="../js/ShowImageInForm.js"></script>
	<?php include '../php/DbConfig.php' ?>
	<link rel="stylesheet" href="../styles/Style.css">
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		td, th {
			border: 1px solid black;
			padding: 8px;
		}

		th{
			background-color: #0066ff;
			color: white;
			text-align: center;
		}
		td{
			text-align: left;
		}

		tr:nth-child(even) {
			background-color: #dddddd;
		}
	</style>

</head>
<body>
  <?php include '../php/Menus.php' ?>
  <section style='overflow-y:scroll;' class="main" id="s1">
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
					echo "	<div>
								<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; text-align:center;'> 
									<p style='text-align:center;'>USUARIOS ONLINE</p><span id='numpersonas'></span>
								</div><br>
								<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; text-align:center;'> 
									<p style='text-align:center;'>MIS PREGUNTAS / TOTAL PREGUNTAS</p><span id='numpreguntas'></span>
								</div><br>
								<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; text-align:left;'> <p style='text-align:center;'>DATOS DE LA PREGUNTA</p>
								<form method='POST' id='fquestion' name='fquestion' enctype='multipart/form-data' accept-charset='UTF-8'>
									Email*: <input type='text' id='email' name='email' value='".$_GET['email']."' size=35 readonly><span id='eemail'></span><br>
									Enunciado de la pregunta*: <input type='text' id='enunciado' name='enunciado' size=55><span id='eenunciado'></span><br>
									Respuesta correcta*: <input type='text' id='correcta' name='correcta' size=55><span id='ecorrecta'></span><br>
									Respuesta incorrecta 1*: <input type='text' id='incorrecta1' name='incorrecta1' size=55><span id='eincorrecta1'></span><br>
									Respuesta incorrecta 2*: <input type='text' id='incorrecta2' name='incorrecta2' size=55><span id='eincorrecta2'></span><br>
									Respuesta incorrecta 3*: <input type='text' id='incorrecta3' name='incorrecta3' size=55><span id='eincorrecta3'></span><br>
									Complejidad de la pregunta*: 	<input type='radio' name='complejidad' value='1' id='complejidad' checked> Baja 
																	<input type='radio' name='complejidad' value='2' id='complejidad'> Media 
																	<input type='radio' name='complejidad' value='3' id='complejidad'> Alta <br>
									Tema de la pregunta*: <input type='text' id='tema' name='tema' size=55><span id='etema'></span><br>
									Imagen relacionada con la pregunta*: <input type='file' id='file' accept='image/*' name='foto' onchange='verImagen(event)'>
									<p style='text-align:center;'><input type='button' id='ver' name='ver' value='Ver Preguntas' onclick='mostrarTabla()'><input type='button' id='enviar' name='enviar' value='Insertar pregunta'><input type='button' id='reset' name='reset' value='Vaciar formulario'></p>	
								</form>
							</div><div id='vermensajes'></div><div id='vertabla'></div></div>";

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
