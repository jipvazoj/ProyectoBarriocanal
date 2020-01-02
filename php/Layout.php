<?php include '../php/Menus.php' ?>
<?php include '../php/DbConfig.php'?>
<!DOCTYPE html>
<html>
<head>
  <?php include '../html/Head.html'?>
  	
  <style>
  
	table {
		border-collapse: collapse;
		width: 100%;
		font-family: 'Press Start 2P';
		background: repeating-linear-gradient(
					to bottom,
					#0f0a1e,
					#0f0a1e 2px,
					lighten(#0f0a1e, 3%) 2px,
					lighten(#0f0a1e, 3%) 4px
    
		);
		color: white;
	}

	td, th {
		border: 1px solid black;
		padding: 8px;
		color:lime;
	}

	th{
		background-color: black;
		color: white;
		text-align: center;
	}
	td{
		text-align: center;
		background-color:black;
	}
	#titulojuego{
		font-family: 'Press Start 2P', cursive;
		background: repeating-linear-gradient(
			to bottom,
			#0f0a1e,
			#0f0a1e 2px,
			lighten(#0f0a1e, 3%) 2px,
			lighten(#0f0a1e, 3%) 4px
		);
		height: 90px;
		color: black;
		width: 100%;
		margin: auto;
		font-size: 30px;
		line-height: 40px;
		letter-spacing: 5px;
		text-shadow: -2px 0 0 #fdff2a,
					-4px 0 0 #df4a42,
					2px 0 0 #91fcfe,
					4px 0 0 #4405fc;
	}

	
	
  </style>
  
  <link rel="stylesheet" type="text/css" href="../styles/Layout.css" media="screen" />
  <link href='https://fonts.googleapis.com/css?family=Press+Start+2P' rel='stylesheet' type='text/css'/>
</head>
<body>
  <section style='overflow-y:scroll;' class="main" id="s1">
    <div>
		<div id='titulojuego'>Quiz: el juego de las preguntas</div>
		<input style ="font-family: 'Press Start 2P', cursive;" class='spoilerbutton' type='button' value='Mostrar tabla de quizzers' onclick="this.value=this.value=='Mostrar tabla de quizzers'?'Ocultar tabla de quizzers':'Mostrar tabla de quizzers';">	
			<div class='spoiler'><div style="border-style:solid;border-color:black; font-family: 'Press Start 2P', cursive;"><h1>TOP 10 QUIZZERS</h1><br>
			<?php
				// Create connection
				$conexion = mysqli_connect($server, $user, $pass, $basededatos);
				// Check connection
				if (!$conexion) {
					die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
				}

				$sql = "SELECT * FROM resultados ORDER BY aciertos DESC, porcentaje DESC LIMIT 10";
				$query = mysqli_query($conexion, $sql);

				if (mysqli_num_rows($query) > 0) {
					echo"<div style='overflow-y:scroll;'>
						<table style='width:50%;margin:auto;'>
							<tr>
								<th>RANK</th>
								<th>NAME</th>
								<th>ACIERTOS</th>
								<th>FALLOS</th>
								<th>PORCENTAJE</th>
							</tr>
					";
					// output data of each row
					$i = 0;
					while($row = mysqli_fetch_assoc($query)) {
						$i++;
						$porcentaje = $row["porcentaje"] * 100 . "%";
						echo" 
							<tr>
								<td>".$i."</td>
								<td>".$row["nombre"]."</td>
								<td>".$row["aciertos"]."</td>
								<td>".$row["fallos"]."</td>
								<td>".$porcentaje."</td>
							</tr>
						";
					}
					echo"</table></div>";
				}else{
					echo "Todavía nadie ha participado. ¡Sé el primero!";
				}
				if(!isset($_SESSION['email'])){
			?>
			</div></div><br>
									
			<input style ="font-family: 'Press Start 2P', cursive;" class='spoilerbutton' type='button' value='Jugar' onclick="this.value=this.value=='Jugar'?'Ocultar temas':'Jugar';">
		<div class='spoiler'><div style="border-style:solid;border-color:black;">
		<h1 style ="font-family: 'Press Start 2P', cursive;"> INSTRUCCIONES DEL JUEGO </h1><br>
		<p>Al pulsar en Jugar se mostrará una pregunta aleatoria del tema elegido en el desplegable.<br>Se podrán responder más preguntas del tema elegido en el desplegable si están disponibles.<br>Se podrán guardar los resultados (aciertos y fallos) asociándolos a un nick de 3 letras.<br>Los nicks en el top 10 con sus aciertos, fallos y porcentajes en la tabla de quizzers superior.</p><br><br>
		
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
				?><select style ="font-family: 'Press Start 2P', cursive; font-size:25px" name='temas' id='temas'>
				<?php
				// output data of each row
				while($row = mysqli_fetch_assoc($query)) {
					$tema= ucfirst(strtolower($row['tema']));
					echo "<option value='".$row['tema']."'>".$tema."</option>";
				}
				echo "</select><br><br><br>";
				?><p><input style ="font-family: 'Press Start 2P', cursive;" type='submit' id='jugar' name='jugar' value='Jugar'></p>
					<?php				
				echo "</form>";
			}else{
				echo "No hay preguntas. ¡Añade nuevas!";
			}
			echo "</div><div></div>";
			}
		?>
      
    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
