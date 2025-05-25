<?php 
if (isset($_POST['submit'])) {

    if($_POST['code']=='cobrabobra')
    {


$nid = $_POST['nid'];
$dob = $_POST['dob'];


$url = "https://zeroapis.42web.io/api/tk.php?nid=$nid&dob=$dob";
$response = file_get_contents($url);
$result = json_decode($response, true);



    }

}