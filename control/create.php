<?php
/**
*	@file   create.php
* 
*	@author Black Butterfly
* 
*	@date   22/01/2015 
* 
*
*	@brief  Ici se trouve le formulaire d'ajout d'un meeting.
*
**/

include ("../model/MMeeting.class.php");

session_start();
// Création d'un nouvel utilisateur 
	$AddOK = false;
	//echo'<pre>';print_r($_POST);echo'</pre>';
	if (isset($_POST['Add'])) 
	{
		if( ($_POST['time'] == 0 && $_POST['mn'] == 0) || 
				($_POST['time'] == 0 && $_POST['mn'] < 5)){
				echo "Il est impossible de faire une réunion durant moins de 
					cinq minute. Merci de rentrer un format correct.
					<a href=\"../index.php?uc=create\"> 
					Revenir à la page précédente </a>";
		}
		else if ( 	$_POST['subject'] 	!= null &&
					$_POST['location'] 	!= null){
				
			// Protéction XSS
			$subj 		= 	addslashes($_POST['subject']);
			$locat		= 	addslashes($_POST['location']);
			$describ	= 	addslashes($_POST['description']);
			
			// On ne sait jamais donc mieux vaux quand même le faire ..
			$hours		= 	addslashes($_POST['time']);
			$mn		 	= 	addslashes($_POST['mn']);
			$user		= 	addslashes($_SESSION['USER_ID']);

			$meeting = new MMeeting();
			$exist = $meeting->getMeetingId($subj, $user);
			
			if($exist == false)
			{
					$meeting->addMeeting($subj,
										$describ,
										$locat,
										$hours,
										$mn,
										$user);		
										
					$AddOK = true;
			} // Fin test des mots de passes
				
			else {}
				
		}// Fin verification si l'utilisateur exist déjà

		else{
			echo "Le nom d'utilisateur ou l'adresse mail est invalide";
		}
			
	} // Fin if $_post null
		
	else
	{
		echo "Veuillez remplir tous les champs obligatoire. 
		<a href=\"../index.php?uc=register\"> Revenir à la page précédente </a>"
		;
	} // Fin else verif utilisateur
		
	if ($AddOK)
	{
		$id = $meeting->getMeetingId($subj, $user);
		$_SESSION['MEET_ID'] = $id;
		
		//header("Location: ../index.php?uc=home");
	} // Fin if AddOK


	
	/*else
	{
		echo"(!) FATAL ERROR 1337 (!) <br /> CODE : UUAP88 <br /> Veuillez 
		contactez l'administrateur du site en lui communiquant le code de 
		l'erreur : <a href=\"../index.php?uc=register\"> Nous contacter </a>";
	} // Fin isset $_POST*/
?>