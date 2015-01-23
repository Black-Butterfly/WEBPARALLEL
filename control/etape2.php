<?php
	
/**
*	@file   etape2.php
* 
*	@author Black Butterfly
* 
*	@date   21/01/2015 
* 
*
*	@brief  Ici se trouve le contrôleur de l'ajout des jours et heures dans la
*			base.
*
**/

	// Obligatoire si l'on veut pouvoir utiliser les variables de session
	session_start();
	
	// Pour l'utilisation des fonctions faisant appel à la base de données
	include ("../model/MMeeting.class.php");
	
	// Verification si le form et bien envoyé 
	if(isset($_POST['AddDay'])){
		// Si une date a été séléctioné ou au moins une heure
		if ($_POST['date'] != null && 
			sizeof($_POST['choice']) != 0){
		
			$date = date('Y-m-d');
			
			// si la date selectioné n'est pas la même ou inferrieur à la 
			// date actuelle
			if ($_POST['date'] > $date){
				$date 	= addslashes($_POST['date']);
				$idmeet = addslashes($_SESSION['MEET_ID'][0]);
				
				$meeting = new MMeeting();
				
				//if date not already exist for this meeting
				if ($iddate  = $meeting->getDateId($date, $idmeet) == null){
					$adddate = $meeting->addDayToMeeting($date, $idmeet);
					$iddate  = $meeting->getDateId($date, $idmeet); 
					
					// Add hours selected into the database
					foreach($_POST['choice'] as $choices){
						$suretime = addslashes($choices);
						$time = explode(':', $suretime);
						
						$addHours = $meeting->addHours($time[0], $time[1], 
							$idmeet,$iddate[0]);
					}
					
					if($_POST['new'] == 0){
						header("Location: ../index.php?uc=etape2");
					}//If user select to continue to add
					else{
						// Réinitialisa les variable relative au meeting a null
						$_SESSION['MEET_ID'] 	= null;
						$_SESSION['MEET_H'] 	= null;
						$_SESSION['MEET_MN'] 	= null;
						var_dump($_SESSION);
						// redirection sur home
						header("Location: ../index.php?uc=home");
					}
				}// date already exist
				else{
					echo "La date spécifié existe déjà pour ce meeting";
				}
			}
			else{
				echo "La date selectionné n'est pas valide.";
			}
		
		}// if date select with at least one hour
		else{
			echo "date hour incorrect";
		}
	}// if isset POST AddDay
	else{
		echo "FATAL ERROR";
	}
	
?>