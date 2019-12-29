<?php include '../php/Menus.php' ?>
<!DOCTYPE html>
<html>
<head>
	<?php include '../html/Head.html'?>
	<?php include '../php/DbConfig.php' ?>
    
</head>
<body>
	<section class="main" id="s1">
		<?php
		if(!isset($_SESSION)){
			session_start();
		}
		if(!isset($_SESSION['email'])&&!isset($_SESSION['twitter_access_token'])&&!isset($_SESSION['facebook_access_token'])&&!isset($_SESSION['facebook_access_token'])){
			echo "<div style='color:white; background-color:#ff0000'>Para acceder a esta página se necesita haber iniciado sesión.</div>";
		}else if(isset($_SESSION['twitter_access_token'])||isset($_SESSION['facebook_access_token'])||isset($_SESSION['google_access_token'])){
			echo "	<div>
								<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; text-align:center;'> <p style='text-align:center;'>OBTENER PREGUNTA</p>
									<form name='datos' ID='datos' action='ClientGetQuestion.php' method='POST'>
										ID: <input type='number' id='id' name='id' size=55><br>
										<input type='submit' id='ver' name='ver' value='Obtener pregunta'>	
									</form>
								</div>
							</div>";
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
					echo "	<div>
								<div style='border-style:solid;border-color:black; font-family: Verdana,Geneva,sans-serif; text-align:center;'> <p style='text-align:center;'>OBTENER PREGUNTA</p>
									<form name='datos' ID='datos' action='ClientGetQuestion.php' method='POST'>
										ID: <input type='number' id='id' name='id' size=55><br>
										<input type='submit' id='ver' name='ver' value='Obtener pregunta'>	
									</form>
								</div>
							</div>";
				}else if($encontrado&&$tipo==3){
					echo"<div style='color:white; background-color:#ff0000'>El usuario no tiene los privilegios estipulados para acceder a esta página.</div>";
				}else{
					echo"<div style='color:white; background-color:#ff0000'>El usuario no está registrado.</div>";
				}
				mysqli_close($conexion);
				
			}else{
				echo"<div style='color:white; background-color:#ff0000'>El usuario no está registrado.</div>";
			}	
		}
		?>	
		<?php
			
			
			if(isset($_POST['id'])){
				$id = (int) $_POST['id'];
                require_once('../lib/nusoap.php');
                require_once('../lib/class.wsdlcache.php');

				$soapclient = new nusoap_client( 'http://localhost/Proyecto/php/GetQuestionWS.php?wsdl',true);
                $result = $soapclient->call('ObtenerPregunta', array('x'=>$id));

				if($result['autor']==''){
					echo "<h2>No existe ninguna pregunta con el ID ".$id.".</h2>";
				}else{
					echo "<h2> ID: ".$id."</h1>";
					echo "<h2> Autor: ".$result['autor']."</h2>";
					echo "<h2> Enunciado: ".$result['enunciado']."</h2>";
					echo "<h2> Respuesta Correcta: ".$result['correcta']."</h2>";
				}
			}
			
			
		?>
  </section>
  <?php include '../html/Footer.html' ?>
</body>
</html>