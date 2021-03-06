﻿<?php
	
/**
*	@file   etape2.php
* 
*	@author Black Butterfly
* 
*	@date   22/01/2015 
* 
*
*	@brief  Permet de rajouter des dates et heures à un meeting 
*
**/
	
	// Fonction permettant de traduire en heure les minutes
	function toHours($mn){
		$m = $mn%60;
		$h = intval($mn/60);
		
		return array($h, $m);
	}
	
	// Génération du code html
	echo '	<form method="post" action="control/etape2.php" autocomplete="off">
			<h3>
				<div class="label label-primary"><label for="date">*Date : 
				</label></div><input type="date" name="date" />
				Format : (YYYY-MM-DD)
			</h3>
			<br />
			<div class="panel panel-info">
				<div class="panel-heading">
				  <h3 class="panel-title">Choisissez un créneau horaire :</h3>
				</div>
				<div class="panel-body">
					<div class="row">
	';
	
	// Protéction XSS
	$steph 	= addslashes($_SESSION['MEET_H']);
	$stepmn = addslashes($_SESSION['MEET_MN']);
	
	// Boucle principale pour proposer les créneaux toutes les 5 mn
	for ($i = 0; $i < 3600; $i+=5){
		
		// On récupère le temps de départ en 0 --> h , 1 --> mn
		$time = toHours($i);
		// On fait une copie
		$time2 = $time;
		
		// On rajoute le step choisi par l'utilisateur 
		$time2[0] += $steph;
		$time2[1] += $stepmn;
		
		// On fait en sorte de ne pas avoir 12h80...
		if($time2[1] > 60)
		{
			$time2[1] = ($time[1]+$stepmn)%60;
			++$time2[0];
		}
		
		// Une fois arrivé à 24h on sort de la boucle
		if ($time2[0] >= 24)
			break;
		
		// Build les valeurs à afficher
		$timebegin 	= $time[0].":".$time[1];
		$timeend 	= $time2[0].":".$time2[1];
		
		// Génère les choix possibles
		echo '
			<div class="col-sm-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">'.$timebegin." - "
						.$timeend.'</h3>
					</div>
					<div class="panel-body-checkbox">
						<input type="checkbox" name="choice[]" value="
						'.$timebegin.'" />
					</div>
				</div>
			</div>'
			;
	} // boucle principale
	
	// fermeture des balises 
	echo '		</div>
			</div>
          </div>';
	
	// Finalisation du document
	echo '	
				<div class="row">
					<div class="col-md-9">
						<table class="table">
						  <tbody>
							  <tr>
								<td>Ajouter une autre date <input type="radio" 
										name="new" value="0"></td>
							  </tr>
							  <tr>
								<td>Finaliser <input type="radio" name="new" 
										value="1" checked></td>
							  </tr>
							  <tr>
								<td><input type="submit" class="btn btn-sm 
								btn-success" name="AddDay" value="Valider"></td>
							  </tr>
						  </tbody>
						</table>
					</div>
				</div>
			</form>';
?>

