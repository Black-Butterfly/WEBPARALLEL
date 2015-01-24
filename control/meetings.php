<?php

	// Besoin des fonctions ayant accès à la base
	include ("model/MMeeting.class.php");
	
	// Création de l'objet
	$meeting = new MMeeting();
	
	// On affiche tous les meeting avec les siens en priorité.
	$meetings = $meeting->giveAllMeetingWithUser();
	
	
	/*
	*
	* Function to build url
	*
	*/
	function buildUrl($subject, $name, $surname){
						
		// Pour être certain que les dossiers utilisé soit tjs 
		// bon dans l'url
		$geturl = $_SERVER['REQUEST_URI'];
						
		// Récupération du nom du dossié
		$explode = explode('/', $geturl);
						
		//On part sur localhost
		$url = 'http://localhost/';
									
		// On rajoute tous ce qu'il y a avant index.php?uc=meetings
		// Sinon erreur dans le build de l'address ! 
		foreach($explode as $path){
			if ($path == 'index.php?uc=meetings'){
				break;
			}
			$url = $url.$path;
		}
			
		// On rajoute le fait que l'on part de l'index
		$url = $url.'/'."index.php?uc=meeting";
		
		// On fait en sorte de n'avoir que les valeurs
		$tobuild = array($subject, $name,$surname);
						
		// FINALEMENT on build l'url
		$final = $url . '&' . 
		http_build_query($tobuild);
						
		return $final;
	}
	
	
	/*
	*
	* Fonction d'affichage quand l'utilisateur est connécté
	* TODO : Add bouton pour imprim en pdf ?? ou le faire sur view_meeting
	* TODO : Add bouton pour sup le meeting
	*
	*/
	function toShowWhenConnected($id, $all){
		echo '<div class="row">
			<div class="col-sm-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Vos reunions :</h3>
					</div>
					<div class="panel-body">';
		
		// Vas nous permettre de savoir si la personne à fait une réunion
		$content = false;
		
		// Toutes les réunion de l'utilisateur sont affiché en priorité
		foreach($all as $rdv){
			// Si c'est bien l'utilisateur
			if($rdv[3] == $id){
				// On reconstruit l'url du meeting
				$url = buildUrl($rdv[0], $rdv[1], $rdv[2]);
				// On affiche le meeting
				echo '<a href="'.$url.'">'.$rdv[0].' </a><br />';
				// On valide le fait qu'il y ait bien un meeting
				$content = true;
			}
		}
		
		// Si l'utilisateur n'as rien créer
		if (!$content)
			echo "Vous n'avez pas encore créer de reunions <br />";
		
		echo '</div></div></div></div>';
		echo '<div class="row">
			<div class="col-sm-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">
							Reunions des autres utilisateurs :
						</h3>
					</div>
					<div class="panel-body">';
		// Finalement on affiche les autres
		foreach($all as $rdv){
			//Si la reunion n'as pas été créer par l'utilisateur
			if($rdv[3] != $id){
				// On reconstruit l'url du meeting
				$url = buildUrl($rdv[0], $rdv[1], $rdv[2]);
				// On affiche le meeting
				echo '<a href="'.$url.'">'.$rdv[0].' </a><br />';
			}
		}
		echo '</div></div></div></div>';
	} // Function toShowWhenConnected($id, $all)
	
	
	/*
	*
	* 	Function showMeEverything
	*	Affiche tous les meeting
	*
	*/
	function showMeEverything($all){
		echo '<div class="row">
			<div class="col-sm-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h2 class="panel-title">Liste des meetings</h2>
					</div>
					<div class="panel-body">';
		foreach($all as $rdv){
			// On reconstruit l'url du meeting
			$url = buildUrl($rdv[0], $rdv[1], $rdv[2]);
			// On affiche le meeting
			echo '<a href="'.$url.'">'.$rdv[0].' </a><br />';
		}
		echo '		</div>
				</div>
			</div>
		</div>';
	}
	
	
	// Si la personne est connécté
	if(isset($_SESSION['USER_ID'])){
		// On n'as pas confiance en ses données
		$id = addslashes($_SESSION['USER_ID']);
		toShowWhenConnected($id, $meetings);
	}else{
		showMeEverything($meetings);
	}
	echo "<br />";
?>