<?php
	
/**
*	@file   etape2.php
* 
*	@author Black Butterfly
* 
*	@date   22/01/2015 
* 
*	@brief  Ici se trouve le contr�leur de l'ajout des jours et heures dans la
*			base.
*
**/

	// Obligatoire si l'on veut pouvoir utiliser les variables de session
	session_start();
	
	// Pour l'utilisation des fonctions faisant appel � la base de donn�es
	include ("../model/MMeeting.class.php");
	
	// Verification si le form et bien envoy� 
	if(isset($_POST['AddDay'])){
		// Si une date a �t� s�l�ction� ou au moins une heure
		if ($_POST['date'] != null && 
			sizeof($_POST['choice']) != 0){
		
			$date = date('Y-m-d');
			
			// si la date selection� n'est pas la m�me ou inferrieur � la 
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
					
					// If user continue to add
					if($_POST['new'] == 0){
						header("Location: ../index.php?uc=etape2");
					}//If user select to continue to add
					else{
						// R�initialise les variables relative au meeting a null
						$_SESSION['MEET_ID'] 	= null;
						$_SESSION['MEET_H'] 	= null;
						$_SESSION['MEET_MN'] 	= null;
						
						
						/*
						*
						* Cr�ation de l'url
						*
						*/
						
						
						$iduser = addslashes($_SESSION['USER_ID']);
						
						// Pour �tre certain que les dossiers utilis� soit tjs 
						// bon dans l'url
						$geturl = $_SERVER['REQUEST_URI'];
						
						// R�cup�ration du nom du dossi�
						$explode = explode('/', $geturl);
						
						// On part sur localhost
						$url = "http://localhost/";
						
						// On rajoute tous ce qu'il y a avant control
						foreach($explode as $path){
							if ($path == 'control'){
								break;
							}
							$url = $url.$path;
						}
						
						// On rajoute le fait que l'on part de l'index
						$url = $url."/index.php?";
							
						// ENFIN !! 
						
						// R�cup�ration des informations dont nous avons besoin
						// 0 --> Subject, 1 --> Name, 2-->Surname
						$info = $meeting->createURL($idmeet, $iduser);
						
						// On fait en sorte de n'avoir que les valeurs
						$tobuild = array($info[0], $info[1],$info[2]);
						
						// FINALEMENT on build l'url
						$final = $url . 'uc=meeting' .'&' . 
							http_build_query($tobuild);
						
						// redirection sur le meeting
						header("Location: " . $final);
					}
				}// date already exist
				else{
					echo "La date sp�cifi� existe d�j� pour ce meeting
					<a href=\"../index.php?uc=etape2\"> Revenir � la page 
					pr�c�dente </a>";
				}
			}
			else{
				echo "La date selectionn� n'est pas valide. 
				<a href=\"../index.php?uc=etape2\"> Revenir � la page pr�c�dente 
				</a>";
			}
		
		}// if date select with at least one hour
		else{
			echo "date hour incorrect <a href=\"../index.php?uc=etape2\"> 
			Revenir � la page pr�c�dente </a>";
		}
	}// if isset POST AddDay
	else{
		echo "FATAL ERROR";
	}
	
?>