<?php
	include 'DbConfig.php';
	if($_POST){
			$mysql = mysqli_connect($server, $user, $pass, $basededatos);
			$email=array_keys($_POST)[0];
			$emailbien = str_replace("_",".",$email);
			// Check connection
			if (!$mysql) {
				die();
			}

			$sql1 = "SELECT * FROM usuarios WHERE email='$emailbien'";
			$query = mysqli_query($mysql, $sql1);

			if (mysqli_num_rows($query) > 0) {
				while($row = mysqli_fetch_assoc($query)){
						$estado=$row['estado'];
						break;	
				}
			}
			
			if($estado=='1'){
				$estadocambiado = 0;
			}else{
				$estadocambiado = 1;
			}
			
			if(!isset($_SESSION)){
				session_start();
			}
			if($_SESSION['email']==$emailbien && $estadocambiado ==0){
				session_destroy();
			}

			
			if(isset($_POST[$email])) {
				$sql="UPDATE usuarios SET estado = '$estadocambiado' WHERE email = '$emailbien'";
				echo "<script>alert('".$sql."');</script>";
				if (mysqli_query($mysql ,$sql)){
					mysqli_close($mysql);
					echo "<script>alert('Estado cambiado.');</script>";
				}else{
					die();
				}
				echo '<script language="javascript">window.location.href="HandlingAccounts.php"</script>';
			}
	}else{
		echo "<script>alert('No se ha podido cambiar el estado.');</script>";
		echo '<script language="javascript">window.location.href="HandlingAccounts.php"</script>';
	}
?>
