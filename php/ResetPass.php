<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<?php include '../php/DbConfig.php' ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="../js/ValidateLogIn.js"></script>
    <link rel="icon" type="image/png" href="../images/icon/favicon-32x32.png" sizes="32x32" />
</head>
<body>
  <section class="main" id="s1">
  	<?php
		if(!isset($_SESSION)){
			session_start();
		}
		echo "	<div>
				<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; width:70%; margin-left:auto; margin-right:auto; text-align:left; padding: 1em;'> <p style='text-align:center;'>RESTABLECER CONTRASEÑA</p><br>
				<form method='POST' action='ResetPass.php' id='fquestion' name='fquestion' enctype='multipart/form-data' accept-charset='UTF-8'>
					Email de la cuenta*: <br><input type='text' id='email' name='email' size=35><span id='eemail'></span><br><br>
					<p><input type='submit' id='enviar' name='enviar' value='Enviar'></p>	
				</form>
					";
		
	?>	
		<?php
			if(isset($_POST['email'])){
				//comprobar si está en la BBDD
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
							if(strcmp($row['email'],$_POST['email'])==0){
								$nombre = $row['nombre'];
								$estado = $row['estado'];
								$encontrado=1;
								break;	
							}
						}
						if($encontrado&&$estado==1){
							$receptor = $_POST['email'];
							$codigo = rand(100000,999999);
							$asunto = "[QUIZ SW] Restablecer contraseña";
							$headers="MIME-VERSION: 1.0"."\r\n";
							$headers.="Content-type:text/html;charset=UTF-8"."\r\n";
							$mensaje = "<html><head><title>Restablecer contraseña</title></head>
										<body><h2>Proceso de restablecimiento de contraseña:</h2>
											<ul><li>Entrar en el <a href='https://sw19lab0.000webhostapp.com/Proyecto/php/ResetPassCode.php?email=".$_POST['email']."'>enlace de recuperación</a></li>
												<li>Rellenar los campos con la nueva contraseña y el siguiente código: <b>".$codigo."</b></li>
											</ul>
										</body></html>
										";
										
								$_SESSION['codigo'] = $codigo;
								$_SESSION['correo'] = $_POST['email'];
							if(mail($receptor, $asunto, $mensaje, $headers)){
								$_SESSION['codigo'] = $codigo;
								$_SESSION['correo'] = $_POST['email'];
								echo "<div style='color:white; background-color:#ff0000'>".$nombre.", se ha enviado un email a la dirección dada. Es necesario seguir sus instrucciones para restablecer la contraseña. Revise la carpeta de SPAM. No se debe cerrar la sesión.</div>";
							}else{
								echo "Email no enviado";
							}
						}else{
							echo "<div style='color:white; background-color:#ff0000'>Error en los campos o usuario bloqueado, inténtalo otra vez.</div>";
						}
						mysqli_close($conexion);
					}else{
						echo "<div style='color:white; background-color:#ff0000'>Error en los campos, inténtalo otra vez.</div>";
					}
			}else if(!isset($_POST['enviar'])){
				echo"";
			}else{
				echo "<div style='color:white; background-color:#ff0000'>No puede haber campos vacíos.</div>";
			}
		?>
		</div>
	</div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>