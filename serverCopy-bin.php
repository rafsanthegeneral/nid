<?php
session_start(); error_reporting(0);
ini_set('display_errors', 0);
include('includes/db.php');
include('includes/config.php');

if(!isset($_SESSION['alogin'])) {
    header('location:login.php');
    die();
}

function image($api){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $api);
	curl_setopt($curl, CURLOPT_TIMEOUT, 60);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	$contents = curl_exec($curl);
	curl_close($curl);
	
	$img = 'image/'.md5($contents).'.png';
	file_put_contents($img, $contents);
	return $img;
}

$id = $_SESSION['alogin'];
$select = "SELECT * FROM control";
$g = mysqli_query($link,$select);
$data = mysqli_fetch_assoc($g);
$cc_server_unofficial_price = $data['cc_server_unofficial_price'];
$sql = "SELECT * FROM users WHERE username='$id'";
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_array($result);
$username = $row['username'];
$balance = $row['balance'];

if(isset($_POST['nid'], $_POST['dob'])){
	if($balance >= $cc_server_unofficial_price) {
	    
		$json = nidInfo($_POST['nid'], $_POST['dob']);
        $data = $json;

		if(
			isset($data->name_bn) AND
			!empty($data->name_bn) AND
			isset($data->name_en) AND
			!empty($data->name_en)
		) {
			
			$userImg     = $data->photo_url;
			$name  = $data->name_bn;
			$nameEn = $data->name_en;
			$nationalId = $data->national_id;
			$pin         = $data->pin;
			// $occupationBn   = $data->occupationBn;
			// $father  = $data->father;
			// $mother  = $data->mother;
			// $spouse  = $data->spouse;
			$postCode  = $data->permanent_address->post_office;
			$upozila  = $data->permanent_address->permanent_address;
			$birthPlace  = $data->current_address->current_address;

            $dob = $_POST['dob'];

			$dateOfBirth = date("d M Y", strtotime($dob));

			$bloodGroup = $data->bloodGroup;

			$address = "বাসা/হোল্ডিং: ".$data->permanent_address->village_road.",  ডাকঘর: ".$data->permanent_address->post_office.", ".$data->permanent_address->ward.", ".$data->permanent_address->permanent_address;

            $permanentAddress = $data->permanent_address->permanent_address;
            $presentAddress = $data->permanent_address->permanent_address;

			$gender = $data->gender;
			$maritalStatus = "";
			$religion = $data->religion;

            $nidFather = $data->nidFather;
            $nidMother   = $data->nidMother;
            $voterAreaCode = $data->voterAreaCode;

            $qrcode = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=". urlencode($data->name_en.' '.$data->national_id.' '.$_POST['dob']);

			$sql2 = "UPDATE users SET balance = balance - $cc_server_unofficial_price WHERE username = '$username'";
            mysqli_query($link, $sql2);

        }else{
			echo '<script>alert("Nid info not found")</script>'; die();
            
		}
	}else{
		echo '<script>alert("আপনার পর্যাপ্ত পরিমাণ ব্যালেন্স নাই প্লিজ রিচার্জ করুন")</script>'; die();
	}
}

