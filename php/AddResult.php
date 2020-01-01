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
	
	function nickunico($nombre){
	    include '../php/DbConfig.php';
	    $mysql = mysqli_connect($server, $user, $pass, $basededatos);
		$nick = strtoupper($nombre);
		$obtener="SELECT * FROM resultados WHERE nombre='".$nick."'";
		$query = mysqli_query($mysql ,$obtener);
		if (mysqli_num_rows($query) > 0) {
		    return false;
		}
		return true;
	}
		
	if($_POST){
		echo "<div>";
		$validacion = validar($_POST['nick']);
		$nickunico = nickunico($_POST['nick']);
		if($validacion){
			$mysql = mysqli_connect($server, $user, $pass, $basededatos);
			$nick = strtoupper($_POST['nick']);
			if($nickunico){
			    $porcentaje = $_POST['aciertos'] / ($_POST['aciertos']+$_POST['fallos']);
			    $query="INSERT INTO resultados (nombre,aciertos,fallos,porcentaje) VALUES ('".$nick."',$_POST[aciertos],$_POST[fallos],".$porcentaje.")";
			}else{
			    $sql = "SELECT * FROM resultados WHERE nombre = '".$nick."'";
			    $consulta = mysqli_query($mysql, $sql);
			    if (mysqli_num_rows($consulta) == 1) {
			        while($row = mysqli_fetch_assoc($consulta)){
			            $aciertos = $row['aciertos']; 
			            $fallos = $row['fallos'];
			        }
			        //sumar
			        $aciertos += $_POST['aciertos'];
			        $fallos += $_POST['fallos'];
			        $porcentaje = $aciertos / ($aciertos + $fallos);
			        $query = "UPDATE resultados SET aciertos = ".$aciertos.", fallos = ".$fallos.", porcentaje = ".$porcentaje."  WHERE nombre = '".$nick."'";
			    }else{
			        die('<div style="color:white; background-color:#ff0000">Error en el servidor, inténtalo otra vez.</div>');
			    }
			}
			if (!mysqli_query($mysql ,$query)){
				die('<div style="color:white; background-color:#ff0000">Error en el servidor, inténtalo otra vez.</div>');
			}
			mysqli_close($mysql);
			
		}

		echo "</div>";
		if($validacion&&$nickunico){
			echo "<div id='mensajecorrecto' style='color:white; background-color:#00cc66'>¡Resultados guardados con éxito!<br>Comprueba si has entrado en la tabla de top 10 quizzers <a href='https://sw19lab0.000webhostapp.com/Proyecto/php/Layout.php'>aquí</a></div>";
		}else if($validacion && !$nickunico){
			echo "<div id='mensajecorrecto' style='color:white; background-color:#00cc66'>El nick ya existe, se han añadido los resultados al nick.<br>Comprueba si has entrado en la tabla de top 10 quizzers <a href='https://sw19lab0.000webhostapp.com/Proyecto/php/Layout.php'>aquí</a></div>";
		}else{
		    echo "<div style='color:white; background-color:#ff0000'>Error en el nick, inténtalo otra vez.</div>";
		}
	}else{
		echo "<div style='color:white; background-color:#ff0000'>No se han añadido los resultados a la base de datos.</div>";
	}
?>