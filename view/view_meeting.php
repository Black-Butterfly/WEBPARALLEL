<?php 

/**
*	@file   view_meeting.php
* 
*	@author Black Butterfly
* 
*	@date   23/01/2015 
* 
*
*	@brief  Permet de récup n'importe quel meeting via l'url. 
*
**/
	
	// On vas avoir besoin des connexions à la base
	include ("model/MMeeting.class.php");
	
	// Sécurisation des valeurs via url
	$subject 	= addslashes($_GET[0]);
	$name 		= addslashes($_GET[1]);
	$surname 	= addslashes($_GET[2]);
	
	
	$meeting = new MMeeting();
	
	// Test si le meeting existe
	$result = $meeting->getMeetingToShow($subject, $name, $surname);
	
	// Si le meeting n'existe pas. On est redirigé
	if($result == false){
		header("Location: ../index.php?uc=home");
	}
	
	
	// Affiche de l'année sélectionné
	function yearView($date){
		echo '<div class="rowmeetings">
				<div style="text-align: center;" class="col2-md-8">'
					.date("Y", strtotime($date))
				.'</div>';
	}
	
	// Affiche le mois de l'année 
	function monthView($date){
		// Attribut F permet d'avoir le moi en textuel
		echo '	<div style="text-align: center;" class="col2-md-8">'.
					date("F", strtotime($date)).
				'</div>';
	}
	
	// Affiche la date (Monday 01)
	function dayView($date){
		// strftime permet d'obtenir le format Monday etc ...
		echo '	<div style="text-align: center;" class="col2-md-8">'.
					strftime("%A", strtotime($date)).' '
					.date("d", strtotime($date)).
				'</div>';
	}
	
	// Génère les heures de façons à ce qu'elles puissent être séléctionné.
	// Les heures reçues sont toujours en rapport avec la date
	function generateHours($hours, $dure, $idday){
		echo '<div class="col2-md-12">
				<table class="table">
					<tbody>';
		
		foreach ($hours as $time){
			
			// Reconstruction des temps de départ, fin et de la valeur à envoyer
			$timebegin 	= $time[1].':'.$time[2];
			$timeend 	= ($time[1]+$dure[4]).':'.($time[2]+$dure[5]);
			// Pas sécure mais je ne vois pas trop comment faire autrement :/
			$value 		= $dure[0].'&'.$idday.'&'.$time[0];
		
			// Génération du code html pour les heures
			echo '
				<div class="col-md-2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 style="text-align: center;" class="panel-title">'.$timebegin." - "
							.$timeend.'</h3>
						</div>
						<div class="panel-body-checkbox2">
							 <input type="checkbox" name="choice[]" 
								value="'.$value.'" />
						</div>
					</div>
				</div>';
		}
		echo '		</tbody>
				</table>
			</div>';
	}
	
	
	/* 
	*
	*
	*	GENERATION DU CODE HTML DE LA PAGE !!
	*
	*
	*/

	// Affiche le sujet, la description et le lieux : 
	// $result[1] --> Subject
	// $result[2] --> Description ( peut être null .. )
	// $result[3] --> Lieux 
	
	echo '<div class="row">
			<div class="col-sm-11">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Meeting : '.$result[1].'</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-9">
								<table class="table">
									<tbody>
									  <tr>
										<td>Description : '.$result[2].'</td>
									  </tr>
									  <tr>
										<td>Lieux : '.$result[3].'</td>
									  </tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
		<form action="control/participate.php" autocomplete="off" method="POST">';
			
			
	
	// Recupération de toutes les dates associé au meeting
	$date = $meeting->getMeetingDate($result[0]);
	
	// NORMALEMENT ON NE TOMBE JAMAIS DANS CE CAS MAIS BON ...
	if (sizeof($date) == 0){
		echo 'Aucune date est heures n\'est disponible pour ce meeting';
		exit();
	}
	
	//Pour être sûr que la personne n'as pas changé d'année :
	$year1 = date("Y", strtotime($date[0][1]));
	
	//Pour le mois
	$month1 = date("m", strtotime($date[0][1]));
	
	//On affiche l'année
	yearView($date[0][1]);
	
	//On affiche le mois
	monthView($date[0][1]);
	
	
	/*
	*
	* Code HTML générer en boucle
	*
	*/
	
	foreach($date as $day){
		// Récupération des heures via la date
		$hours = $meeting->getDateHours($day[0]);
		
		$year2  = date("Y", strtotime($day[1]));
		$month2 = date("m", strtotime($day[1]));

		// Pour être certain que l'année séléctionné n'as pas été modifier entre
		// temps
		if ($year1 != $year2){
			$year1 = $year2;
			bigView($day[1]);
		} // Changement d'année
		
		// Pareil que précedemment mais pour les mois
		if ($month1 != $month2){
			$month1 = $month2;
			monthView($day[1]);
		} // Changement de moi
		
		// Affichage du jour 
		dayView($day[1]);
		
		// Affichage de toutes les heures relatif au jour
		generateHours($hours, $result, $day[0]);
	} 
	
	
	// Si la personne est connécté, son nom sera envoyé d'office
	if(isset($_SESSION['PRENOM'])){
		  // On vérifie si la personne qui regarde est le créateur.
		  if($result[6] == $_SESSION['USER_ID']){
		   
		  }
		  else{
		   // récuperation de ses informations 
		   $surname  = addslashes($_SESSION['PRENOM']);
		   $name   = addslashes($_SESSION['NOM']);
		   
		   // Zone de saisie
		   echo '<input type="text" name="owner" value="'.$name.' '.$surname.'" 
			disabled />';
		  }
	}else{
		 echo '<br/>  Nom et prenom du participant : 
		 <input type="text" name="owner" placeholder="NOM PRENOM"/>';
	}
	
	// Fin du document
	echo '
		<br/><br/>
		<button name="participate" type="submit" class="btn btn-success">Participer
		</button>
		</form></div></div><br/>'
?>