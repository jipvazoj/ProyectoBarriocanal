<?php
    require_once 'vendor/autoload.php';
    
    $clienteGoogle = new Google_Client();
    
    $clienteGoogle->setClientId("303112825353-ut6f5ij8o2mv1rm1tvhkpf7k8nu7kf3k.apps.googleusercontent.com");
    $clienteGoogle->setClientSecret("T-7aCFm9ykL4AIqSw1PD8dOE");
    $clienteGoogle->setRedirectUri("https://sw19lab0.000webhostapp.com/Proyecto/php/Layout.php");
    $clienteGoogle->setScopes(array("email","profile"));
    $clienteGoogle->setAccessType('offline');
    $clienteGoogle->setApprovalPrompt('force');
?>