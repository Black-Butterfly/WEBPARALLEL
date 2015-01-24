<?php
/**
*	@file   user.php
* 
*	@author Mariana ANDUJAR
* 
*	@date   21/01/2015 
* 
*
*	@brief  Ici se trouve le formulaire d'un utilisateur.
*
**/

// Obligatoire
session_start();

	// Pour pouvoir comuniquer avec la base de donnée
	include ("../model/MMembers.class.php");
	
	$validMdp = false;
	$valid = false;
	
	// Si l'utilisateur change juste ses infos perso
	if (isset($_POST['valid'])) {
		// Si l'utilisateur n'as pas modifier les champs, on remet les valeurs
		// comprise dans les variables de session
		
		if($_POST['name'] == null){
			$name = addslashes($_SESSION['NOM']);
		}else{
			$name = addslashes($_POST['name']);
		}
		
		if($_POST['mail'] == null){
			$mail = addslashes($_SESSION['EMAIL']);
			$mailoK = 1;
		}else{
			$mail = addslashes($_POST['mail']);
			$pattern = "/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$/";
			$mailoK = preg_match($pattern, $mail);
		}
		
		if($_POST['surname'] == null){
			$surname = addslashes($_SESSION['PRENOM']);
		}else{
			$surname = addslashes($_POST['surname']);
		}
		
		$id 	= addslashes($_SESSION['USER_ID']);
		$tel 	= addslashes($_POST['num']);
		
		
		$member = new MMembers();
		
		
		
		if($mailoK == 1){
			$member->update_User($id, $name, $surname, $tel, $mail);
			$valid = true;
		}else{
				echo "L'adresse mail est invalide";
			}
		
		if ($valid)
		{
			session_regenerate_id();
			
			// On récupère le login de l'utilisateur 
			$log = $member->getLoginById($id);
			
			// On récupère les info de celui-ci
			$USR = $member->getUser($log[0]);
	
			// On redéfini les variables de session
			$_SESSION["USER_ID"] 	= htmlentities($USR['0']);
			$_SESSION["NOM"]		= htmlentities($USR['1']);
			$_SESSION["PRENOM"]		= htmlentities($USR['2']);
			$_SESSION["TEL"]		= htmlentities($USR['3']);
			$_SESSION["EMAIL"]		= htmlentities($USR['4']);
	
			//Redirection sur home
			header("Location: ../index.php?uc=user");
		} 
	}
	
	// Si l'utilisateur change le mdp
	if (isset($_POST['validMdp'])) {
		
		// On regarde si les champs sont bien inséré
		if ( 			
			$_POST['passwordOld'] 		!= null &&
			$_POST['password']			!= null &&
			$_POST['passwordValid'] 	!= null ){
						
			// Protéction XSS			
			$passOld	=   addslashes($_POST['passwordOld']);
			$paswd	 	= 	addslashes($_POST['password']);
			$paswd2 	= 	addslashes($_POST['passwordValid']);
			$id 		= 	addslashes($_SESSION['USER_ID']);
			
			$member = new MMembers();
			
			// Récupère le login
			$log = $member->getLoginById($id);
			
			
			/*
			*
			* TODO
			*
			*/
			// On hash les mdp input
			$part1 	= hash('sha1', $paswd);
			$part2 	= hash('sha1', $paswd2);
			
			// On récupère le mdp et le salt
			$verifOldmdp = $member->Get_Info(addslashes($log[0]));
					
			// Partie de la mise en forme du mdp
			//rentré par l'utilisateur
			$hash1 = hash('sha1', $passOld);
			$debut = hash('md5', $log[0]);
			$fin = hash('gost', $passOld);
			$salt	= $log[1];
			$hashpass = $debut.$salt.$fin;
					
					
			if($hashpass == $verifOldmdp[0]){
				if($paswd == $paswd2)	
				{	
					$part1 	= hash('md5', $log[0]);
					$part2 	= hash('gost', $paswd);
					$salt	= $log[1];
							
							
					//Mise en forme du mot de passe
					$mdp	= $part1.$salt.$part2;
							
					$member->updateMdp($id, $mdp);
					
					session_regenerate_id();
					
					header("Location: ../index.php?uc=user");
					
					//$validMdp = true;
				}else{
					echo "Erreur dans le nouveau mot de passe n'est pas le même
						<a href=\"../index.php?uc=user\"> ";
				}
			}else{
				echo "Erreur dans le mot de passe
				<a href=\"../index.php?uc=user\"> ";
			}
					
		}
	}
?>