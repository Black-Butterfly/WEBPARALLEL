<?php
/**
*	@file   MMeeting.class.php
* 
*	@author Black Butterfly 
* 
*	@date   21/01/2015
* 
*	@brief  Ici se trouve la class gerant les actions sur les meetings
*
**/

// Pour pouvoir se connécter
require_once(dirname(__FILE__) . "/../config.inc.php");

class MMeeting{

	//constructeur / destructeur
    public function __construct () {}

    public function __destruct () {}
	
	/*
	*
	*	@function 		addMeeting
	*
	*	@description	Cette fonction permet de rajouter un meeting dans la 
	*					base
	*
	*	@Param 			$subject 		( Sujet du meeting )
	*	@Param 			$description	( description du meeting )
	*	@Param 			$locate 		( lieux du meeting )
	*	@Param 			$duration		( Temps du meeting (h) )
	*	@Param 			$mn				( Temps du meeting (mn) )
	*	@Param 			$user			( Id de l'utilisateur )
	*
	*	@return			Rien
	*
	*/
    public function addMeeting ($subject, $description, $locate, $duration, $mn, 
		$user)
    {
		
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "INSERT INTO MEETING (SUBJECT, DESCRIPTION, LOCATION, 
				DURATION, DURATION2, ID_USER) VALUES (?, ?, ?, ?, ?, ?)";
			$reqprep = $cnx->prepare($req);
			// On fais en sorte d'essayé de ne pas se faire avoir par une sqli
			$reqprep->bindParam(1, $subject, 	 PDO::PARAM_STR);
			$reqprep->bindParam(2, $description, PDO::PARAM_STR);
			$reqprep->bindParam(3, $locate, 	 PDO::PARAM_STR);
			$reqprep->bindParam(4, $duration, 	 PDO::PARAM_INT);
			$reqprep->bindParam(5, $mn, 		 PDO::PARAM_INT);
			$reqprep->bindParam(6, $user, 		 PDO::PARAM_INT);
			
