<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../html/Head.html'?>
  <link rel="icon" type="image/png" href="../images/icon/favicon-32x32.png" sizes="32x32" />
</head>
<body>
  <section class="main" id="s1">
  	<?php
		if(!isset($_SESSION)){
			session_start();
		}
		if(!isset($_SESSION['email'])){
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
					if(strcmp($row['email'],$_SESSION['email'])==0){
						$encontrado=1;
						$tipo=$row['tipo'];
						break;	
					}
				}
				
				if($encontrado&&($tipo==1||$tipo==2)){
					echo "	<div>
							  Añadir el formulario y los scripts necesarios para que el usuario <br>
							  pueda introducir los datos de una pregunta sin imagen.

							</div>";
				}else{
					echo"<div style='color:white; background-color:#ff0000'>El usuario no está registrado o no tiene los privilegios estipulados.</div>";
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
