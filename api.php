<?php
ini_set('display_errors', 0);
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
	curl_close($curl);
	$response = json_decode($contents,true);
	
	$pin = $response['pin'];
	$photo =$response['photo_url'];
	$url = "https://zeroapis.42web.io/api/".$photo;

	$savePath = "images/". $pin . '.jpg';
	$ch = curl_init($url);
	$fp = fopen($savePath, 'wb');
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_TIMEOUT, 200);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // if using HTTPS
	curl_exec($ch);
	curl_close($ch);
	fclose($fp);
	echo "===== Basic Info =====\n";
echo "Status Code: " . $response['Status code'] . "\n";
echo "Request ID: @MR_COBRA_1\n";
echo "Owner: ⏤͟͞ 〲𝐂𝐎𝐁𝐑𝐀 ✘ 𝐕𝐀𝐈 🏴‍☠️\n";

echo "\n===== Personal Info =====\n";
echo "Name: ". $response['name en']."\n";
echo "Name Bangla: ". $response['name bn']."\n";
echo "Mobile :". $response['mobile']."\n";
echo "Father's Name: " . $response['father_name'] . "\n";
echo "Mother's Name: " . $response['mother_name'] . "\n";
echo "Mobile: " . $response['mobile'] . "\n";
echo "Birth Date: " . $response['birth_date'] . "\n";
echo "Age: " . $response['Age'] . "\n";
echo "National ID: " . $response['national_id'] . "\n";
echo "Image : https://api-kobravai.42web.io/images/".$pin.".jpg\n";
echo "Direct Address". $response['permanent_address']['raw_address']."\n";
echo "\n===== Present Address =====\n";
echo "Holding No: " . $response['presentHomeOrHoldingNo'] . "\n";
echo "Village/Road: " . $response['presentAdditionalVillageOrRoad'] . "\n";
echo "Post Office: " . $response['presentPostOffice'] . "\n";
echo "Postal Code: " . $response['presentPostalCode'] . "\n";
echo "City Corporation: " . $response['presentCityCorporationOrMunicipality'] . "\n";
echo "Upazila: " . $response['presentUpozila'] . "\n";
echo "District: " . $response['presentDistrict'] . "\n";
echo "Division: " . $response['presentDivision'] . "\n";
echo "Region: " . $response['presentRegion'] . "\n";
echo "Union/Ward: " . $response['presentUnionOrWard'] . "\n";

echo "\n===== Permanent Address =====\n";
echo "Village/Road: " . $response['permanentAdditionalVillageOrRoad'] . "\n";
echo "Post Office: " . $response['permanentPostOffice'] . "\n";
echo "Upazila: " . $response['permanentUpozila'] . "\n";
echo "District: " . $response['permanentDistrict'] . "\n";
echo "Division: " . $response['permanentDivision'] . "\n";
echo "Region: " . $response['permanentRegion'] . "\n";

echo "\n===== Debug Info =====\n";
echo "allow_url_fopen: " . $response['debug_info']['allow_url_fopen'] . "\n";
echo "curl_installed: " . ($response['debug_info']['curl_installed'] ? 'true' : 'false') . "\n";
echo "remote_url_accessible: " . ($response['debug_info']['remote_url_accessible'] ? 'true' : 'false') . "\n";

} 
else
{
	echo "{'error':'Code Error'}";
}