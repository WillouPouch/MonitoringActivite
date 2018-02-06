<?php
    
    $host = "127.0.0.1";
    $dbname = "monitoring_activite";
    $user = "user_ma";
    $pass = "morpou";
    $db=null;
    $state=null;

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    }catch (PDOException $e) {
        $state["STATE"]="KO";
        $state["ERROR"]=$e->getMessage();
        echo json_encode($state); //Renvoi de l'erreur
        die();
    }

?> 
