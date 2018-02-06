<?php

include_once("type_activite.php");

class TypeActiviteManager{
	
	private $_db;
	private $_tab_type_activite;

	//Constructeur
	public function __construct($db){
		$this->_db = $db;
		$this->_tab_type_activite = array();
	}

	public function get_query_data($query){
		
		$sql = $this->_db->query($query);
		 
		while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
			array_push($this->_tab_type_activite, new TypeActivite($row));
		}
		return $this->_tab_type_activite;
	}
	
	public function get_all(){
		return $this->get_query_data("SELECT * FROM TYPE_ACTIVITE ORDER BY label");
	}
	
	public function get_label($type_activite){
		$sql = $this->_db->query("SELECT label FROM TYPE_ACTIVITE WHERE type_activite = '".$type_activite."' ORDER BY label");
		$row = $sql->fetch(PDO::FETCH_ASSOC);
		return $row['label'];
	}
	
}
?>