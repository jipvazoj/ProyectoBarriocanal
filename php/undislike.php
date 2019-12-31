<?php

	if(!isset($_SESSION)){
		session_start();
	}
	if(!isset($_SESSION['dislike'])){ 
		$_SESSION['dislike'] = []; 
	}

	include '../php/DbConfig.php';
	
	function remove_ele($ele, $session_array){
		$index = array_search($ele, $session_array);
		array_splice($session_array, $index, 1);
		return $session_array;
	}

	function is_ajax_request() {
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	}

	if(!is_ajax_request()) { 
		exit; 
	}
	$raw_id = isset($_POST['id']) ? $_POST['id'] : '';
	if(preg_match("/likes-(\d+)/", $raw_id, $matches)){
		$id = $matches[1];
		if(in_array($id, $_SESSION['dislike'])){
			$_SESSION['dislike'] = remove_ele($id, $_SESSION['dislike']);
		}
		//BASE DE DATOS
			
		// Create connection
		$connection = mysqli_connect($server, $user, $pass, $basededatos);
		// Check connection
		if (!$connection) {
			die('<div style="color:white; background-color:#ff0000">Error al conectar con la base de datos </div>');
		}
		$sql = "UPDATE preguntas SET dislikes = dislikes - 1 WHERE ID = ".$id;
		$query = mysqli_query($connection, $sql);
		echo "true";
	}else{
		echo "false";
	}
?>