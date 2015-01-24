<?php
/**
*	@file   create.php
* 
*	@author Black Butterfly
* 
*	@date   22/01/2015 
*
*	@brief  Ici se trouve le formulaire d'ajout d'un meeting.
*
**/

// Pour utiliser les fonctions faisant appel � la base de donn�e
include ("../model/MMeeting.class.php");

session_start();


// Cr�ation d'un nouveau meeting 
	$AddOK = false;
	
	// Si on ressoit bien le formulaire
	if (isset($_POST['Add'])) 
	{
		// Si le temps choisi est null (0h0mn) ou < 5mn on n'accepte pas
		if( ($_POST['time'] == 0 && $_POST['mn'] == 0) || 
			($_POST['time'] == 0 && $_POST['mn'] < 5)){
			echo "Il est impossible de faire une r�union durant moins de 
				  cinq minute. Merci de rentrer un format correct.
				  <a href=\"../index.php?uc=create\"> 
				  Revenir � la page pr�c�dente </a>";
		}
		else if ( 	$_POST['subject'] 	!= null &&
					$_POST['location'] 	!= null){
				
			// Prot�ction XSS
			$subj 		= 	addslashes($_POST['subject']);
			$locat		= 	addslashes($_POST['location']);
			$describ	= 	addslashes($_POST['description']);
			
			// On ne sait jamais donc mieux vaux quand m�me le faire ..
			$hours		= 	addslashes($_POST['time']);
			$mn		 	= 	addslashes($_POST['mn']);
			$user		= 	addslashes($_SESSION['USER_ID']);

			$meeting = new MMeeting();
			
			// On regarde si le meeting existe d�j�
			$exist = $meeting->getMeetingId($subj, $user);
			
			// S'il n'existe pas, on le cr�er
			if($exist == false)
			{
					$meeting->addMeeting($subj,
										$describ,
										$locat,
										$hours,
										$mn,
										$user);		
										
					$AddOK = true;
			} // Fin cr�ation du meeting
			// Si l'utilisateur a d�j� cr�er le meeting --> Erreur message
			else {
				echo("Vous avez d�j� cr�ez un meeting comprenant ce sujet. 
					Merci de modifier le Sujet.<a href=\"../index.php?uc=create
					\"> Revenir � la page pr�c�dente </a>");
			}
				
		}// Fin verification vav du temps entr�
		// Si l'utilisateur n'as pas remplis tous les champs obligatoire
		else{
			echo "Veuillez remplir tous les champs obligatoire. 
			<a href=\"../index.php?uc=create\"> Revenir � la page pr�c�dente </a>"
			;
		} // Fin else verif champs obligatoire		
	} // Fin isset du form
	else{
		// ON NE DEVRAIT JAMAIS RENTRER ICI
		echo"(!) FATAL ERROR 1337 (!) ";
	} // Fin isset $_POST	
	
	// Une fois le meeting cr�er on rempli des nouvelles variable session pour
	// pouvoir continuer dans l'etape 2
	if ($AddOK)
	{
		$id = $meeting->getMeetingId($subj, $user);
		
		$_SESSION['MEET_ID'] 	= $id;
		$_SESSION['MEET_H'] 	= $hours;
		$_SESSION['MEET_MN']	= $mn;
		
		
		header("Location: ../index.php?uc=etape2");
	} // Fin if AddOK
	
	
?>