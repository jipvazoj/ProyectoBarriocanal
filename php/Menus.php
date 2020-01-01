<?php
if(!isset($_SESSION)){
    session_start();
}

require 'TwitterManager.php';
$manager = new TwitterManager();

require 'fb_init.php';

require 'googleConfig.php';

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
			
			//obtener url twitter
			$usertwitter = $manager->signedIn();
			try{
				$urltwitter = $manager->getAuthUrl();
			}catch(Exception $e){
				echo"";
			}
			//obtener url facebook
			$helper = $fb->getRedirectLoginHelper();
			$permissions = array('scope' => 'email');
			$urlfacebook = $helper->getLoginUrl('https://sw19lab0.000webhostapp.com/Proyecto/php/FacebookCallback.php', $permissions);
	
	        //obtener url google
			$urlGoogle = $clienteGoogle->createAuthUrl();
			$code = isset($_GET['code']) ? $_GET['code'] : NULL;
			
			//si se esta log en twitter
			if($usertwitter){
				$_SESSION['email']=$usertwitter->email;
				include '../php/IncreaseGlobalCounter.php';
				echo "<span class='right' id='logout'><a onclick='logout();' style='text-decoration: underline; cursor: pointer; color:blue;'>Logout</a> </span>";
				echo "<span class='right'>Bienvenido, ".$usertwitter->name."<img src=".$usertwitter->profile_image_url_https." height='70'/></span>";
				
			//si se esta log en facebook
			}else if(isset($_SESSION['facebook_access_token'])){
				$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
				try {
					$response = $fb->get('/me?fields=id,name,email,picture');
					$userFacebook = $response->getGraphUser();
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
					// When Graph returns an error
					echo 'Graph returned an error: ' . $e->getMessage();
					exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
					// When validation fails or other local issues
					echo 'Facebook SDK returned an error: ' . $e->getMessage();
					exit;
				}
				
				$_SESSION['email']=$userFacebook->getEmail();
				include '../php/IncreaseGlobalCounter.php';
				echo "<span class='right' id='logout'><a onclick='logout();' style='text-decoration: underline; cursor: pointer; color:blue;'>Logout</a> </span>";
				echo "<span class='right'>Bienvenido, ".$userFacebook->getName()."<img src=".$userFacebook->getPicture()->getUrl()." height='70'/></span>";
			//si esta log en google
			}else if(isset($code)||isset($_SESSION['google_access_token'])) {
			    if(isset($code)){
                    try {
                        $token = $clienteGoogle->fetchAccessTokenWithAuthCode($code);
                        $_SESSION['google_access_token']=$token;
                        $clienteGoogle->setAccessToken($token);
                    }catch (Exception $e){
                        echo '';
                    }
                    try {
                        $userGoogle = $clienteGoogle->verifyIdToken();
                    }catch (Exception $e) {
                        echo '';
                    }
                    if(isset($userGoogle)){
					//logged in
    					$_SESSION['email']=$userGoogle['email'];
    					$_SESSION['google_name']=$userGoogle['name'];
    					$_SESSION['google_picture']=$userGoogle['picture'];
                    }
			    }
				
				
					include '../php/IncreaseGlobalCounter.php';
					echo "<span class='right' id='logout'><a onclick='logout();' style='text-decoration: underline; cursor: pointer; color:blue;'>Logout</a> </span>";
					echo "<span class='right'>Bienvenido, ".$_SESSION['google_name']."<img src=".$_SESSION['google_picture']." height='70'/></span>";
			
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
					echo "<span class='right' id='logout'><a onclick='logout();' style='text-decoration: underline; cursor: pointer; color:blue;'>Logout</a> </span>";
					echo"<span class='right'>Bienvenido, ".$nombre."<img src=".$rutaimagen." height='70'/></span>";
				}else{
					echo "<span class='right'><a href='/Proyecto/php/SignUp.php'>Registro</a></span> ";
					echo "<span class='right'><a href='/Proyecto/php/LogIn.php'>Login</a></span>";
					echo "<span class='right'><a href=".$urltwitter."><img src='../images/login_with_twitter.png' alt='Sign in with Twitter' title='Sign in with Twitter' width='30'/></a></span>";
					echo "<span class='right'><a href=".$urlfacebook."><img src='../images/login_with_facebook.png' alt='Sign in with Facebook' title='Sign in with Facebook' width='30'/></a></span>";
					echo "<span class='right'><a href=".$urlGoogle."><img src='../images/login_with_google.png' alt='Sign in with Google' title='Sign in with Google' width='30'/></a></span>";
				}

				
			//si no se está logueado de ninguna forma
			}else{
				echo "<span class='right'><a href='/Proyecto/php/SignUp.php'>Registro</a></span> ";
				echo "<span class='right'><a href='/Proyecto/php/LogIn.php'>Login</a></span>";
				echo "<span class='right'><a href=".$urltwitter."><img src='../images/login_with_twitter.png' alt='Sign in with Twitter' title='Sign in with Twitter' width='30'/></a></span>";
				echo "<span class='right'><a href=".$urlfacebook."><img src='../images/login_with_facebook.png' alt='Sign in with Facebook' title='Sign in with Facebook' width='30'/></a></span>";
				echo "<span class='right'><a href=".$urlGoogle."><img src='../images/login_with_google.png' alt='Sign in with Google' title='Sign in with Google' width='30'/></a></span>";
			}
		?>
</header>
<nav class='main' id='n1' role='navigation'>
			<?php
				if(!isset($_SESSION)){
					session_start();
				}
				if(!isset($_SESSION['email'])&&!isset($_SESSION['twitter_access_token'])&&!isset($_SESSION['google_access_token'])){
					echo"<span><a href='Layout.php'>Inicio</a></span>";
					echo"<span><a href='Credits.php'>Créditos</a></span>";
					echo"<span><a href='ResetPass.php'>Restablecer contraseña</a></span>";
				}else if(isset($_SESSION['twitter_access_token'])){
					echo"<span><a href='Layout.php'>Inicio</a></span>";
					echo"<span><a href='HandlingQuizesAjax.php'>Gestionar preguntas</a></span>";
					echo"<span><a href='ClientGetQuestion.php'>Obtener Preguntas</a></span>";
					echo"<span><a href='Credits.php'>Créditos</a></span>";
				}else if(isset($_SESSION['facebook_access_token'])){
					echo"<span><a href='Layout.php'>Inicio</a></span>";
					echo"<span><a href='HandlingQuizesAjax.php'>Gestionar preguntas</a></span>";
					echo"<span><a href='ClientGetQuestion.php'>Obtener Preguntas</a></span>";
					echo"<span><a href='Credits.php'>Créditos</a></span>";
				}else if(isset($_SESSION['google_access_token'])){
					echo"<span><a href='Layout.php'>Inicio</a></span>";
					echo"<span><a href='HandlingQuizesAjax.php'>Gestionar preguntas</a></span>";
					echo"<span><a href='ClientGetQuestion.php'>Obtener Preguntas</a></span>";
					echo"<span><a href='Credits.php'>Créditos</a></span>";
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

