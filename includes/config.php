<?php 
date_default_timezone_set('Asia/Dhaka');
// error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once("database.php");

define('DB_HOST',$host);
define('DB_USER',$user);
define('DB_PASS',$pass);
define('DB_NAME',$name);

try{
	$dbh = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	$dbh->query("SET NAMES utf8");
}catch (PDOException $e){
	exit("Error: " . $e->getMessage());
}


$mailHost = 'smtp.gmail.com'; 
$mailUsername = 'bdnidw@gmail.com';
$mailPassword = 'Trust@Allah1';
$mailPort = 465;

function dd($dd)
{
	echo "<pre>";
	var_dump($dd);
	echo "</pre>";
	die();
}

function nidInfo($n, $d){

	$api = "https://api.export-bangla.com/index.php?code=kobra&nid=$n&dob=$d";
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $api);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	$contents = curl_exec($curl);
	curl_close($curl);
	return json_decode($contents);
}

function convertToBanglaNumerals($number) {
    $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return str_replace($englishDigits, $banglaDigits, $number);
}   

// nidInfo('19758517695481989','1975-09-10');