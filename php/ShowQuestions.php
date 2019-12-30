<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<?php include '../php/DbConfig.php'?>
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
						break;	
						}
					}
					
					if($encontrado){
						// Create connection
						$conexion2 = mysqli_connect($server, $user, $pass, $basededatos);
						// Check connection
						if (!$conexion2) {
							die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
						}

						$sql = "SELECT * FROM preguntas";
						$query = mysqli_query($conexion2, $sql);

						if (mysqli_num_rows($query) > 0) {
						  echo"<div>
						  <table>
							<tr>
							  <th>Email</th>
							  <th>Pregunta</th>
							  <th>Respuesta Correcta</th>
							</tr>
						  ";
							// output data of each row
							while($row = mysqli_fetch_assoc($query)) {
							  //r_correcta, r_in1, r_in2, r_in3, complejidad, tema  
							  echo" 
							  <tr>
								<td>".$row["email"]."</td>
								<td>".$row["enunciado"]."</td>
								<td>".$row["r_correcta"]."</td>
							  </tr>
							  ";
							}
						echo"</table></div>";
						}
					}else {
						echo '<div style="color:white; background-color:#ff0000">El usuario no está registrado.</div>';
					}
					mysqli_close($conexion2);						
				}else{
					die('<div style="color:white; background-color:#ff0000">El usuario no está registrado.</div>');
				}
				mysqli_close($conexion);
			}else{
				echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página se necesita haber iniciado sesión.</div>";
			}

			
    ?>
	</section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
