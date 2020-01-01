<?php include '../php/Menus.php' ?>
<?php include '../php/DbConfig.php'?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<script src="../js/jquery-3.4.1.min.js"></script>
	<script src="../js/AddResultAjax.js"></script>
	<link rel="stylesheet" type="text/css" href="../styles/Likes.css" media="screen" />
	<link href='https://fonts.googleapis.com/css?family=Press+Start+2P' rel='stylesheet' type='text/css'>
	<style>
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
		#fuente8b{
			font-family: 'Press Start 2P', cursive;
		}
		.boton8b{
			font-family: 'Press Start 2P', cursive;
			background-color: black;
			border: 1px solid lime;
			color: lime;
			padding: 10px 20px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 9px;
		}
		.boton8b:hover{
			font-family: 'Press Start 2P', cursive;
			background-color: lime;
			border: 1px solid black;
			color: black;
			padding: 10px 20px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 9px;
		}
		
		.form-radio
		{
			-webkit-appearance: none;
			-moz-appearance: none;
			appearance: none;
			display: inline-block;
			position: relative;
			background-color: black;
			color: lime;
			top: 10px;
			height: 30px;
			width: 30px;
			border: 0;
			border-radius: 50px;
			cursor: pointer;     
			margin-right: 7px;
			outline: none;
			border: 2px solid lime;
		}
		.form-radio:checked::before
		{
			position: absolute;
			font-weight: bold;
			left: 11px;
			top: 7px;
			content: '\02143';
			transform: rotate(40deg);
		}
		.form-radio:hover
		{
			background-color: lime;
			color: black;
		}
		.form-radio:checked
		{
			background-color: lime;
			color: black;
			border: 1px solid black;
		}
		label
		{
			font: 'Press Start 2P', cursive;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
			cursor: pointer;
		} 
		
	</style>
