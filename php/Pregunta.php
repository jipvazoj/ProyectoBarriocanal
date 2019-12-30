<?php include '../php/Menus.php' ?>
<?php include '../php/DbConfig.php'?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<script src="../js/jquery-3.4.1.min.js"></script>
	<script src="../js/AddResultAjax.js"></script>
</head>
<body>
  <section style='overflow-y:scroll;' class="main" id="s1">
    <div>

      <h2>Quiz: el juego de las preguntas</h2>
	  
		
		
		
		<?php
			if(!isset($_SESSION['preguntas'])){
				$_SESSION['preguntas'] = 0;
			}
			
			if(!isset($_SESSION['fallos'])){
				$_SESSION['fallos']=0;
			}
			
			if(!isset($_SESSION['aciertos'])){
				$_SESSION['aciertos']=0;
			}


			if(isset($_POST['jugar'])){
				
				
				if(isset($_POST['respuesta'])&&isset($_POST['id'])){
					$_SESSION['preguntas'].=",".$_POST['id'];
					//comprobar respuesta correcta y sumar
					$conexion = mysqli_connect($server, $user, $pass, $basededatos);
						// Check connection
						if (!$conexion) {
							die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
						}
						$sql = "SELECT * FROM preguntas WHERE tema = ".$_POST['temas']." AND ID = ".$_POST['id'];
						
						$query = mysqli_query($conexion, $sql);
						
						if (mysqli_num_rows($query) == 1) {
							
							$preguntas = array();					
							while($row = mysqli_fetch_array($query)){
								$preguntas[] = $row;
							}
							$preguntarespondida = $preguntas[0];
							
							if($_POST['respuesta'] == $preguntarespondida['r_correcta']){
								$_SESSION['aciertos'] += 1;
							}else{
								$_SESSION['fallos'] += 1;
							}
						}
				}
				echo "<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif;'>";
				
				// Create connection
				$conexion = mysqli_connect($server, $user, $pass, $basededatos);
				// Check connection
				if (!$conexion) {
					die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
				}
				if(isset($_SESSION['preguntas'])){
					$sql = "SELECT * FROM preguntas WHERE tema = ".$_POST['temas']." AND ID NOT IN (".$_SESSION['preguntas'].")";
				}else{
					$sql = "SELECT * FROM preguntas WHERE tema = ".$_POST['temas'];
				}
				$query = mysqli_query($conexion, $sql);
				
				if (mysqli_num_rows($query) > 1) {
					//CASO GENERAL PARA MÁS DE 1 RESULTADO
					$numpregunta = rand(1,mysqli_num_rows($query))-1;
					
					$preguntas = array();					
					while($row = mysqli_fetch_array($query)){
						$preguntas[] = $row;
					}
					$preguntaseleccionada = $preguntas[$numpregunta];
					
					
					//shuffle respuestas
					$respuestas = array();
					$respuestas[] = $preguntaseleccionada['r_correcta'];
					$respuestas[] = $preguntaseleccionada['r_in1'];
					$respuestas[] = $preguntaseleccionada['r_in2'];
					$respuestas[] = $preguntaseleccionada['r_in3'];
					shuffle($respuestas);
					
					
					//obtener ruta imagen
					if($row["img"]==''){
						$rutaimagen = '../images/noimage.png';
					}else{
						$rutaimagen = '../images/'.$row["img"];
					}
					echo "Se ha elegido una pregunta aleatoria del tema ".$_POST['temas'].".";
					echo "<p>En un rango de 0 a 3 la pregunta tiene una complejidad de ".$preguntaseleccionada['complejidad']."</p>";
					echo "<p>La pregunta ha sido aportada por ".$preguntaseleccionada['email']."</p>";
					echo "<h1>".$preguntaseleccionada['enunciado']."</h1>";
					echo "<p><img src=".$rutaimagen." height='100'/></p>";
					echo "<form method='POST' action='Pregunta.php'>";
					echo "<input type='hidden' id='id' name='id' value='".$preguntaseleccionada['ID']."'>";
					echo "<input type='hidden' id='temas' name='temas' value='".$_POST['temas']."'>";
					echo "<input type='radio' name='respuesta' value='".$respuestas[0]."' required> ".$respuestas[0]."<br>
							<input type='radio' name='respuesta' value='".$respuestas[1]."'> ".$respuestas[1]."<br>
							<input type='radio' name='respuesta' value='".$respuestas[2]."'> ".$respuestas[2]."<br>
							<input type='radio' name='respuesta' value='".$respuestas[3]."'> ".$respuestas[3]."<br>";
							
					//LIKE DISLIKE		
							
					echo "<p><input type='submit' id='jugar' name='jugar' value='Contestar otra pregunta'><input type='submit' id='terminar' name='terminar' value='Terminar'></p>";
					
				}else if(mysqli_num_rows($query) == 1){
					//CASO PARA LA ÚLTIMA PREGUNTA
					
					$preguntas = array();					
					while($row = mysqli_fetch_array($query)){
						$preguntas[] = $row;
					}
					$preguntaseleccionada = $preguntas[0];
					
					//shuffle respuestas
					$respuestas = array();
					$respuestas[] = $preguntaseleccionada['r_correcta'];
					$respuestas[] = $preguntaseleccionada['r_in1'];
					$respuestas[] = $preguntaseleccionada['r_in2'];
					$respuestas[] = $preguntaseleccionada['r_in3'];
					shuffle($respuestas);
					
					
					//obtener ruta imagen
					if($row["img"]==''){
						$rutaimagen = '../images/noimage.png';
					}else{
						$rutaimagen = '../images/'.$row["img"];
					}
					echo "Se ha elegido una pregunta aleatoria del tema ".$_POST['temas'].".";
					echo "<p>En un rango de 0 a 3 la pregunta tiene una complejidad de ".$preguntaseleccionada['complejidad']."</p>";
					echo "<p>La pregunta ha sido aportada por ".$preguntaseleccionada['email']."</p>";
					echo "<h1>".$preguntaseleccionada['enunciado']."</h1>";
					echo "<p><img src=".$rutaimagen." height='100'/></p>";
					echo "<form method='POST' action='Pregunta.php'>";
					echo "<input type='hidden' id='id' name='id' value='".$preguntaseleccionada['ID']."'>";
					echo "<input type='hidden' id='temas' name='temas' value='".$_POST['temas']."'>";
					echo "<input type='radio' name='respuesta' value='".$respuestas[0]."' required> ".$respuestas[0]."<br>
							<input type='radio' name='respuesta' value='".$respuestas[1]."'> ".$respuestas[1]."<br>
							<input type='radio' name='respuesta' value='".$respuestas[2]."'> ".$respuestas[2]."<br>
							<input type='radio' name='respuesta' value='".$respuestas[3]."'> ".$respuestas[3]."<br>";
							
					//LIKE DISLIKE		
							
					echo "<p><input type='submit' id='terminar' name='terminar' value='Terminar'></p>";
					
				}else{
					echo "No existen preguntas del tema seleccionado.";
				}
		
		
				echo "</div>";
			}else if(isset($_POST['terminar'])){
				
				//comprobar última respuesta
				if(isset($_POST['respuesta'])&&isset($_POST['id'])){
					$_SESSION['preguntas'].=",".$_POST['id'];
					//comprobar respuesta correcta y sumar
					$conexion = mysqli_connect($server, $user, $pass, $basededatos);
						// Check connection
						if (!$conexion) {
							die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
						}
						$sql = "SELECT * FROM preguntas WHERE tema = ".$_POST['temas']." AND ID = ".$_POST['id'];
						
						$query = mysqli_query($conexion, $sql);
						
						if (mysqli_num_rows($query) == 1) {
							
							$preguntas = array();					
							while($row = mysqli_fetch_array($query)){
								$preguntas[] = $row;
							}
							$preguntarespondida = $preguntas[0];
							
							if($_POST['respuesta'] == $preguntarespondida['r_correcta']){
								$_SESSION['aciertos'] += 1;
							}else{
								$_SESSION['fallos'] += 1;
							}
						}
				}
				
				
				//imprimir resultados
				echo "<h1>RESULTADOS<br>";
				echo "Aciertos: ".$_SESSION['aciertos']."<br>";
				echo "Fallos: ".$_SESSION['fallos']."<br></h1>";
				
				//formulario para introducir datos en la BBDD AJAX
				echo "<form id='fresul' name='fresul' method='POST'>";
				echo "Nick de 3 letras: <input type='text' maxlength='3' pattern='[a-zA-Z]{3}' id='nick' name='nick' placeholder='XYZ' required>";
				echo "<input type='hidden' id='aciertos' name='aciertos' value='".$_SESSION['aciertos']."'>";
				echo "<input type='hidden' id='fallos' name='fallos' value='".$_SESSION['fallos']."'>";
				echo "<input type='button' id='guardar' name='guardar' value='Guardar resultados'>";
				echo "</form>";
				echo "<div id='mensaje'></div>";
				unset($_SESSION['aciertos']);
				unset($_SESSION['fallos']);
				unset($_SESSION['preguntas']);
			}else{
				echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página es necesario haber seleccionado un tema.</div>";
			}
		?>
      
    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
