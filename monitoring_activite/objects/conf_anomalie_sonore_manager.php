<?php

include_once("conf_anomalie_sonore.php");

class ConfAnomalieSonoreManager{
	
	private $_db;
	private $_tab_cas;

	//Constructeur
	public function __construct($db){
		$this->_db = $db;
		$this->_tab_cas = array();
	}

	public function get_query_data($query){
		
		$sql = $this->_db->query($query);
		 
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			array_push($this->_tab_cas, new ConfAnomalieSonore($row));
		}
		return $this->_tab_cas;
	}
	
	public function get_all(){
		return $this->get_query_data("SELECT * FROM CONF_ANOMALIE_SONORE ORDER BY HEURE_DEBUT");
	}

}
?>