<?php

    session_start();
    
    require_once("../utils/functions.php");
    require_once("../utils/bdd_connection.php"); // Contient le tableau clé-valeur 'state'
    
      
    
    $sql = $db->prepare("SELECT * FROM CONF_ANOMALIE_SONORE");
    
    run_sql($sql, $state);
    
   if($state["STATE"]=="OK"){
   //PDO::FETCH_ASSOC permet d'obtenir les lignes grâce à un identifiant clé-valeur et non par index (sans ce paramètre, fetchAll renvoie 2 fois la donnée : clé-valeur et index !)
   $state["DATA"]=$sql->fetchAll(PDO::FETCH_ASSOC);
   }
    
    echo json_encode($state);
    
?> 
