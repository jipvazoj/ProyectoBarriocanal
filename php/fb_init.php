<?php
if(!isset($_SESSION)){
	session_start();
}
require 'facebook-sdk-v5/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '999197630456252',
  'app_secret' => '0777cd7bb0f69d12d7d3edf482a24c69',
  'default_graph_version' => 'v2.2',
  ]);