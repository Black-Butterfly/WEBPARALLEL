<?php
/**
*	@file   index.php
* 
*	@author Andujar Mariana
*
*	@update Black Butterfly 
* 
*	@date   21/01/2015
* 
*	@brief  Le Controleur 
*
**/

	// Obligatoire
	session_start();
	
	require_once(dirname(__FILE__) . '/config.inc.php');

	// affichage des vues de l'en tete et du sommaire 
	include(VIEW_DIR . "header.html");	
	include(VIEW_DIR . "menu.php");
	
	// instantiation de l'acces aux données
	require_once(MODEL_DIR . "MMembers.class.php");
	
	// utilisation du controleur adapté
	$page = $_REQUEST["uc"];
	
	// Redirection sur le controleur approprié
	switch($page)
	{
		// Cas pour voir les meetings créer
		case "meetings" :
	        include(CONTROLLER_DIR . "meetings.php");
	        break;
		// Cas pour la connéction	
	    case "login" :
	        include(VIEW_DIR. "login.html");
	        break;
		// Cas de déconexion
	    case "logout" :
	        include(CONTROLLER_DIR . "logout.php");
	        break;
		// Cas de création d'un meeting
	    case "create" : 
			// Si l'utilisateur a commencé un meeting
			if (isset($_SESSION['MEET_ID']))
				include(VIEW_DIR ."etape2.php");
			// L'utilisateur doit être identifier pour créer un meeting
			else if(isset($_SESSION['NOM']))
				include(VIEW_DIR ."create.html");
			else
				include(VIEW_DIR . "home.html");
			break;
		// Cas d'enregistrement d'un utilisateur
	    case "register" :
	        include(VIEW_DIR . "register.html");
	        break;
		// Session utilisateur	
		case "user" :
			if(isset($_SESSION['NOM']))
				include(VIEW_DIR . "user.php");
			else
				include(VIEW_DIR . "home.html");
			break;
		// Choix des heures et date
		case "etape2" :
			if(isset($_SESSION['NOM']))
				include(VIEW_DIR ."etape2.php");
			else
				include(VIEW_DIR . "home.html");
			break;
		// Affiche les meeting via url
		case "meeting" :
			include(VIEW_DIR ."view_meeting.php");
			break;
		default:
			include(VIEW_DIR . "home.html");
			break;
	
	}
	// footer de la page
	include(VIEW_DIR . "footer.html");
	
?>