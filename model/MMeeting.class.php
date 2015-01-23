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
		$description peut être null. $user = id_user (via $_SESSION['id_user'])
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
		Ajout une heure de départ à un meeting
		
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
			//$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
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
			//$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
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
	// TODO
	/*
	public function update_User($login, $name, $surname, $tel, $email, $passwd){
		try{
				// connexion
				$cnx = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_pwd);
				$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				// preparer la requete
				$req = "UPDATE USER SET NAME = '$name', SURNAME = '$surname', 
						TEL = '$tel', EMAIL = '$email', PASSWD = '$passwd' 
						WHERE LOGIN ='$login';";
				$reqprep = $cnx->prepare($req);
				$reqprep->execute(array($login));
				
				// deconnexion
				$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}
		
	}
	
	public function getUser($login)
	{
		try{
			// connexion
			$cnx = new db();
			//$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			// preparer la requete
			$req = "SELECT ID_USER, NAME, SURNAME, TEL, EMAIL FROM USER 
				WHERE LOGIN = ?;";
			$reqprep = $cnx->prepare($req);
			$reqprep->execute(array($login));
			$result = $reqprep->fetch();
			
			// deconnexion
			$cnx = null;
		}catch (PDOException $e){
			die("exception : ". $e->getMessage());
		}	
		return $result;
	}*/
}