<?php 

/**
*	@file   view_meeting.php
* 
*	@author Black Butterfly
* 
*	@date   23/01/2015 
* 
*
*	@brief  Permet de r�cup n'importe quel meeting via l'url. 
*
**/
	
	// On vas avoir besoin des connexions � la base
	include ("model/MMeeting.class.php");
	
	// S�curisation des valeurs via url
	$subject 	= addslashes($_GET[0]);
	$name 		= addslashes($_GET[1]);
	$surname 	= addslashes($_GET[2]);
	
	
	$meeting = new MMeeting();
	
	// Test si le meeting existe
	$result = $meeting->getMeetingToShow($subject, $name, $surname);
	
	// Si le meeting n'existe pas. On est redirig�
	if($result == false){
		header("Location: ../index.php?uc=home");
	}
	
	
	// Affiche de l'ann�e s�lectionn�
	function yearView($date){
		echo '<div class="rowmeetings">
				<div style="text-align: center;" class="col2-md-8">'
					.date("Y", strtotime($date))
				.'</div>';
	}
	
	// Affiche le mois de l'ann�e 
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
	
	// G�n�re les heures de fa�ons � ce qu'elles puissent �tre s�l�ctionn�.
	// Les heures re�ues sont toujours en rapport avec la date
	function generateHours($hours, $dure, $idday){
		echo '<div class="col2-md-12">
				<table class="table">
					<tbody>';
		
		foreach ($hours as $time){
			
			// Reconstruction des temps de d�part, fin et de la valeur � envoyer
			$timebegin 	= $time[1].':'.$time[2];
			$timeend 	= ($time[1]+$dure[4]).':'.($time[2]+$dure[5]);
			// Pas s�cure mais je ne vois pas trop comment faire autrement :/
			$value 		= $dure[0].'&'.$idday.'&'.$time[0];
		
			// G�n�ration du code html pour les heures
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
	// $result[2] --> Description ( peut �tre null .. )
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
			
			
	
	// Recup�ration de toutes les dates associ� au meeting
	$date = $meeting->getMeetingDate($result[0]);
	
	// NORMALEMENT ON NE TOMBE JAMAIS DANS CE CAS MAIS BON ...
	if (sizeof($date) == 0){
		echo 'Aucune date est heures n\'est disponible pour ce meeting';
		exit();
	}
	
	//Pour �tre s�r que la personne n'as pas chang� d'ann�e :
	$year1 = date("Y", strtotime($date[0][1]));
	
	//Pour le mois
	$month1 = date("m", strtotime($date[0][1]));
	
	//On affiche l'ann�e
	yearView($date[0][1]);
	
	//On affiche le mois
	monthView($date[0][1]);
	
	
	/*
	*
	* Code HTML g�n�rer en boucle
	*
	*/
	
	foreach($date as $day){
		// R�cup�ration des heures via la date
		$hours = $meeting->getDateHours($day[0]);
		
		$year2  = date("Y", strtotime($day[1]));
		$month2 = date("m", strtotime($day[1]));

		// Pour �tre certain que l'ann�e s�l�ctionn� n'as pas �t� modifier entre
		// temps
		if ($year1 != $year2){
			$year1 = $year2;
			bigView($day[1]);
		} // Changement d'ann�e
		
		// Pareil que pr�cedemment mais pour les mois
		if ($month1 != $month2){
			$month1 = $month2;
			monthView($day[1]);
		} // Changement de moi
		
		// Affichage du jour 
		dayView($day[1]);
		
		// Affichage de toutes les heures relatif au jour
		generateHours($hours, $result, $day[0]);
	} 
	
	
	// Si la personne est conn�ct�, son nom sera envoy� d'office
	if(isset($_SESSION['PRENOM'])){
		  // On v�rifie si la personne qui regarde est le cr�ateur.
		  if($result[6] == $_SESSION['USER_ID']){
		   
		  }
		  else{
		   // r�cuperation de ses informations 
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