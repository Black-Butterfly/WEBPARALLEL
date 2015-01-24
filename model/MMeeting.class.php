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

require_once(dirname(__FILE__) . "/../config.inc.php");

class MMeeting{

	//constructeur / destructeur
    public function __construct () {}

    public function __destruct () {}
	
	/*Ajout d'un meeting
		$description peut �tre null. $user = id_user (via $_SESSION['id_user'])
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
			$reqprep->bindParam(1, $subject, 	 PDO::PARAM_STR);
			$reqprep->bindParam(2, $description, PDO::PARAM_STR);
			$reqprep->bindParam(3, $locate, 	 PDO::PARAM_STR);
			$reqprep->bindParam(4, $duration, 	 PDO::PARAM_INT);
			$reqprep->bindParam(5, $mn, 		 PDO::PARAM_INT);
			$reqprep->bindParam(6, $user, 		 PDO::PARAM_INT);
			$reqprep->execute();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception");
		}	
    }
	
	/*
		Ajout d'une date d'un meeting
		
		$day = format 0000-00-00  
		$meeting = id_meeting obtenue via getMeetingId()
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
				$reqprep->bindParam(1, $date, 	PDO::PARAM_STR);
				$reqprep->bindParam(2, $idmeet, PDO::PARAM_STR);
				$reqprep->execute();
				
				// deconnexion
				$cnx = null;
		}catch (PDOException $e){
			die("exception");
		}
	}
	
	/*
		Ajout une heure de d�part � un meeting
		
		$day = format 0000-00-00  
		$meeting = id_meeting obtenue via getMeetingId()
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
	}
	
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
	}
	
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
	
	public function addMeetingByIdUser($uid){
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT SUBJECT, DESCRIPTION, LOCATION, DURATION, DURATION2
					FROM MEETING WHERE ID_USER = ?;";
			$reqprep = $cnx->prepare($req);
			$reqprep->bindParam(1, $uid,	PDO::PARAM_INT);
			$reqprep->execute();
			$result = $reqprep->fetchAll();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}
	
	public function addAllMeeting(){
		try{
			// connexion
			$cnx = new db();
			$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT SUBJECT, DESCRIPTION, LOCATION, DURATION, DURATION2
					FROM MEETING;";
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
}