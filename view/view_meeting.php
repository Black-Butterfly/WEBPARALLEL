<?php 
	
	include ("model/MMeeting.class.php");
	
	$subject 	= addslashes($_GET[0]);
	$name 		= addslashes($_GET[1]);
	$surname 	= addslashes($_GET[2]);
	
	$meeting = new MMeeting();
	$result = $meeting->getMeetingToShow($subject, $name, $surname);
	
	if($result == false){
		echo "Ya rien donc rediriger";
	}
	
	
	function yearView($date){
		echo date("Y", strtotime($date)).'<br />';
	}
	
	function monthView($date){
		echo date("F", strtotime($date)).'<br />';
	}
	
	function dayView($date){
		echo strftime("%A", strtotime($date)).' '.date("d", strtotime($date)).'
		<br />';
	}
	
	function generateHours($hours, $dure, $idday){
		foreach ($hours as $time){
			
			$timebegin 	= $time[1].':'.$time[2];
			$timeend 	= ($time[1]+$dure[4]).':'.($time[2]+$dure[5]);
			$value 		= $dure[0].'&'.$idday.'&'.$time[0];
		
			echo '
				<div class="col-sm-2">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">'.$timebegin." - "
							.$timeend.'</h3>
						</div>
						<div class="panel-body-checkbox">
							<input type="checkbox" name="choice[]" value="
							'.$value.'" />
						</div>
					</div>
				</div>'
				;
		}
	}
	
	
	// Affiche le sujet, la description et le lieux : 
	echo '<h3>Meeting : '.$result[1].'</h3>
			<br />
			Description : '.$result[2].'
			<br />
			Lieux : '.$result[3].'
			<br />
			<form action="TODO" autocomplete="off" methode="POST">';
			
	// TRICKY PART ! ! !
	
	// Recupération de toutes les dates associé au meeting
	$date = $meeting->getMeetingDate($result[0]);
	
	//Pour être sûr que la personne n'as pas changé d'année :
	$year1 = date("Y", strtotime($date[0][1]));
	
	//Pour le mois
	$month1 = date("m", strtotime($date[0][1]));
	
	//On affiche l'année
	yearView($date[0][1]);
	
	//On affiche le mois
	monthView($date[0][1]);
	
	
	// Génération des jours + heures + checkbox
	foreach($date as $day){
		$hours = $meeting->getDateHours($day[0]);
		
		$year2  = date("Y", strtotime($day[1]));
		$month2 = date("m", strtotime($day[1]));

		if ($year1 != $year2){
			$year1 = $year2;
			bigView($day[1]);
		} // Changement d'année
		
		if ($month1 != $month2){
			$month1 = $month2;
			monthView($day[1]);
		} // Changement de moi
		
		dayView($day[1]);
		
		generateHours($hours, $result, $day[0]);
	}
	
	if(isset($_SESSION['PRENOM'])){
		$surname = addslashes($_SESSION['PRENOM']);
		echo '<input type="text" name="owner" value="'.$surname.'" disabled />';
	}
	else{
		echo '<input type="text" name="owner" />';
	}
	
	echo '
		<button name="participate" type="submit" class="btn-success">Participer
		</button>
		 </form>'
?>