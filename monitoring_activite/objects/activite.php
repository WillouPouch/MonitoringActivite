<?php

class Activite{
	
	private $_id_activite;
	private $_date_debut;
	private $_date_fin;
	private $_type_activite;

	//Constructeur
	public function __construct(array $activite){
		
		$this->_id_activite = (int) $activite['id_activite'];
		$this->_date_debut = $activite['date_debut'];
		$this->_date_fin = $activite['date_fin'];
		$this->_type_activite = $activite['type_activite'];
	}

	//Getters
	public function id_activite() { return $this->_id_activite; }
	public function date_debut() { return $this->_date_debut; }
	public function date_fin() { return $this->_date_fin; }
	public function type_activite() { return $this->_type_activite; }
	
}
?>