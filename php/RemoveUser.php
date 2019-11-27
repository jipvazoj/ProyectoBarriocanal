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
			if(!isset($_SESSION)){
				session_start();
			}
			if($_SESSION['email']==$emailbien){
				session_destroy();
			}
			if(isset($_POST[$email])) {
				$sql="DELETE FROM usuarios WHERE email = '$emailbien'";
				echo "<script>alert('".$sql."');</script>";
				if (mysqli_query($mysql ,$sql)){
					echo "<script>alert('Usuario borrado.');</script>";
				}else{
					die();
				}
				mysqli_close($mysql);
				echo '<script language="javascript">window.location.href="HandlingAccounts.php"</script>';
			}
	}else{
		echo "<script>alert('No se ha podido borrar el usuario.');</script>";
		echo '<script language="javascript">window.location.href="HandlingAccounts.php"</script>';
	}
?
