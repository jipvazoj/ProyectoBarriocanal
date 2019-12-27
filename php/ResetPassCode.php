<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>

<head>
	<?php include '../html/Head.html' ?>
	<?php include '../php/DbConfig.php' ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="../js/ValidateCode.js"></script>
	<script src="../js/ShowImageInForm.js"></script>

</head>

<body>
	<section class="main" id="s1">
	<?php
		require_once('../lib/nusoap.php');
		require_once('../lib/class.wsdlcache.php');
		if(!isset($_SESSION)){
			session_start();
		}
		if(!isset($_GET['email'])){
			echo '<div style="color:white; background-color:#ff0000">El usuario no tiene los privilegios estipulados para acceder a esta página.</div>';
		}else{
			if(isset($_SESSION['codigo'])&&isset($_GET['email'])){
				echo "	<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; width:70%; margin-left:auto; margin-right:auto; text-align:left; padding: 1em;'> <p style='text-align:center;'>RESTABLECER CONTRASEÑA</p><br>
								<form method='POST' action='ResetPassCode.php?email=".$_GET['email']."' id='fsignup' name='fsignup' enctype='multipart/form-data' accept-charset='UTF-8'>
									Email*: <br><input type='text' id='email' value='".$_GET['email']."' name='email' size=35 readonly><span id='eemail'></span><br>
									<div id='emailvip'></div><br>
									Contraseña*: <br><input type='password' id='pass1' name='pass1' size=55><span id='epass1'></span><br>
									<div id='passsegura'></div><br>
									Repetir Contraseña*: <br><input type='password' id='pass2' name='pass2' size=55><span id='epass2'></span><br><br>
									Código*: <br><input type='number' name='codigo' id='codigo'>
									<p style='text-align:center;'><input type='submit' id='enviar' name='enviar' value='Enviar'></p>
								</form>
							</div>
						";
			}else{
				echo '<div style="color:white; background-color:#ff0000">Solo es posible acceder a esta página si se tiene un código de recuperación de contraseña.</div>';
			}
		}
	?>	
		<?php
			if(isset($_POST['email'])){
				echo "<div>";
				function validar($email, $pass1, $pass2, $codigo){
					echo "HOLA";
					$error= false;
					$errormsg='Se han encontrado los siguientes errores:\n';
					$passlength = strlen($pass1);
					if (strlen($pass1) == 0 || strlen($pass2) == 0) {
						//echo "<p></p>";
						$errormsg = $errormsg.'Algunos campos estan vacíos.\n';
						$error=true;
					}
					if ($pass1 != $pass2) {
						//echo "<p>Las contraseñas no coinciden.</p>";
						$errormsg = $errormsg.'Las contraseñas no coinciden.\n';
						$error=true;
					}
					if ($passlength < 6) {
						//echo "<p>Contraseña demasiado corta.</p>";
						$errormsg = $errormsg.'Contraseña demasiado corta.\n';
						$error=true;
					}
					if(strlen($codigo)!=6 || !is_numeric($codigo) ){
						$errormsg=$errormsg.'El código no tiene la longitud correcta o no es numérico.';
						$error=true;
					}
					if($codigo!=$_SESSION['codigo']){
						$errormsg = $errormsg.'El código es incorrecto.';
						$error=true;
					}
					if($error){
						echo'<script type="text/javascript">alert("'.$errormsg.'");</script>';
						return false;
					}
					else{
						return true;
					}
				
				}
				$validacion = false;
				if (isset($_POST['enviar'])) {
					$validacion = validar($_POST['email'],$_POST['pass1'], $_POST['pass2'], $_POST['codigo']);
					if ($validacion) {
						$mysql = mysqli_connect($server, $user, $pass, $basededatos);		
						$emails = "SELECT * from usuarios WHERE email='".$_POST['email']."'";
						$queryemails = mysqli_query($mysql, $emails);
						
						$salt = crypt($_POST['email'],"SW");
						$hashpass = crypt($_POST['pass1'],$salt);
						
						if (!$queryemails){
							die('<div style="color:white; background-color:#ff0000">No se ha podido establecer la conexión con la base de datos.</div>');
						}else{
							if (mysqli_num_rows($queryemails) == 1) {
								$sql="UPDATE usuarios SET password='$hashpass' WHERE email='".$_POST['email']."'";
								if(mysqli_query($mysql, $sql)){
									$actualizado = true;
								}else{
									echo '<div style="color:white; background-color:#ff0000">No se ha podido actualizar la contraseña.</div>';
									$actualizado = false;
								}
							}else{
								echo '<div style="color:white; background-color:#ff0000">El usuario no está registrado.</div>';
								$actualizado = false;
							}
						}
						mysqli_close($mysql);
					}
				}
				echo "</div>";
				if ($validacion && $actualizado) {
					echo "	<div style='color:white; background-color:#00cc66'>
					<strong>¡Cambio de contraseña realizado con éxito!</strong> Para entrar con la nueva contraseña <a href='../php/LogIn.php' class='alert-link'>pulsa aquí.</a>.
					</div>
				  ";
				}
			}else{
				echo '';
			}
		?>
	</section>
	<?php include '../html/Footer.html' ?>
</body>

</html>