<?php

session_start();

ini_set('display_errors', 1);

include_once("../utils/bdd_connection.php");

/*---*/
/*VAR*/
/*---*/

$query = "
SELECT
	act.id_activite,
	DATE_FORMAT(act.date_debut, '%d/%m/%Y %H:%i:%s' ) date_debut,
	DATE_FORMAT(act.date_fin, '%d/%m/%Y %H:%i:%s' ) date_fin,
	act.type_activite,
	ta.label
FROM
	ACTIVITE act
	,TYPE_ACTIVITE ta
WHERE act.type_activite = ta.type_activite
AND act.id_activite > ".$_POST["last_id_activite"]."
ORDER BY act.id_activite DESC
";

$sql = $db->query($query);
echo json_encode( $sql->fetchAll(PDO::FETCH_ASSOC) );

?>