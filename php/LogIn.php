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
		if(!isset($_SESSION['email'])){
			
			echo "	<div>
		<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; width:70%; margin-left:auto; margin-right:auto; text-align:left; padding: 1em;'> <p style='text-align:center;'>INICIO DE SESIÓN</p><br>
		<form method='POST' action='LogIn.php' id='fquestion' name='fquestion' enctype='multipart/form-data' accept-charset='UTF-8'>
			Email*: <br><input type='text' id='email' name='email' size=35><span id='eemail'></span><br>
			Contraseña*: <br><input type='password' id='pass' name='password' size=35><span id='epass'></span><br><br>
			<p><input type='submit' id='enviar' name='enviar' value='Enviar'></p>	
		</form>
					";
		}else{
					
			echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página no se puede tener la sesión iniciada.</div>";		
		}
	?>	
		<?php
			if(isset($_POST['email'])&&isset($_POST['password'])){
				//comprobar si está en la BBDD
				$conexion = mysqli_connect($server, $user, $pass, $basededatos);
					// Check connection
					if (!$conexion) {
						die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
					}

					$sql = "SELECT * FROM usuarios";
					$query = mysqli_query($conexion, $sql);
						
					$salt = crypt($_POST['email'],"SW");
					
					
					if (mysqli_num_rows($query) > 0) {
						
						$encontrado = 0;
						while($row = mysqli_fetch_assoc($query)){
							if(strcmp($row['email'],$_POST['email'])==0){
								if(hash_equals($row['password'], crypt($_POST['password'], $salt))){
									$nombre = $row['nombre'];
									$estado = $row['estado'];
									$encontrado=1;
								}
								break;	
							}
						}
						
						if($encontrado&&$estado==1){
							if(!isset($_SESSION)){
								session_start();
							}
							$_SESSION['email'] = $_POST['email'];
							echo '<script>alert("Bienvenido, '.$nombre.'."); location.href="Layout.php";</script>';							
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