if(isset($_POST['server']) && $_POST['server'] == 'new'){
?>  
    
    
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>SVC:-<?php echo $nationalId;?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .container {
            position: relative;
            width: 210mm;
            margin: 0px auto;
        }

        .bgImg {
            width: 210mm;
            height: 297mm;
        }

        .avatar {
            width: 130px;
            height: 151px;
            position: absolute;
            top: 187px;
            left: 333px;
            background: red;
            border-radius: 16px;
        }

        p {
            font-size: 15px;
        }

        p.inLeft {
            position: absolute;
            left: 110px;
            opacity: 0.9;
        }

        p.relagionKey.inLeft {
            top: 790px;
        }

        p.mobileKey.inLeft {
            top: 817px;
        }

        p.inRight {
            max-height: 0.393in;
            max-width: 6.33in;
        }

        .inRight {
            position: absolute;
            left: 264px;
        }

        p.nid.inRight {
            top: 400px;
        }

        p.pin.inRight {
            top: 428px;
        }

        p.formNo.inRight {
            top: 457px;
        }
        p.VoterNo.inRight {
            top: 482px;
        }

        p.vArea.inRight {
            top: 510px;
        }

        p.nameBn.inRight {
            top: 567px;
            font-weight: bold;
        }

        p.nameEn.inRight {
            top: 595px;
        }

        p.dob.inRight {
            top: 623px;
        }

        p.fName.inRight {
            top: 649px;
        }

        p.mName.inRight {
            top: 677px;
        }

        p.husWif.inRight {
            top: 703px;
        }

        p.gender.inRight {
            top: 762px;
        }

        p.phone.inRight {
            top: 819px;
        }

        p.relagion.inRight {
            top: 791px;
        }

        p.birthPlace.inRight {
            top: 845px;
        }

        p.address {
            max-width: 575px;
            position: absolute;

            left: 110px;
            font-size: 12px;
            line-height: 18px;
        }

        .presentAddr {
            top: 902px;
        }

        .permanentAddr {
            top: 975px;
        }

        button.PrintBtn {
            width: 150px;
            background: #8a00ff;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            display: block;
            margin: auto;
            margin-bottom: 100px;
            border-radius: 6px;
            color: #fff;
            font-size: 16px;
        }

        @page {
            size: A4;
            margin: 20;
        }

        @media print {
        
            button.PrintBtn {
                display: none;
            }
        }
    </style>
</head>
<?php
//$voter_area = '';

// if (!empty($json->permanentAddress->mouzaMoholla)) {
//     $voter_area = $json->permanentAddress->mouzaMoholla;
// } elseif (!empty($json->permanentAddress->postOffic)) {
//     $voter_area = $json->permanentAddress->postOffic;
// } elseif (!empty($json->permanentAddress->upozila)) {
//     $voter_area = $json->permanentAddress->upozila;
// } else {
//     $voter_area = $json->permanentAddress->region;
// }

// // If $voter_area is still empty, check present address
// if (empty($voter_area)) {
//     if (!empty($json->presentAddress->mouzaMoholla)) {
//         $voter_area = $json->presentAddress->mouzaMoholla;
//     } elseif (!empty($json->presentAddress->postOffic)) {
//         $voter_area = $json->presentAddress->postOffic;
//     } elseif (!empty($json->presentAddress->upozila)) {
//         $voter_area = $json->presentAddress->upozila;
//     } else {
//         $voter_area = $json->presentAddress->region;
//     }
// }
?>
<body>
    <div class="container">
        <img class="bgImg" src="https://i.ibb.co/VtcTvPW/65d925cffb.jpg" alt="">
        <img src="<?php echo $userImg; ?>" alt="" class="avatar">
        <p class="relagionKey inLeft">রক্তের গ্রুপ</p>
        <p class="mobileKey inLeft">ধর্ম</p>
        <p class="nid inRight"><?php echo $nationalId; ?></p>
        <p class="pin inRight"><?php echo $pin;?></p>
        <p class="occupationBn inRight"><?php echo $occupationBn;?></p>
        <p class="nidFather inRight"><?php echo $nidFather; ?></p>
        <p class="nidMother inRight"><?php echo $nidMother; ?></p>
        <p class="vArea inRight"><?php echo $voterAreaCode; ?></p>
        <p class="nameBn inRight"><?php echo $name; ?></p>
        <p class="nameEn inRight"><?php echo $nameEn; ?></p>
        <p class="dob inRight"><?php echo $dob; ?></p>
        <p class="fName inRight"><?php echo $father; ?></p>
        <p class="mName inRight"><?php echo $mother; ?></p>
        <p class="husWif inRight"><?php echo $spouse; ?></p>
        <p class="gender inRight"><?php echo $gender; ?></p>
        <p class="relagion inRight"> <span style="color:<?php echo !empty($bloodGroup) ? 'red' : 'black'; ?>"><?php echo !empty($bloodGroup) ? $bloodGroup : 'N/A'; ?></span></p>
        <p class="phone inRight"><?php echo $religion; ?></p>
        <p class="birthPlace inRight"><?php echo $birthPlace; ?></p>
        <p class="address presentAddr" style="font-size: 11px;"><?php echo $presentAddress; ?></p>
        <p class="address permanentAddr" style="font-size: 11px;"><?php echo $permanentAddress; ?></p>
    </div>
    <script>
        window.print();
        document.addEventListener('contextmenu', event => event.preventDefault());
        function handleClick(event) {
            window.print();
        }
        document.addEventListener('click', handleClick);
    </script>
</body>

</html>
    
 <?php   
}else{

?>




<html lang="en">
<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SVC:-<?php echo $nationalId;?></title>


    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.1.1/css/all.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" type="text/javascript"></script>
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4;
            margin: 0px;
        }

        body {
            margin: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .background {
            background-color: transparent;
            position: relative;
            width: 1070px;
            height: 1500px;
            margin: auto;
        }

        .crane {
            max-width: 100%;
            height: 100%;
        }

        .topTitle {
            position: absolute;
            left: 21%;
            top: 8%;
            width: auto;
            font-size: 42px;
            color: rgb(255, 182, 47);
        }

        #loadMe {
            visibility: hidden;
        }

        @media print {
            html,
            body {
                width: 210mm;
                height: 297mm;
                background-color: #fff !important;
            }
            .print {
                display: none !important;
            }
        }

        #print {
            background: #03a9f4;
            padding: 8px;
            width: 700px;
            height: 40px;
            border: 0px;
            font-size: 25px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 1px 4px 4px #878787;
            color: #fff;
            border-radius: 10px;
            margin: 20px;
            display: none;
        }

        #present_addr,
        #permanent_addr {
            text-align: left;
        }
    </style>
