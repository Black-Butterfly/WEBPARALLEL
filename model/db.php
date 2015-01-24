<?php

/**
*	@file   db.php
* 
*	@author Black Butterfly 
* 
*	@date   21/01/2015
* 
*	@brief  Pour récupérer les variables de connection
*
**/

	define("HOST", getenv('dbHost'));
	define("DBNAME", getenv('dbBd'));
	define("DBUSER", getenv('dbLogin'));
	define("DBPWD", getenv('dbPass'));
	
?>