<form autocomplete="off" method="post">
	<h2>Listes des Réunions</h2>
	 <table >
        <tr>
           <th >Sujet:</th>  
           <th > Description:</th> 
           <th >Location:</th>  
           <th >Duration</th>
           <th>Voir plus d'informations</th>             
        </tr>
       <?php
       		foreach ($meetings as $meeting) {
		?>
		   <th ><?php echo $meeting[1]; ?></th>  
           <th ><?php echo $meeting[2]; ?></th> 
           <th ><?php echo $meeting[3]; ?></th>  
           <th ><?php echo $meeting[4]; ?></th>
           <th><?php echo'url à venir';?></th> 
	   <?php
			   }	
       ?>
	</table>
</form>

<form autocomplete="off" method="post">
	<h2>Listes de vos Réunions</h2>
	 <table >
        <tr>
           <th >Sujet:</th>  
           <th > Description:</th> 
           <th >Location:</th>  
           <th >Duration</th>
           <th>Voir plus d'informations</th>
           <th >PDF</th>
           <th >EXCEL</th>              
        </tr>
       <?php
       		foreach ($meetingsUser as $meeting) {
		?>
		   <th ><?php echo $meeting[1]; ?></th>  
           <th ><?php echo $meeting[2]; ?></th> 
           <th ><?php echo $meeting[3]; ?></th>  
           <th ><?php echo $meeting[4]; ?></th>
           <th><?php echo'url à venir';?></th>
           <th><?php echo'PDF à venir';?></th>
           <th><?php echo'Excel à venir';?></th>
	   <?php
			   }	
       ?>
	</table>
</form>