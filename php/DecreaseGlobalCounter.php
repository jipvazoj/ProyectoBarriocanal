<?php
	if(!isset($_SESSION)){
		session_start();
	}

	//si se esta log twitter
	if(isset($_SESSION['access_token_twitter'])){
		unset($_SESSION['oauth_token']);
		unset($_SESSION['oauth_token_secret']);
		session_destroy();
		header('Location: Layout.php');

	//si se esta log nativo
	}else if(isset($_SESSION['email'])){
		
		try{
			libxml_use_internal_errors(TRUE);
			$xml = simplexml_load_file('../xml/Counter.xml');
			$mail = $_SESSION['email'];
			$index = 0;
			foreach ($xml->children() as $users){
				if($users == $mail){
					unset($xml->user[$index]);
					break;
				}
				$index++;
			}
			$xml->asXML('../xml/Counter.xml');
			session_destroy();
			header('Location: Layout.php');
		}catch(Exception $e){
			echo "<div style='color:white; background-color:#ff0000'>Error al cerrar sesión, inténtelo otra vez.</div>";
		}		
	}
?>

