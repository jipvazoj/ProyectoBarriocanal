<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<?php include '../php/DbConfig.php' ?>
	<link rel="icon" type="image/png" href="../images/icon/favicon-32x32.png" sizes="32x32" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
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
    <div>
		<?php
			if(!isset($_SESSION)){
				session_start();
			}
			if(isset($_SESSION['email'])){
				//comprobar si está en la BBDD
				$conexion = mysqli_connect($server, $user, $pass, $basededatos);
					// Check connection
					if (!$conexion) {
						die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos.</div>');
					}

					$sql = "SELECT * FROM usuarios";
					$query = mysqli_query($conexion, $sql);

					if (mysqli_num_rows($query) > 0) {
						
						$encontrado = 0;
						while($row = mysqli_fetch_assoc($query)){
							if(strcmp($row['email'],$_SESSION['email'])==0){
								$nombre = $row['nombre'];
								$encontrado=1;
								break;	
							}
						}
						
						if($encontrado){
							//XML
							try{
								libxml_use_internal_errors(TRUE);
								$xml = simplexml_load_file('../xml/Questions.xml');
							
								echo "	<div style='height:500px;overflow-y:scroll;'>
										<table>
											<tr>
												<th>Autor</th>
												<th>Enunciado</th>
												<th>Respuesta Correcta</th>
											</tr>
								";
								foreach ($xml->children() as $preguntas){
										echo "<tr>
												<td>".$preguntas['author']."
												<td>".$preguntas->itemBody->p."
												<td>".$preguntas->correctResponse->value."
											<tr>";
								}
								echo "</table></div>";
							}catch(Exception $e){
								echo "<div style='color:white; background-color:#ff0000'>No se ha podido cargar el XML, inténtelo otra vez <a href='../php/ShowXMLQuestions.php' class='alert-link'>aquí</a></div>";
							}
							//FIN XML							
						}else{
							echo "<div style='color:white; background-color:#ff0000'>Para ver las preguntas se debe haber iniciado sesión.</div>";
						}
						mysqli_close($conexion);
					}else{
						echo "<div style='color:white; background-color:#ff0000'>Para ver las preguntas se debe haber iniciado sesión.</div>";
					}
			}else{
				echo "<div style='color:white; background-color:#ff0000'>Para ver las preguntas se debe haber iniciado sesión.</div>";
			}
		?>
	</div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>