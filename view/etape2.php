<?php
	
	function toHours($mn){
		$m = $mn%60;
		$h = intval($mn/60);
		
		return array($h, $m);
	}
	
	echo '<form method="post" action="control/etape2.php" autocomplete="off">
		<br />
		<label for="date">*Date : </label>
		<input type="date" name="date" />
		<br />
		Choisissez un créneaux horraire : 
		<br />
	';
	
	$steph 	= addslashes($_SESSION['MEET_H']);
	$stepmn = addslashes($_SESSION['MEET_MN']);
	
	for ($i = 0; $i < 3600; $i+=5){
		
		$time = toHours($i);
		$time2 = $time;
		
		$time2[0] += $steph;
		$time2[1] += $stepmn;
		
		if($time2[1] > 60)
		{
			$time2[1] = ($time[1]+$stepmn)%60;
			++$time2[0];
		}
		
		if ($time2[0] >= 24)
			break;
		
		$timebegin = $time[0].":".$time[1];
		$timeend = $time2[0].":".$time2[1];
		
		echo '<input type="checkbox" name="choice'.$timebegin.'" value="'
			.$timebegin.'" />'.$timebegin." - ".$timeend;
	}
		
	
	echo '	<br />
			<input type="radio" name="new" value="0">Ajouter une autre date
			<br />
			<input type="radio" name="new" value="1" checked>Finaliser
			<br />
			<input type="submit" name="AddDay" value="Valider"> 
			<br />
			</form>';
	
?>

