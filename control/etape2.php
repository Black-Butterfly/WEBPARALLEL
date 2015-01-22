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

	session_start();
	var_dump($_POST);
	var_dump($_SESSION);
	
	// Pour l'utilisation des fonctions faisant appel à la base de données
	include ("../model/MMeeting.class.php");
	
	
?>