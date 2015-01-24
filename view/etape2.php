﻿<?php
	
	function toHours($mn){
		$m = $mn%60;
		$h = intval($mn/60);
		
		return array($h, $m);
	}
	
	echo '	<form method="post" action="control/etape2.php" autocomplete="off">
			<h3>
				<div class="label label-primary"><label for="date">*Date : 
				</label></div><input type="date" name="date" />
			</h3>
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
						<input type="checkbox" name="choice[]" value="
						'.$timebegin.'" />
					</div>
				</div>
			</div>'
			;
	}
	echo '		</div>
			</div>
          </div>';
	
	echo '	
				<div class="row">
					<div class="col-md-9">
						<table class="table">
						  <tbody>
							  <tr>
								<td>Ajouter une autre date <input type="radio" 
										name="new" value="0"></td>
							  </tr>
							  <tr>
								<td>Finaliser <input type="radio" name="new" 
										value="1" checked></td>
							  </tr>
							  <tr>
								<td><input type="submit" class="btn btn-sm 
								btn-success" name="AddDay" value="Valider"></td>
							  </tr>
						  </tbody>
						</table>
					</div>
				</div>
			</form>';
?>

