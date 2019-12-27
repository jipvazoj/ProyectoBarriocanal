<?php
if(!isset($_SESSION)){
    session_start();
}

require 'vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', 'eydTx2SF7fYJLUYQsBZ0Xfkg2'); 
define('CONSUMER_SECRET', 'AJTRSqBI3iLFyUaLfOgMbLzAI7PBapKPd0uDkuTDkxWUyNslg0'); 
define('OAUTH_CALLBACK', 'https://sw19lab0.000webhostapp.com/Proyecto/php/Layout.php'); 

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);

$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

$_SESSION['oauth_token'] = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$urltwitter = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

?>
<div id='page-wrap'>
<header class='main' id='h1'>
		<script>
			function logout(){
				if(confirm("¿Seguro que quieres cerrar sesión?")){
					location.href = "DecreaseGlobalCounter.php";
				}
			}
		</script>
		<?php include '../php/DbConfig.php' ?>
		<?php

			if(!isset($_SESSION)){
				session_start();
			}
			$usertwitter = $connection->get("account/verify_credentials");
			if(!array_key_exists("errors", $usertwitter)){
				$_SESSION['twitter']=True;
			}
			
			//si se esta log en twitter
			if(isset($_SESSION['twitter'])){
				$twitter = True;
				echo "<span class='right' id='logout'><a onclick='logout();' style='text-decoration: underline; cursor: pointer;'>Logout</a></span>";
				echo "<span class='right'>Bienvenido, ".$usertwitter->screen_name."<img src=".$usertwitter->profile_image_url_https." height='100'/></span>";
				
				
			//si se está logueado de forma nativa	
			}else if(isset($_SESSION['email'])){
				$conexion = mysqli_connect($server, $user, $pass, $basededatos);
				// Check connection
				if (!$conexion) {
					die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
				}

				$sql = "SELECT * FROM usuarios";
				$query = mysqli_query($conexion, $sql);

				if (mysqli_num_rows($query) > 0) {
					
					$encontrado = 0;
					$rutaimagen='';
					while($row = mysqli_fetch_assoc($query)){
						if(strcmp($row['email'],$_SESSION['email'])==0){
							$encontrado=1;
							$nombre = $row['nombre'];
							if($row["imagen"]==''){
								$rutaimagen = '../images/noimage.png';
							}else{
								$rutaimagen = '../images/'.$row["imagen"];
							}
							break;	
						}
					}
					mysqli_close($conexion);
					
				}
				
				if($encontrado){
					include '../php/IncreaseGlobalCounter.php';
					echo "<span class='right' id='logout'><a onclick='logout();' style='text-decoration: underline; cursor: pointer;'>Logout</a></span>";
					echo"<span class='right'>Bienvenido, ".$nombre."<img src=".$rutaimagen." height='100'/></span>";
				}else{
					echo "<span class='right'><a href='/Proyecto/php/SignUp.php'>Registro</a></span> ";
					echo "<span class='right'><a href='/Proyecto/php/LogIn.php'>Login</a></span>";
					echo "<span class='right'><a href=".$urltwitter."><img src='../images/login_with_twitter.png' alt='Sign in with Twitter' title='Sign in with Twitter' width='30'/></a></span>";
				}

				
			//si no se está logueado de ninguna forma
			}else{
				echo "<span class='right'><a href='/Proyecto/php/SignUp.php'>Registro</a></span> ";
				echo "<span class='right'><a href='/Proyecto/php/LogIn.php'>Login</a></span>";
				echo "<span class='right'><a href=".$urltwitter."><img src='../images/login_with_twitter.png' alt='Sign in with Twitter' title='Sign in with Twitter' width='30'/></a></span>";
			}
		?>
</header>
<nav class='main' id='n1' role='navigation'>
			<?php
				if(!isset($_SESSION)){
					session_start();
				}
				if(!isset($_SESSION['email'])){
					echo"<span><a href='Layout.php'>Inicio</a></span>";
					echo"<span><a href='Credits.php'>Créditos</a></span>";
					echo"<span><a href='ResetPass.php'>Restablecer contraseña</a></span>";
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
								$tipo = $row['tipo'];
								break;	
							}
						}
						
						if($encontrado&&($tipo==1||$tipo==2)){
							echo"<span><a href='Layout.php'>Inicio</a></span>";
							echo"<span><a href='HandlingQuizesAjax.php'>Gestionar preguntas</a></span>";
							echo"<span><a href='ClientGetQuestion.php'>Obtener Preguntas</a></span>";
							echo"<span><a href='Credits.php'>Créditos</a></span>";
						}else if($encontrado&&$tipo==3){
							echo"<span><a href='Layout.php'>Inicio</a></span>";
							echo"<span><a href='HandlingAccounts.php'>Gestionar usuarios</a></span>";
							echo"<span><a href='Credits.php'>Créditos</a></span>";
						}else{
							echo"<span><a href='Layout.php'>Inicio</a></span>";
							echo"<span><a href='Credits.php'>Créditos</a></span>";
							echo"<span><a href='ResetPass.php'>Restablecer contraseña</a></span>";
						}
						mysqli_close($conexion);
						
					}
				}
			?>
</nav>

