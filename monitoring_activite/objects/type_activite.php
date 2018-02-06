<?php

class TypeActivite{
	
	private $_type_activite;
	private $_label;

	//Constructeur
	public function __construct(array $type_activite){
		
		$this->_type_activite = $type_activite['type_activite'];
		$this->_label = $type_activite['label'];

	}

	//Getters
	public function type_activite() { return $this->_type_activite; }
	public function label() { return $this->_label; }
	
}
?>