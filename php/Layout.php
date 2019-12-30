<?php include '../php/Menus.php' ?>
<?php include '../php/DbConfig.php'?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../html/Head.html'?>
  	
  <link rel="stylesheet" type="text/css" href="../styles/Layout.css" media="screen" />
</head>
<body>
  <section style='overflow-y:scroll;' class="main" id="s1">
    <div>

      <h2>Quiz: el juego de las preguntas</h2>
	  
		
		<br>
		<input class='spoilerbutton' type='button' value='Mostrar tabla de quizzers' onclick="this.value=this.value=='Mostrar tabla de quizzers'?'Ocultar tabla de quizzers':'Mostrar tabla de quizzers';">	
			<div class='spoiler'><div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif;'><h1>TABLA DE QUIZZERS</h1><br>
			<?php
				// Create connection
				$conexion = mysqli_connect($server, $user, $pass, $basededatos);
				// Check connection
				if (!$conexion) {
					die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
				}

				$sql = "SELECT * FROM resultados";
				$query = mysqli_query($conexion, $sql);

				if (mysqli_num_rows($query) > 0) {
					echo"<div style='overflow-y:scroll;'>
						<table>
							<tr>
								<th>NOMBRE</th>
								<th>ACIERTOS</th>
								<th>FALLOS</th>
							</tr>
					";
					// output data of each row
					while($row = mysqli_fetch_assoc($query)) {
						echo" 
							<tr>
								<td>".$row["nombre"]."</td>
								<td>".$row["aciertos"]."</td>
								<td>".$row["fallos"]."</td>
							</tr>
						";
					}
					echo"</table></div>";
				}else{
					echo "Todavía nadie ha participado. ¡Sé el primero!";
				}
			?>
			</div></div><br>
									
			<input class='spoilerbutton' type='button' value='Jugar' onclick="this.value=this.value=='Jugar'?'Ocultar temas':'Jugar';">
		<div class='spoiler'><div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif;'>
		<h1> TEMAS DE QUIZ </h1>
		<p>Al pulsar en Jugar se mostrará una pregunta aleatoria del tema elegido.<br>Se podrán responder más preguntas del mismo tema si están disponibles.<br>Se podrán guardar los resultados (aciertos y fallos) asociándolos a un nick.<br>Los nicks aparecerán con su resultado en la tabla de quizzers.</p><br><br>
		
		<?php
			// Create connection
			$conexion = mysqli_connect($server, $user, $pass, $basededatos);
			// Check connection
			if (!$conexion) {
				die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
			}

			$sql = "SELECT DISTINCT tema FROM preguntas";
			$query = mysqli_query($conexion, $sql);
			
			if (mysqli_num_rows($query) > 0) {
				echo "<form method='POST' action='Pregunta.php' id='ftema' name='ftema'>";
				echo "<select style='font-size:25px' name='temas' id='temas'>";
				// output data of each row
				while($row = mysqli_fetch_assoc($query)) {
					$tema= ucfirst(strtolower($row['tema']));
					echo "<option value='".$row['tema']."'>".$tema."</option>";
				}
				echo "</select><br><br><br>";
				echo "<p><input type='submit' id='jugar' name='jugar' value='Jugar'></p>";	
				echo "</form>";
			}else{
				echo "No hay preguntas. ¡Añade nuevas!";
			}
			echo "</div><div></div>";
		?>
      
    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
