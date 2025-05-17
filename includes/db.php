<?php 
if(!isset($link)){
	if(!isset($host) && !isset($user) && !isset($pass) && !isset($name)){
		require_once("database.php");
	}
	$link = mysqli_connect($host, $user, $pass, $name);
	mysqli_set_charset($link,"utf8");

	if($link === false){
		die("ERROR: Could not connect. " . mysqli_connect_error());
	}
}
