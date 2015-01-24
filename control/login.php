<?php

/**
*	@file   login.php
* 
*	@author Mariana Andujar
* 
*	@date   21/01/2015 
*
*	@brief  Ici se trouve le controleur de conn�ction
*
**/

// Pour pouvoir acc�der � la bdd
include ("../model/MMembers.class.php");

	// On regarde si un tous les champs sont remplit
	if($_POST['login'] == null || $_POST['paswd'] == null)
	{
		echo "L'un des champs n'est pas rempli.";
	}
	else
	{
		$member = new MMembers();
		
		// Protection XSS
		$login 	= addslashes($_POST['login']);
		$passwd = addslashes($_POST['paswd']);
		
		// On commence � rebuild le mdp
		$part1 = hash('md5', $login);
		$part2 = hash('gost', $passwd);
		
		// Recuperation des infos de l'utilisateur
		$info = $member->Get_Info($login);
		
		// Set up des variables pour le mdp
		$password2 = "";
		$salt = "";
		
		// Si on a bien des resultats, on compl�te les variables
		if($info != array())
		{
			$password2 = $info[0];
			$salt = $info[1];
		}
		
		// Reconstruction du mdp
		$password = $part1.$salt.$part2;
		
		// Si les mdp correspondent
		if($password == $password2)
		{
			// On fait session_start() ici pour ne pas avoir a re-g�n�rer de 
			// jeton de session
			session_start();
		
			// R�cup�ration des informations de l'utilisateur
			$USR = $member->getUser($login);

			$_SESSION["USER_ID"] 	= htmlentities($USR['0']);
			$_SESSION["NOM"]		= htmlentities($USR['1']);
			$_SESSION["PRENOM"]		= htmlentities($USR['2']);
			$_SESSION["TEL"]		= htmlentities($USR['3']);
			$_SESSION["EMAIL"]		= htmlentities($USR['4']);

			// Redir�ction sur home
			header("Location: ../index.php?uc=home");
			
		}	
		else
		{
			// Si l'utilisateur n'as pas remplis les informations correctement
			// on le renvoi sur la connection
			header("Location: ../index.php?uc=login");
		}
	}

?>
