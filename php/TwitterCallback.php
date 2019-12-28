<?php
require 'app/TwitterOAuthHelper.php';
if(!empty($_GET['oauth_token']) && !empty($_SESSION['oauth_token']) && !empty($_SESSION['oauth_token_secret'])){
	$_SESSION['twitter_access_token'] = TwitterOAuthHelper::getAccessToken();
	header('Location: Layout.php');
} else {
	header('Location: Layout.php');
}
?>