<?php	
/**
*	@file   logout.php
* 
*	@author Black Butterfly
* 
*	@date   21/01/2015 
*
*	@brief  Fichier de déconnexion
*
**/

	session_start();

	// On vide les variables de session
	$_SESSION = array();
	// On envoie un cookie de deconnexion
	setcookie(session_name(), '', time()-3600);
	// Redirection sur l'acceuil
	header("Location: index.php?uc=home");
?>