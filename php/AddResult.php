<?php
	if (isset($_SESSION)){
		session_start();
	}
	include '../php/DbConfig.php';
	function validar($nick){
		if(preg_match("/[a-zA-Z]{3}/",$nick)==0){
			return false;
		}
		return true;
	}
	if($_POST){
		echo "<div>";
		$validacion = validar($_POST['nick']);
		if($validacion){
			$mysql = mysqli_connect($server, $user, $pass, $basededatos);
			
			$sql="INSERT INTO resultados (nombre,aciertos,fallos) VALUES ('$_POST[nick]','$_POST[aciertos]', '$_POST[fallos]')";
			if (!mysqli_query($mysql ,$sql)){
				die('<div style="color:white; background-color:#ff0000">Error en el servidor, inténtalo otra vez.</div>');
			}
			mysqli_close($mysql);
			
		}

		echo "</div>";
		if($validacion){
			echo "<div style='color:white; background-color:#00cc66'>¡Resultados guardados con éxito!</div>";
		}else{
			echo "<div style='color:white; background-color:#ff0000'>Error en el nick, inténtalo otra vez.</div>";
		}
	}else{
		echo "<div style='color:white; background-color:#ff0000'>No se han añadido los resultados a la base de datos.</div>";
	}
?>