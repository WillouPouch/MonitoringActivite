<?php

include_once("conf_anomalie_sonore_manager.php");


class ConfAnomalieSonoreDisplay{
	
	private $_cas_manager;

	//Constructeur
	public function __construct($db){
		$this->_cas_manager = new ConfAnomalieSonoreManager($db);
	}
	
	
	public function get_all_cas($div_table_classes){
		echo $this->html_table_cas($this->_cas_manager->get_all(), $div_table_classes);
	}
	
	
	private function html_table_cas($tab_cas, $div_table_classes){

		$html = '
			<div class="row '. $div_table_classes .'">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Heure de début</th>
							<th>Heure de fin</th>
							<th>Seuil (dB)</th>
							<th>Durée (sec)</th>
						</tr>
					</thead>
					<tbody>';

		foreach ($tab_cas as $cas){ 
			$html .= '
				<tr>
					<td>'.$cas->heure_debut().'</td>
					<td>'.$cas->heure_fin().'</td>
					<td>'.$cas->seuil().'</td>
					<td>'.$cas->duree().'</td>
				</tr>';
		}

		$html .= '	</tbody>
				</table>
			</div>';
		
		return $html;
	}
	
}
?>