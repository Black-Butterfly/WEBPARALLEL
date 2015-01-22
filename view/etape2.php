<?php
	
	function toHours($mn){
		$m = $mn%60;
		$h = intval($mn/60);
		
		return array($h, $m);
	}
	
	echo '	<form method="post" action="control/etape2.php" autocomplete="off">
			<h1>
				<div class="label label-primary"><label for="date">*Date : </label></div>
			</h1>
			<input type="date" name="date" />
			<br />
			<div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title">Choisissez un créneau horaire :</h3>
			</div>
			<div class="panel-body">
				<div class="row">
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
		
		echo '
			<div class="col-sm-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">'.$timebegin." - "
						.$timeend.'</h3>
					</div>
					<div class="panel-body-checkbox">
						<input type="checkbox" name="choice'.$timebegin.'
						" value="'.$timebegin.'" />
					</div>
				</div>
			</div>'
			;
	}
	echo '		</div>
			</div>
          </div>';	
	
	echo '	<br />
			<input type="radio" name="new" value="0">Ajouter une autre date
			<br />
			<input type="radio" name="new" value="1" checked>Finaliser
			<br />
			<input type="submit" name="AddDay" value="Valider"> 
			<br />
			</form>';
	
?>

