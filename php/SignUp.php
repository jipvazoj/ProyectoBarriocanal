<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>

<head>
	<?php include '../html/Head.html' ?>
	<?php include '../php/DbConfig.php' ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="../js/ValidateSignUp.js"></script>
	<script src="../js/ShowImageInForm.js"></script>
	<link rel="icon" type="image/png" href="../images/icon/favicon-32x32.png" sizes="32x32" />
</head>

<body>
	<section class="main" id="s1">
	<?php
		require_once('../lib/nusoap.php');
		require_once('../lib/class.wsdlcache.php');
		if(!isset($_SESSION)){
			session_start();
		}
		if(!isset($_SESSION['email'])){
			
			echo "	<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; width:70%; margin-left:auto; margin-right:auto; text-align:left; padding: 1em;'> <p style='text-align:center;'>REGISTRO</p><br>
							<form method='POST' action='' id='fsignup' name='fsignup' enctype='multipart/form-data' accept-charset='UTF-8'>
								Email*: <br><input type='text' id='email' name='email' size=35><span id='eemail'></span><br>
								<div id='emailvip'></div><br>
								Nombre y Apellidos*: <br><input type='text' id='nombre' name='nombre' size=55><span id='enombre'></span><br>
								Contraseña*: <br><input type='password' id='pass1' name='pass1' size=55><span id='epass1'></span><br>
								<div id='passsegura'></div><br>
								Repetir Contraseña*: <br><input type='password' id='pass2' name='pass2' size=55><span id='epass2'></span><br>
								Tipo de Usuario*: <input type='radio' name='typeuser' value='1' id='typeuser1' checked> Alumno
								<input type='radio' name='typeuser' value='2' id='typeuser2'> Profesor <br>
								Imagen: <input type='file' id='file' accept='image/*' name='foto' onchange='verImagen(event)'><br><img id='imagen' name='imagen' width='100' /><br>
								<p style='text-align:center;'><input type='submit' id='enviar' name='enviar' value='Enviar' disabled></p>
							</form>
						</div>
					";
		}else{					
			echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página no se puede tener la sesión iniciada.</div>";
		}
	?>	
		<?php
			if(!isset($_SESSION['email'])){
				echo "<div>";
				function validar($email, $nombre, $pass1, $pass2, $typeuser){
					$error= false;
					$errormsg='Se han encontrado los siguientes errores:\n';
					$passlength = strlen($pass1);
					if (strlen($nombre) == 0 || strlen($pass1) == 0 || strlen($pass2) == 0) {
						//echo "<p></p>";
						$errormsg = $errormsg.'Algunos campos estan vacíos.\n';
						$error=true;
					}
					if (preg_match("/^[a-z]+[0-9]{3}@ikasle\.ehu\.(eus|es)$/", $email) == 0 && preg_match("/^[a-z]+\.*[a-z]+@ehu\.(eus|es)$/", $email) == 0) {
						//echo "<p>Fallo en el email</p>";
						$errormsg = $errormsg.'Error de formato en el email.\n';
						$error=true;
					}
					if((preg_match("/^[a-z]+[0-9]{3}@ikasle\.ehu\.(eus|es)$/", $email) == 0 && $typeuser=='1') || (preg_match("/^[a-z]+\.*[a-z]+@ehu\.(eus|es)$/", $email) == 0 && $typeuser == '2')){
						$errormsg = $errormsg.'El email no concuerda con el tipo de usuario.\n';
						$error = true;
					}	
					if (str_word_count($nombre) < 2) {
						//echo "<p>Formato del nombre no valido. </p>";
						$errormsg = $errormsg.'Formato de nombre no valido.\n';
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
					$valores = array('valores' => array('min_range' => 1, 'max_range' => 2));
					if (filter_var($typeuser, FILTER_VALIDATE_INT, $valores) == false) {
						//echo "<p>Fallo en el tipo de usuario.</p>";
						$errormsg = $errormsg.'Error en el tipo de usuario.\n';
						$error=true;
					}
					$soapclient = new nusoap_client('http://ehusw.es/jav/ServiciosWeb/comprobarmatricula.php?wsdl' ,true);
					$result = $soapclient->call('comprobar',array('x'=>$email));
					if($result!='SI'){
						$errormsg = $errormsg.'El email no es VIP.\n';
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
					$validacion = validar($_POST['email'], $_POST['nombre'], $_POST['pass1'], $_POST['pass2'], $_POST['typeuser']);
					$tipo = $_POST['typeuser'];
					if($_POST['email']=='admin@ehu.es'&&$_POST['typeuser']==2&&$validacion){
						$tipo = 3;
					}
					if ($validacion) {
						$mysql = mysqli_connect($server, $user, $pass, $basededatos);
						$imagen = $_FILES['foto']['name'];
						$imagenTMP = $_FILES['foto']['tmp_name'];
						$carpeta = "A:/xampp/htdocs/Proyecto/images/";
						move_uploaded_file($imagenTMP, $carpeta.$imagen);			
						$emails = "SELECT * from usuarios WHERE email='".$_POST['email']."'";
						$queryemails = mysqli_query($mysql, $emails);
						
						$salt = crypt($_POST['email'],"SW");
						$hashpass = crypt($_POST['pass1'],$salt);
						
						if (!$queryemails){
							die('<div style="color:white; background-color:#ff0000">No se ha podido establecer la conexión con la base de datos.</div>');
						}else{
							if (mysqli_num_rows($queryemails) == 0) {
								$sql="INSERT INTO usuarios (ID, email, nombre, password, tipo, estado, imagen) 
										VALUES (NULL, '$_POST[email]','$_POST[nombre]', '$hashpass', '$tipo','1', '$imagen')";
								if(mysqli_query($mysql, $sql)){
									$registrado = true;
								}else{
									echo '<div style="color:white; background-color:#ff0000">El usuario no se ha podido registrar.</div>';
									$registrado = false;
								}
								
							}else{
								echo '<div style="color:white; background-color:#ff0000">El usuario ya está registrado.</div>';
								$registrado = false;
							}
						}
						mysqli_close($mysql);
					}
				}
				echo "</div>";
				if ($validacion && $registrado) {
					echo "	<div style='color:white; background-color:#00cc66'>
					<strong>¡Registro realizado con éxito!</strong> Para entrar <a href='../php/LogIn.php' class='alert-link'>pulsa aquí.</a>.
					</div>
				  ";
				}
			}else{
				echo '<div style="color:white; background-color:#ff0000">No es posible realizar un registro si ya se está logueado.</div>';
			}
		?>
	</section>
	<?php include '../html/Footer.html' ?>
</body>

</html>