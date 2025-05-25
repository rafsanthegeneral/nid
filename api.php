<?php
header('Content-Type: application/json');

if($_GET["code"]=== "kobrachobra")
{
    $n = $_GET["nid"];
    $d = $_GET["dob"];
	$api = "https://zeroapis.42web.io/api/tk.php?nid=$n&dob=$d";
	
	$curl = curl_init();
    // curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl, CURLOPT_URL, $api);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	$contents = curl_exec($curl);
	echo $contents;
	// echo (json_decode($contents));
	curl_close($curl);
}
else
{
	echo "Code Error";
}