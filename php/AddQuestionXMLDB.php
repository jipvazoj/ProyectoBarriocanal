<?php
	include '../php/DbConfig.php';
	function validar($email, $enunciado, $correcta, $incorrecta1, $incorrecta2, $incorrecta3, $complejidad, $tema){
		if(preg_match("/\S+@\S+\.\S+/",$email)==0){
			echo '<div style="color:white; background-color:#ff0000">Fallo en el email.</div>';
			return false;
		}
		if(preg_match("/^.{10,}$/",$enunciado)==0){
			echo '<div style="color:white; background-color:#ff0000">Fallo en el enunciado.</div>';
			return false;
		}
		$valores = array('valores'=>array('min_range'=>1,'max_range'=>3));
		if(filter_var($complejidad, FILTER_VALIDATE_INT, $valores ) == false){
			echo '<div style="color:white; background-color:#ff0000">Fallo en la complejidad.</div>';
			return false;
		}
		if(strlen($correcta) == 0 || strlen($incorrecta1) == 0 || strlen($incorrecta2) == 0 || strlen($incorrecta3) == 0 || strlen($tema) == 0){
			echo '<div style="color:white; background-color:#ff0000">Algunos campos están vacíos.</div>';
			return false;
		}
		return true;
	}
	if($_POST){
		echo "<div>";
		$validacion = validar($_POST['email'],$_POST['enunciado'], $_POST['correcta'], $_POST['incorrecta1'], $_POST['incorrecta2'], $_POST['incorrecta3'],
		$_POST['complejidad'], $_POST['tema']);
		if($validacion){
			$mysql = mysqli_connect($server, $user, $pass, $basededatos);
			if(isset($_FILES['foto'])) {
				$imagen = $_FILES['foto']['name'];
				$imagenTMP = $_FILES['foto']['tmp_name'];
				$carpeta = "/storage/ssd5/138/10866138/public_html/Proyecto/images/";
				move_uploaded_file($imagenTMP, $carpeta.$imagen);
			}
			$enunciado = htmlspecialchars($_POST['enunciado']);
			$correcta = htmlspecialchars($_POST['correcta']);
			$incorrecta1 = htmlspecialchars($_POST['incorrecta1']);
			$incorrecta2 = htmlspecialchars($_POST['incorrecta2']);
			$incorrecta3 = htmlspecialchars($_POST['incorrecta3']);
			$tema = htmlspecialchars($_POST['tema']);
			$sql="INSERT INTO preguntas (ID, email, enunciado, r_correcta, r_in1, r_in2, r_in3, complejidad, tema, img,likes,dislikes) VALUES
			(NULL, '$_POST[email]','$enunciado', '$correcta', '$incorrecta1', '$incorrecta2', '$incorrecta3',
			'$_POST[complejidad]', '$tema', '$imagen',0,0)";
			if (!mysqli_query($mysql ,$sql)){
				die('<div style="color:white; background-color:#ff0000">Error en el servidor, inténtalo otra vez.</div>');
			}
			mysqli_close($mysql);
			
			//XML
			try{
				libxml_use_internal_errors(TRUE);
				$xml = simplexml_load_file('../xml/Questions.xml');
				$pregunta = $xml->addChild('assessmentItem');
				$pregunta->addAttribute('subject',$_POST['tema']);
				$pregunta->addAttribute('author',$_POST['email']);
				$enunciado = $pregunta->addChild('itemBody');
				$enunciado->addChild('p',$_POST['enunciado']);
				$correcta = $pregunta->addChild('correctResponse');
				$correcta->addChild('value',$_POST['correcta']);
				$incorrectas = $pregunta->addChild('incorrectResponses');
				$incorrectas->addChild('value',$_POST['incorrecta1']);
				$incorrectas->addChild('value',$_POST['incorrecta2']);
				$incorrectas->addChild('value',$_POST['incorrecta3']);
				$xml->asXML('../xml/Questions.xml');
				echo"<div style='color:white; background-color:#00cc66'>¡Pregunta guardada con éxito en XML!.</div>";
			}catch(Exception $e){
				echo "<div style='color:white; background-color:#ff0000'>No se ha insertado la pregunta en XML, inténtelo otra vez.</div>";
			}
			//FIN XML
		}

		echo "</div>";
		if($validacion){
			echo "<div style='color:white; background-color:#00cc66'>¡Pregunta guardada con éxito en BBDD!</div>";
		}else{
			echo "<div style='color:white; background-color:#ff0000'>Error en los campos, inténtalo otra vez.</div>";
		}
	}else{
		echo "<div style='color:white; background-color:#ff0000'>No se ha añadido ninguna pregunta a la base de datos.</div>";
	}
?>