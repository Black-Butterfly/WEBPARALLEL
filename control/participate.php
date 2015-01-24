<?php 

/**
*	@file   participate.php
* 
*	@author Black Butterfly
* 
*	@date   24/01/2015 
* 
*	@brief  Permet de rajouter une personne a un meeting. 
*
**/

// Pour accéder à la base de donnée
include ("../model/MMeeting.class.php");

// Ne surtout pas oublier
session_start();

// On regarde si on ressoit bien les informations du formulaire
if (isset($_POST['participate'])){
	
	//Verification sur le fait que la personne ait mis un nom et choisi un 
	//créneau
	if(	isset($_POST['owner']) 	&&
		isset($_POST['choice']) &&
		$_POST['owner'] != null &&
		sizeof($_POST['choice']) != 0){
		
		// Protéction contre xss
		$own = addslashes($_POST['owner']);
		
		//Ajout du fait que l'utilisateur veuil participer au meeting
		foreach($_POST['choice'] as $choice){
			$choice = addslashes($choice);
			
			//On récupère les informations d'ont nous avons besoin
			$info = explode('&', $choice);
			
			//Pour plus de sécurité on regarde si les données n'ont pas été 
			//modifié
			if(	is_numeric($info[0]) && 
				is_numeric($info[1]) && 
				is_numeric($info[2])){
				
				$meeting = new MMeeting();
					
				// Ajout du fait que la personne est disponible 
				$add = $meeting->addFolower($info[0], 
											$info[1], 
											$info[2], 
											$own);
					
				// On le renvoi sur home
				header("Location: ../index.php?uc=home");
			}	
		}
	}else{
		// Si l'utilisateur n'as pas rempli un champs
		echo('Une erreur est survenue durant l\'ajout de votre participation. 
			Etes-vous certain de bien avoir renseigner le champ prénom ainsi
			que d\'avoir choisi un créneau horraire ? 
			<a href=\"../index.php?uc=register\"> Revenir au sommaire des 
			meetings </a>"');
	}// Champs invalide 
}else{
	// ON EST PAS SENSE ENTRER ICI
	echo ("FATAL ERROR dafuq");
}

?>