			// execution de la requete
			$reqprep->execute();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception");
		}	
    } // addMeeting
	
	
	/*
	*
	*	@function 		addDayToMeeting
	*
	*	@description	Cette fonction permet de rajouter à un meeting une date 
	*
	*	@Param 			$date 			( date au format YYYY-MM-DD )
	*	@Param 			$idmeet			( Id du meeting )
	*
	*	@return			Rien
	*
	*/
	public function addDayToMeeting($date, $idmeet)
	{
		try{
				// connexion
				$cnx = new db();
				$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// preparer la requete
				$req = "INSERT INTO DATE (DDAY, ID_MEETING) VALUES (?, ?)";
				$reqprep = $cnx->prepare($req);
				
				// Test de protéction contre sqli
				$reqprep->bindParam(1, $date, 	PDO::PARAM_STR);
				$reqprep->bindParam(2, $idmeet, PDO::PARAM_STR);
				$reqprep->execute();
				
				// deconnexion
				$cnx = null;
		}catch (PDOException $e){
			die("exception");
		}
	}// addDayToMeeting
	
	/*
	*
	*	@function 		addHours
	*
	*	@description	Cette fonction permet de rajouter à une date du meeting  
	*					les heures du meeting
	*
	*	@Param 			$hours	 		( Heure de départ )
	*	@Param 			$minutes		( Mn de départ )
	*	@Param 			$idmeet 		( Id du meeting )
	*	@Param 			$iddate			( id du jour du meeting )
	*
	*	@return			Rien
	*
	*/
	public function addHours($hours, $minutes, $idmeet, $iddate)
	{
		try{
				// connexion
				$cnx = new db();
				$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// preparer la requete
				$req = "INSERT INTO HOURS (BHOUR,BMIN, ID_MEETING, ID_DATE) 
					VALUES (?, ?, ?, ?)";
				$reqprep = $cnx->prepare($req);
				
				// Test de protéction contre sqli
				$reqprep->bindParam(1, $hours,  PDO::PARAM_INT);
				$reqprep->bindParam(2, $minutes,PDO::PARAM_INT);
				$reqprep->bindParam(3, $idmeet, PDO::PARAM_INT);
				$reqprep->bindParam(4, $iddate, PDO::PARAM_INT);
				$reqprep->execute();
				
				// deconnexion
				$cnx = null;
		}catch (PDOException $e){
			die("exception");
		}
	}// addHours
	
	/*
	*
	*	@function 		getMeetingId
	*
	*	@description	Cette fonction permet de récupérer l'id d'un meeting 
	*					via le sujet ainsi que l'utilisateur
	*
	*	@Param 			$subject 		( Sujet du meeting )
	*	@Param 			$user			( id de l'utilisateur)
	*
	*	@return			Id du meeting
	*
	*/
	public function getMeetingId($subject, $user)
	{
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT ID_MEETING FROM MEETING WHERE SUBJECT = ? AND ID_USER
				= ?;";
			$reqprep = $cnx->prepare($req);
			
			// Protection sqli
			$reqprep->bindParam(1, $subject, 	PDO::PARAM_STR);
			$reqprep->bindParam(2, $user, 		PDO::PARAM_INT);
			$reqprep->execute();
			$result = $reqprep->fetch();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}
	
	/*
	*
	*	@function 		getDateId
	*
	*	@description	Permet de récupérer l'id d'un jour particulier du 
	* 					meeting
	*
	*	@Param 			$date	 		( Jour que l'on veut )
	*	@Param 			$idmeet			( id du meeting )
	*
	*	@return			Id de la date
	*
	*/
	public function getDateId($date, $idmeet)
	{
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT ID_DATE FROM DATE WHERE DDAY = ? AND ID_MEETING
				= ?;";
			$reqprep = $cnx->prepare($req);
			$reqprep->bindParam(1, $date,	 	PDO::PARAM_STR);
			$reqprep->bindParam(2, $idmeet,		PDO::PARAM_INT);
			$reqprep->execute();
			$result = $reqprep->fetch();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}// getDateId
	
	/*
		TODO
	*/
	public function createURL($idmeet, $iduser)
	{
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT SUBJECT, NAME, SURNAME FROM MEETING, USER WHERE 
				ID_MEETING = ? AND USER.ID_USER = ?;";
			$reqprep = $cnx->prepare($req);
			$reqprep->bindParam(1, $idmeet,	 	PDO::PARAM_INT);
			$reqprep->bindParam(2, $iduser,		PDO::PARAM_INT);
			$reqprep->execute();
			$result = $reqprep->fetch();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}
	
	/*
		PROBLEME AVEC LA FONCTION : RENVOI UNE INFINITER DE ROW !! 
	*/
	/*public function getMeetingToShow($subject, $name, $surname){
		try{
				// connexion
				$cnx = new db();
				$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// preparer la requete
				$req = "SELECT * FROM MEETING, DATE, HOURS WHERE 
				MEETING.ID_MEETING = ( SELECT ID_MEETING FROM MEETING WHERE 
				SUBJECT = ? AND MEETING.ID_USER = (SELECT ID_USER FROM USER 
				WHERE NAME = ? AND SURNAME = ?));";
				$reqprep = $cnx->prepare($req);
				$reqprep->bindParam(1, $subject,	 PDO::PARAM_STR);
				$reqprep->bindParam(2, $name,		PDO::PARAM_STR);
				$reqprep->bindParam(3, $surname,	PDO::PARAM_STR);
				$reqprep->execute();
				$result = $reqprep->fetch();
				
				
				// deconnexion
				$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}
		
		return $result;
		
	}*/
	
	public function getMeetingToShow($subject, $name, $surname){
		try{
				// connexion
				$cnx = new db();
				$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// preparer la requete
				$req = "SELECT * FROM MEETING WHERE SUBJECT = ? AND 
				MEETING.ID_USER = (SELECT ID_USER FROM USER 
				WHERE NAME = ? AND SURNAME = ?);";
				$reqprep = $cnx->prepare($req);
				$reqprep->bindParam(1, $subject,	PDO::PARAM_STR);
				$reqprep->bindParam(2, $name,		PDO::PARAM_STR);
				$reqprep->bindParam(3, $surname,	PDO::PARAM_STR);
				$reqprep->execute();
				$result = $reqprep->fetch();
				
				
				// deconnexion
				$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}
		
		return $result;
	}
	

	public function getMeetingDate($idmeet)
	{
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT * FROM DATE WHERE ID_MEETING = ? ORDER BY DDAY ASC;";
			$reqprep = $cnx->prepare($req);
			$reqprep->bindParam(1, $idmeet,	PDO::PARAM_INT);
			$reqprep->execute();
			$result = $reqprep->fetchAll();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}
	
	public function getDateHours($iddate)
	{
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT * FROM HOURS WHERE ID_DATE = ?;";
			$reqprep = $cnx->prepare($req);
			$reqprep->bindParam(1, $iddate,	PDO::PARAM_INT);
			$reqprep->execute();
			$result = $reqprep->fetchAll();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}
	
	public function addFolower ($idmeeting, $iddate, $idhour, $owner)
    {	
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "INSERT INTO AVAILABLE (ID_MEETING, ID_DATE, ID_HOURS, OWNER
				) VALUES (?, ?, ?, ?)";
			$reqprep = $cnx->prepare($req);
			$reqprep->bindParam(1, $idmeeting, 	 PDO::PARAM_INT);
			$reqprep->bindParam(2, $iddate,		 PDO::PARAM_INT);
			$reqprep->bindParam(3, $idhour, 	 PDO::PARAM_INT);
			$reqprep->bindParam(4, $owner,	 	 PDO::PARAM_STR);
			$reqprep->execute();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception");
		}	
    }
	
	public function giveAllMeetingWithUser()
	{
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT SUBJECT, NAME, SURNAME, MEETING.ID_USER FROM MEETING,
			USER WHERE MEETING.ID_USER = USER.ID_USER 
			ORDER BY MEETING.ID_USER;";
			$reqprep = $cnx->prepare($req);
			$reqprep->execute();
			$result = $reqprep->fetchAll();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}
	
	public function getAllFolowers($id)
	{
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT * FROM AVAILABLE WHERE ID_MEETING = ?;";
			$reqprep = $cnx->prepare($req);
			$reqprep->bindParam(1, $id, PDO::PARAM_INT);
			$reqprep->execute();
			$result = $reqprep->fetchAll();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}
}