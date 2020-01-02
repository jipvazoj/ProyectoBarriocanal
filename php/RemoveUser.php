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
			
			
			
		if(isset($_SESSION['email'])){
			$conexion = mysqli_connect($server, $user, $pass, $basededatos);
				// Check connection
				if (!$conexion) {
					die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
				}

				$sqlUsuario = "SELECT * FROM usuarios";
				$queryUsuario = mysqli_query($conexion, $sqlUsuario);

				if (mysqli_num_rows($queryUsuario) > 0) {
					
					$encontrado = 0;
					while($row = mysqli_fetch_assoc($queryUsuario)){
						if(strcmp($row['email'],$_SESSION['email'])==0){
							$encontrado=1;
							$tipo = $row['tipo'];
							if($row['estado']=='1'){
								$estado = 'Activo';
							}else if($row['estado']=='0'){
								$estado = 'Bloqueado';
							}
							mysqli_close($conexion);
							break;	
						}
					}
					
					if($encontrado&&$tipo==3&&$estado=='Activo'){
									
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
						echo "<script>alert('El usuario no está registrado o no tiene los privilegios suficientes.');</script>";
						echo '<script language="javascript">window.location.href="Layout.php"</script>';
					}
						
				}else{
					echo "<script>alert('El usuario no está registrado o no tiene los privilegios suficientes.');</script>";	
					echo '<script language="javascript">window.location.href="Layout.php"</script>';					
				}
		}else{
			echo "<script>alert('El usuario no está registrado o no tiene los privilegios suficientes.');</script>";
			echo '<script language="javascript">window.location.href="Layout.php"</script>';
		}
			
			
			
	}else{
		echo "<script>alert('No se ha podido borrar el usuario.');</script>";
		echo '<script language="javascript">window.location.href="HandlingAccounts.php"</script>';
	}
?>
