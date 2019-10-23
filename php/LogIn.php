<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<?php include '../php/DbConfig.php' ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="../js/ValidateLogIn.js"></script>
    
</head>
<body>
  <?php include '../php/Menus.php' ?>
  <section class="main" id="s1">
    <div>
		<div style="border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; width:70%; margin-left:auto; margin-right:auto; text-align:left; padding: 1em;"> <p style="text-align:center;">INICIO DE SESIÓN</p><br>
		<form method="POST" action="LogIn.php" id='fquestion' name='fquestion' enctype="multipart/form-data" accept-charset="UTF-8">
			Email*: <br><input type="text" id="email" name="email" size=35><span id='eemail'></span><br>
			Contraseña*: <br><input type="password" id="pass" name="password" size=35><span id='epass'></span><br><br>
			<p><input type="submit" id="enviar" name="enviar" value="Enviar"></p>	
		</form>
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

					if (mysqli_num_rows($query) > 0) {
						
						$encontrado = 0;
						while($row = mysqli_fetch_assoc($query)){
							if(strcmp($row['email'],$_POST['email'])==0 && strcmp($row['password'],$_POST['password'])==0){
								$nombre = $row['nombre'];
								$encontrado=1;
								break;	
							}
						}
						
						if($encontrado){
							echo '<script>alert("Bienvenido, '.$nombre.'.");header(location: Layout.php?email='.$_POST['email'].');</script>';
						}else{
							echo "<div style='color:white; background-color:#ff0000'>Error en los campos, inténtalo otra vez.</div>";
						}
						$sql->close();
						mysqli_close($mysqli);
						
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
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>