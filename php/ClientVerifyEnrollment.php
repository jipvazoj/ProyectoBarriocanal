<?php
	require_once('../lib/nusoap.php');
	require_once('../lib/class.wsdlcache.php');
	$soapclient = new nusoap_client('http://ehusw.es/jav/ServiciosWeb/comprobarmatricula.php?wsdl' ,true);
	print_r($result = $soapclient->call('comprobar',array('x'=>$_GET['email'])));
?>