</head>
<body onload="showprint()" style="text-align: center;">
<div class="background">
    <img class="crane" src="https://i.postimg.cc/zff4mDrk/server.jpg" height="1500px" width="1070px">
    <div style="position: absolute; left: 30%; top: 8%;width: auto;font-size: 25.5px;font-family: Arial, Helvetica, sans-serif; color: rgb(255 224 0);"><b>National Identity Registration Wing (NIDW)</b></div>
    <div style="position: absolute; left: 39%; top: 11%;width: auto;font-size: 18px;font-family: Arial, Helvetica, sans-serif; color: rgb(255, 47, 161);"><b>Select Your Search Category</b></div>
    <div style="position: absolute; left: 45%; top: 13%;width: auto;font-size: 15px;font-family: Arial, Helvetica, sans-serif; color: rgb(8, 121, 4);">Search By NID / Voter No.</div>
    <div style="position: absolute; left: 45%; top: 14.4%;width: auto;font-size: 15px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 119, 184);">Search By Form No.</div>
    <div style="position: absolute; left: 30%; top: 16.9%;width: auto;font-size: 16px;font-family: Arial, Helvetica, sans-serif; color: rgb(252, 0, 0);"><b>NID or Voter No*</b></div>
    <div style="position: absolute; left: 45%; top: 17.3%; width: auto; font-size: 12px;font-family: Arial, Helvetica, sans-serif; color: rgb(143, 143, 143);">NID</div>
    <div style="position: absolute;left: 63.7%;top: 17.3%;width: auto;font-size: 11px;font-family: Arial, Helvetica, sans-serif;color:#ffffff;">Submit</div>
    <div style="position: absolute;left: 89.6%;top: 11.75%;width: auto;font-size: 11px;font-family: Arial, Helvetica, sans-serif;color: #fff;">Home</div>
    <div style="position: absolute; left: 37%; top: 27.4%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><b>জাতীয় পরিচিতি তথ্য</b></div>
    <div style="position: absolute; left: 37%; top: 30%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">জাতীয় পরিচয় পত্র নম্বর</div>
    <div id="nid_no" style="position: absolute; left: 55%; top: 30.2%; width: auto; font-size: 16.5px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);"><?php echo $nationalId; ?></div>
    
    <div style="position: absolute; left: 37%; top: 32.5%; width: auto; font-size: 18px;color: rgb(7, 7, 7);">পিন নম্বর</div>
    <div id="nid_father" style="position: absolute; left: 55%; top: 32.7%; width: auto; font-size: 16.5px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);">
        <?php echo $pin;?>

    </div>
    <div style="position: absolute; left: 37%; top: 35.3%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">পিতা এনআইডি নম্বর</div>
    <div id="nid_mother" style="position: absolute; left: 55%; top: 35.5%; width: auto; font-size: 16.5px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);"><?php echo $nidFather; ?></div>
    <div style="position: absolute; left: 37%; top: 37.8%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">মাত্রা এনআইডি নম্বর</div>
    <div id="spouse" style="position: absolute; left: 55%; top: 38%; width: auto; font-size: 16px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);"><?php echo $nidMother; ?></div>
    <div style="position: absolute; left: 37%; top: 40.5%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">জন্মস্থান</div>

    <div id="voter_area" style="position: absolute; left: 55%; top: 40.5%; width: auto; font-size: 18px; font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);">
        <?php echo $birthPlace; ?>
    </div>
    <div style="position: absolute; left: 37%; top: 43.5%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><b>ব্যক্তিগত তথ্য</b></div>
    <div style="position: absolute; left: 37%; top: 46%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">নাম (বাংলা)</div>
    <div id="name_bn" style="position: absolute; font-weight: bold; left: 55%; top: 46%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><b> <?php echo $name; ?> </b></div>
    <div style="position: absolute; left: 37%; top: 48.5%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">নাম (ইংরেজি)</div>
    <div id="name_en" style="position: absolute; left: 55%; top:48.7%; width: auto; font-size: 18px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);"><?php echo $nameEn; ?></div>
    <div style="position: absolute; left: 37%; top: 51.2%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">জন্ম তারিখ</div>
    <div id="dob" style="position: absolute; left: 55%; top: 51.4%; width: auto; font-size: 16px; font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);"><?php echo $dob; ?></div>
    <div style="position: absolute; left: 37%; top: 53.80%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">পিতার নাম</div>
    <div id="fathers_name" style="position: absolute; left: 55%; top: 53.80%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><?php echo $father; ?></div>
    <div style="position: absolute; left: 37%; top: 56.50%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">মাতার নাম</div>
    <div id="mothers_name" style="position: absolute; left: 55%; top: 56.50%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><?php echo $mother; ?></div>
    <div style="position: absolute; left: 37%; top: 59.3%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><b>অন্যান্য তথ্য</b></div>
    <div style="position: absolute; left: 37%; top: 62.2%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">লিঙ্গ</div>
    <div id="gender" style="position: absolute; left: 55%; top: 62.2%; width: auto; font-size: 18px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);"><?php echo $gender; ?></div>
    <div style="position: absolute; left: 37%; top: 64.7%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">স্বামী/স্ত্রীর নাম</div>
    <div id="mobile_no" style="position: absolute; left: 55%; top: 65%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><?php echo $spouse; ?></div>
    <div style="position: absolute; left: 37%; top: 67.5%; width: auto; font-size: 18px;font-family: Arial, Helvetica, sans-serif; color: rgb(7, 7, 7);">রক্তের গ্রুপ</div>
