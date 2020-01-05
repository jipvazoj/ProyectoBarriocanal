<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<?php include '../php/DbConfig.php'?>
	<link rel="icon" type="image/png" href="../images/icon/favicon-32x32.png" sizes="32x32" />
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
	<script>
		function confirmacion(dialogo){
			if(confirm(dialogo)==true){
				return true;
			}else{
				return false;
			}
		}
	</script>
</head>
<body>
	<section class="main" id="s1">
    <?php
		if(!isset($_SESSION)){
			session_start();
		}
		if(isset($_SESSION['email'])){
			$conexion = mysqli_connect($server, $user, $pass, $basededatos);
				// Check connection
				if (!$conexion) {
					die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
				}

				$sqlUsuario = "SELECT * FROM usuarios";
				$queryUsuario = mysqli_query($conexion, $sqlUsuario);

				if (mysqli_num_rows($queryUsuario) > 0) {
					
					$encontrado = 0;
					while($row = mysqli_fetch_assoc($queryUsuario)){
						if(strcmp($row['email'],$_SESSION['email'])==0){
							$encontrado=1;
							$tipo = $row['tipo'];
							if($row['estado']=='1'){
								$estado = 'Activo';
							}else if($row['estado']=='0'){
								$estado = 'Bloqueado';
							}
							break;	
						}
					}
					
					if($encontrado&&$tipo==3){
						// Create connection
						$conexion = mysqli_connect($server, $user, $pass, $basededatos);
						// Check connection
						if (!$conexion) {
							die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
						}

						$sql = "SELECT * FROM usuarios";
						$query = mysqli_query($conexion, $sql);

						if (mysqli_num_rows($query) > 0) {
							echo"<div style='height:500px;overflow-y:scroll;'>
								<table>
									<tr>
										<th>Email</th>
										<th>Contraseña</th>
										<th>Imagen</th>
										<th>Estado</th>
										<th>Cambiar Estado</th>
										<th>Borrar Usuario</th>
									</tr>
							";
							while($row = mysqli_fetch_assoc($query)) {
								if($row["email"]!="admin@ehu.es"){
									if($row["imagen"]==''){
										$rutaimagen = '../images/noimage.png';
									}else{
										$rutaimagen = '../images/'.$row["imagen"];
									}
									if($row['estado']=='1'){
										$estadousuario = "Activo";
									}else{
										$estadousuario = "Bloqueado";
									}
									echo" 
										<tr>
											<td>".$row["email"]."</td>
											<td>".$row["password"]."</td>
											<td style='text-align:center;'> <img src=".$rutaimagen." height='100'/></td>
											<td>".$estadousuario."</td>
											<td><form method='POST' action='ChangeState.php' onsubmit='return confirm(&quot;¿Seguro que quieres cambiar el estado?&quot;);'><input type='submit' name='".$row['email']."' value='Cambiar Estado'/></form></td>
											<td><form method='POST' action='RemoveUser.php' onsubmit='return confirm(&quot;¿Seguro que quieres borrar el usuario?&quot;);'><input type='submit' name='".$row['email']."' value='Borrar Usuario'/></form></td>
										</tr>
									";
								}
							}
							echo"</table></div>";
						} else {
							echo '<div style="color:white; background-color:#ff0000">El usuario no está registrado o no tiene los privilegios necesarios para acceder.</div>';
						}
						mysqli_close($conexion);						
					}else{
						die('<div style="color:white; background-color:#ff0000">El usuario no está registrado o no tiene los privilegios necesarios para acceder.</div>');
					}
						
				}else{
					die('<div style="color:white; background-color:#ff0000">El usuario no está registrado o no tiene los privilegios necesarios para acceder.</div>');					
				}
		}else{
			echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página se necesita haber iniciado sesión.</div>";
		}
    ?>
	</section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