</head>
<body>
  <section style='overflow-y:scroll;' class="main" id="s1">
    <div>

      <h2 id='titulojuego'>Quiz: el juego de las preguntas</h2>
	  
		
		
		
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
			
			if(!isset($_SESSION['like'])) { 
				$_SESSION['like'] = []; 
			}
			
			if(!isset($_SESSION['dislike'])) { 
				$_SESSION['dislike'] = []; 
			}

			function liked($id){
				return in_array($id, $_SESSION['like']);
			}
			
			function disliked($id){
				return in_array($id, $_SESSION['dislike']);
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
						$sql = "SELECT * FROM preguntas WHERE tema = '".$_POST['temas']."' AND ID = ".$_POST['id'];
						
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
				$conexion2 = mysqli_connect($server, $user, $pass, $basededatos);
				// Check connection
				if (!$conexion2) {
					die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
				}
				if(isset($_SESSION['preguntas'])){
					$sql = "SELECT * FROM preguntas WHERE tema = '".$_POST['temas']."' AND ID NOT IN (".$_SESSION['preguntas'].")";
				}else{
					$sql = "SELECT * FROM preguntas WHERE tema = '".$_POST['temas']."'";
				}
				$query = mysqli_query($conexion2, $sql);
				
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
					
					echo "Se ha elegido una pregunta aleatoria del tema ".$_POST['temas'].".";
					echo "<p>En un rango de 1 a 3 la pregunta tiene una complejidad de ".$preguntaseleccionada['complejidad']."</p>";
					echo "<p>La pregunta ha sido aportada por ".$preguntaseleccionada['email']."</p>";
					echo "<h1 id='fuente8b'>".htmlspecialchars($preguntaseleccionada['enunciado'])."</h1>";
					if($preguntaseleccionada['img']!=''){
						$rutaimagen = '../images/'.$preguntaseleccionada['img'];
						echo "<p><img src=".$rutaimagen." height='100'/></p>";
					}
					echo "<form method='POST' action='Pregunta.php'>";
					echo "<input type='hidden' id='id' name='id' value='".$preguntaseleccionada['ID']."'>";
					echo "<input type='hidden' id='temas' name='temas' value='".$_POST['temas']."'>";
					?>
					<input class='form-radio' id='uno' type='radio' name='respuesta' value='<?php echo $respuestas[0];?>' required>
							<label style='font-weight:bold;' for="uno"><?php echo $respuestas[0];?></label><br>
					<input class='form-radio' id='dos' type='radio' name='respuesta' value='<?php echo $respuestas[1];?>'>
							<label style='font-weight:bold;' for="dos"><?php echo $respuestas[1];?></label><br>
					<input class='form-radio' id='tres' type='radio' name='respuesta' value='<?php echo $respuestas[2];?>'>
							<label style='font-weight:bold;' for="tres"><?php echo $respuestas[2];?></label><br>
					<input class='form-radio' id='cuatro' type='radio' name='respuesta' value='<?php echo $respuestas[3];?>'>
							<label style='font-weight:bold;' for="cuatro"><?php echo $respuestas[3];?></label><br>
					<br>
					<p><input class='boton8b'  type='submit' id='jugar' name='jugar' value='Contestar a otra pregunta'>
					<input class='boton8b'  type='submit' id='terminar' name='terminar' value='Terminar y ver resultados'></p></form>
					
					
					<?php
					//LIKE DISLIKE
					?>
					<table style= "border:none;"><tr style= "border:none;"><td style="border:none;text-align:right">
					<div id="likes-<?php echo $preguntaseleccionada['ID'];?>" class="<?php if(liked($preguntaseleccionada['ID'])){ echo "liked"; } ?>">
						<button id="like" class="like-button">Like</button>
						<button id="unlike" class="unlike-button">Like</button>
					</div></td><td style='border:none;'>
					<div id="dislikes-<?php echo $preguntaseleccionada['ID'];?>" class="<?php if(disliked($preguntaseleccionada['ID'])){ echo "disliked"; } ?>">
						<button id="dislike" class="dislike-button">Dislike</button>
						<button id="undislike" class="undislike-button">Dislike</button>
					</div></td></tr>
					</table>

					<?php
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
					
					echo "Se ha elegido una pregunta aleatoria del tema ".$_POST['temas'].".";
					echo "<p>En un rango de 1 a 3 la pregunta tiene una complejidad de ".$preguntaseleccionada['complejidad']."</p>";
					echo "<p>La pregunta ha sido aportada por ".$preguntaseleccionada['email']."</p>";
					echo "<h1>".$preguntaseleccionada['enunciado']."</h1>";
					if($preguntaseleccionada['img']!=''){
						$rutaimagen = '../images/'.$preguntaseleccionada['img'];
						echo "<p><img src=".$rutaimagen." height='100'/></p>";
					}
					echo "<form method='POST' action='Pregunta.php'>";
					echo "<input type='hidden' id='id' name='id' value='".$preguntaseleccionada['ID']."'>";
					echo "<input type='hidden' id='temas' name='temas' value='".$_POST['temas']."'>";?>
					<input class='form-radio' id='uno' type='radio' name='respuesta' value='<?php echo $respuestas[0];?>' required>
							<label style='font-weight:bold;' for="uno"><?php echo $respuestas[0];?></label><br>
					<input class='form-radio' id='dos' type='radio' name='respuesta' value='<?php echo $respuestas[1];?>'>
							<label style='font-weight:bold;' for="dos"><?php echo $respuestas[1];?></label><br>
					<input class='form-radio' id='tres' type='radio' name='respuesta' value='<?php echo $respuestas[2];?>'>
							<label style='font-weight:bold;' for="tres"><?php echo $respuestas[2];?></label><br>
					<input class='form-radio' id='cuatro' type='radio' name='respuesta' value='<?php echo $respuestas[3];?>'>
							<label style='font-weight:bold;' for="cuatro"><?php echo $respuestas[3];?></label><br>
					<br>
					<p><input class='boton8b' type='submit' id='terminar' name='terminar' value='Terminar'></p></form>
					
					<?php
					//LIKE DISLIKE
					?>
					<table style= "border:none;"><tr style= "border:none;"><td style="border:none;text-align:right">
					<div id="likes-<?php echo $preguntaseleccionada['ID'];?>" class="<?php if(liked($preguntaseleccionada['ID'])){ echo "liked"; } ?>">
						<button id="like" class="like-button">Like</button>
						<button id="unlike" class="unlike-button">Like</button>
					</div></td><td style='border:none;'>
					<div id="dislikes-<?php echo $preguntaseleccionada['ID'];?>" class="<?php if(disliked($preguntaseleccionada['ID'])){ echo "disliked"; } ?>">
						<button id="dislike" class="dislike-button">Dislike</button>
						<button id="undislike" class="undislike-button">Dislike</button>
					</div></td></tr></table>

					<?php
					
				}else{
					echo "No existen preguntas del tema seleccionado.";
				}
				
		
				echo "</div>";
				
				?>
				<script language="javascript">
					//LIKE
					function likeButton() {
						var parentEl = this.parentElement;
						var xhr = new XMLHttpRequest();
						xhr.open('POST', 'like.php', true);
						// form data is sent appropriately as a POST request
						xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
						xhr.onreadystatechange = function () {
							if(xhr.readyState == 4 && xhr.status == 200) {
								var result = xhr.responseText;
								console.log('Result: ' + result);
								if(result == "true"){
									parentEl.classList.add('liked');
									if(document.getElementById('dislike').parentNode.classList.contains('disliked')){
										document.getElementById('dislike').parentNode.classList.remove('disliked');
									}
									document.getElementById('dislike').disabled = true;
									document.getElementById('undislike').disabled = true;
								}
							}
						};
						xhr.send("id=" + parentEl.id);
					}

					var likebuttons = document.getElementsByClassName("like-button");
					for(i=0; i < likebuttons.length; i++) {
						likebuttons.item(i).addEventListener("click", likeButton);
					}

					//UNLIKE
					function unlikeButton() {
						var parentEl = this.parentElement;
						var xhr = new XMLHttpRequest();
						xhr.open('POST', 'unlike.php', true);
						// form data is sent appropriately as a POST request
						xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
						xhr.onreadystatechange = function () {
							if(xhr.readyState == 4 && xhr.status == 200) {
								var result = xhr.responseText;
								console.log('Result: ' + result);
								if(result == "true"){
									parentEl.classList.remove('liked');
									document.getElementById('dislike').disabled = false;
									document.getElementById('undislike').disabled = false;
								}
							}
						};
						xhr.send("id=" + parentEl.id);
					}

					var unlikebuttons = document.getElementsByClassName("unlike-button");
					for(i=0; i < unlikebuttons.length; i++) {
						unlikebuttons.item(i).addEventListener("click", unlikeButton);
					}

					//DISLIKE
					function dislikeButton() {
						var parentEl = this.parentElement;
						var xhr = new XMLHttpRequest();
						xhr.open('POST', 'dislike.php', true);
						// form data is sent appropriately as a POST request
						xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
						xhr.onreadystatechange = function () {
							if(xhr.readyState == 4 && xhr.status == 200) {
								var result = xhr.responseText;
								console.log('Result: ' + result);
								if(result == "true"){
									parentEl.classList.add('disliked');
									console.log(document.getElementsByClassName('like-button').parentNode);
									if(document.getElementById('like').parentNode.classList.contains('liked')){
										document.getElementById('like').parentNode.classList.remove('liked');
									}
									document.getElementById('like').disabled = true;
									document.getElementById('unlike').disabled = true;
								}
							}
						};
						xhr.send("id=" + parentEl.id);
					}

					var dislikebuttons = document.getElementsByClassName("dislike-button");
					for(i=0; i < dislikebuttons.length; i++) {
						dislikebuttons.item(i).addEventListener("click", dislikeButton);
					}

					//UNDISLIKE
					function undislikeButton() {
						var parentEl = this.parentElement;
						var xhr = new XMLHttpRequest();
						xhr.open('POST', 'undislike.php', true);
						// form data is sent appropriately as a POST request
						xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
						xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
						xhr.onreadystatechange = function () {
							if(xhr.readyState == 4 && xhr.status == 200) {
								var result = xhr.responseText;
								console.log('Result: ' + result);
								if(result == "true"){
									parentEl.classList.remove('disliked');
									document.getElementById('like').disabled = false;
									document.getElementById('unlike').disabled = false;
								}
							}
						};
						xhr.send("id=" + parentEl.id);
					}

					var undislikebuttons = document.getElementsByClassName("undislike-button");
					for(i=0; i < undislikebuttons.length; i++) {
						undislikebuttons.item(i).addEventListener("click", undislikeButton);
					}


				</script>
				
				<?php
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
						$sql = "SELECT * FROM preguntas WHERE tema = '".$_POST['temas']."' AND ID = ".$_POST['id'];
						
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
				unset($_SESSION['like']);
				unset($_SESSION['dislike']);
			}else{
				echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página es necesario haber seleccionado un tema.</div>";
			}
		?>
      
    </div>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>