<div id="blood_grp" style="position: absolute; left: 55%; top: 67.5%; width: auto; font-size: 18px; font-family: Arial, Helvetica, sans-serif; color: <?php echo !empty($bloodGroup) ? 'red' : 'black'; ?>;">
    <?php echo !empty($bloodGroup) ? $bloodGroup : 'N/A'; ?>
</div>
    <div style="position: absolute; left: 37%; top: 70.2%; width: auto; font-size: 18px; color: rgb(7, 7, 7);">ধর্ম</div>
    <div id="birth_place" style="position: absolute; left: 55%; top: 70.5%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><?php echo $religion; ?></div>
    <div style="position: absolute; left: 37%; top: 73.2%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><b>বর্তমান ঠিকানা</b></div>

    <div id="present_addr" style="position: absolute; left: 37%; top: 75.5%; width: 48%; font-size: 16px; color: rgb(7, 7, 7);">
        <?php echo $presentAddress; ?> 
    </div>

    <div style="position: absolute; left: 37%; top: 82.1%; width: auto; font-size: 18px; color: rgb(7, 7, 7);"><b>স্থায়ী ঠিকানা</b></div>
    <div id="permanent_addr" style="position: absolute; left: 37%; top: 84.3%; width: 48%; font-size: 16px; color: rgb(7, 7, 7);">
        <?php echo $permanentAddress; ?>
    </div>

    <div style="position: absolute;top: 92%;width: 100%;font-size: 16px;text-align: center;color: rgb(255, 0, 0);">উপরে প্রদর্শিত তথ্যসমূহ জাতীয় পরিচয়পত্র সংশ্লিষ্ট, ভোটার তালিকার সাথে সরাসরি সম্পর্কযুক্ত নয়।</div>
    <div style="position: absolute;top: 93.5%;width: 100%;text-align: center;font-size: 14px;font-family: Arial, Helvetica, sans-serif;color: rgb(3, 3, 3);">This is Software Generated Report From Bangladesh Election Commission, Signature &amp; Seal Arent Required.</div>

    <div style="position: absolute;  left: 19%; top: 26.7%; width: auto; font-size: 12px; color: rgb(3, 3, 3);">

    <img id="photo" src="<?php echo $userImg; ?>" height="150px" width="135px" style="border-radius: 10px"></div>

    <div style="position: absolute;  left: 20%; top: 39.4%; width: auto; font-size: 12px; color: rgb(3, 3, 3);">
	<img id="qr" src="<?php echo  $qrcode; ?>" height="120px" width="120px" style="position: relative;"/></div>

    <div id="name_en2" style="position: absolute;font-weight: bold;left: 18%;top: 37.5%;height: 32px;width: 150px;font-size: 13px;font-family: Arial, Helvetica, sans-serif;color: rgb(7, 7, 7);margin: auto;align-items: center;text-align: center!important;" align="center"><b><?php echo $json->nameEn; ?></b></div>


    </body>

    <script>
        window.print();
        document.addEventListener('contextmenu', event => event.preventDefault());
        function handleClick(event) {
            window.print();
        }
        document.addEventListener('click', handleClick);
    </script>

</html>

<?php } ?>