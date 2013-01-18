<?php
//		Main Security Gateway Control
//		This file will be used to make sure acsess has been granted to the site
$db = 0;

//DO NOT START SESSION ON ANY PAGE
//INCLUDE THIS FILE FOR ALL PHP FILES
include("cfg.php");
include("config.php");
include("User.php");
include("Torrent.php");
include("Torrents.php");
include("functions.php");

session_start();
$USER = false; //Global user

include("DB.php");
$db = DB::inst();

//Check if the user has a session
if(!isset($_SESSION["log_in"])){
	header("location:login.php"); //The session is not active so the user must log in
	return;
}else {
	//check the session is still active
	$uname = $db->checkSession(); //returns username if still active
	if($uname==-1){
		$db->deleteSession();
		session_destroy();
		header("location:login.php?inf=expire"); //The login session has expired NOTE: All users on this IP will be logged off 
	}else{
		$db->updateSession($uname);
		$USER = $db->loadUser($uname); //Load the user details
		$USER->setTorrents($db->getTorrentByUser($USER->getID()));
	}
}
?>