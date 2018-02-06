<?php

include_once("anomalie_sonore_manager.php");


class AnomalieSonoreDisplay{

	private $_anomalie_sonore_manager;

	//Constructeur
	public function __construct($db){
		$this->_anomalie_sonore_manager = new AnomalieSonoreManager($db);
	}


	public function get_filter_anomalie_sonore($date_debut, $date_fin, $niveau_inf, $niveau_sup, $div_table_classes){
		echo $this->html_table_anomalie_sonore($this->_anomalie_sonore_manager->get_filter($date_debut, $date_fin, $niveau_inf, $niveau_sup), $div_table_classes);
	}


	public function get_last_anomalie_sonore($nb_anomalie_sonore, $div_table_classes){
		echo $this->html_table_anomalie_sonore($this->_anomalie_sonore_manager->get_last($nb_anomalie_sonore), $div_table_classes);
	}

	public function get_all_anomalie_sonore($div_table_classes){
		echo $this->html_table_anomalie_sonore($this->_anomalie_sonore_manager->get_all(), $div_table_classes);
	}


	private function html_table_anomalie_sonore($tab_anomalie_sonore, $div_table_classes){

		$html = '
			<div class="row '. $div_table_classes .'">
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Date de début</th>
							<th>Date de fin</th>
							<th>Niveau (dB)</th>
							<th>Configuration anomalie sonore</th>
						</tr>
					</thead>
					<tbody>';

		foreach ($tab_anomalie_sonore as $anomalie_sonore){
			$html .= '
				<tr>
					<td>'.$anomalie_sonore->date_debut().'</td>
					<td>'.$anomalie_sonore->date_fin().'</td>
					<td>'.$anomalie_sonore->niveau().'</td>';
					
			if( empty($anomalie_sonore->plage_horaire()) ) $html .= '<td> - </td>';
			else $html .= '<td><b>'.$anomalie_sonore->plage_horaire().'</b> : niveau supérieur à <b>'.$anomalie_sonore->seuil().' dB</b> pendant au moins <b>'.$anomalie_sonore->duree().' seconde(s)</b></td>';
			$html .= '</tr>';
		}

		$html .= '	</tbody>
				</table>
			</div>';

		return $html;
	}

}
?>
