<?php

session_start();

ini_set('display_errors', 1);

include_once("../utils/bdd_connection.php");

/*---*/
/*VAR*/
/*---*/


$query = "
SELECT
	id_as,
	DATE_FORMAT(date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
	DATE_FORMAT(date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
	niveau
FROM ANOMALIE_SONORE
WHERE id_as > ".$_POST["last_id_as"]."
ORDER BY id_as DESC";

$sql = $db->query($query);
echo json_encode( $sql->fetchAll(PDO::FETCH_ASSOC) );

?>