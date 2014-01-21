<?php

// SET YOUR WHMCS DETAILS HERE
$URL = "https://www.yourwhmcsdomain.com/path/includes/api.php";
$username = "whmcs-admin-username";
$password = "whmcs-admin-password";

// SET YOUR BOXBILLING DATABASE DETAUKS HERE
$DBHOST = 'localhost';
$DBUSER = 'boxbilling-database-user';
$DBPASS = 'boxbilling-database-password';
$DBNAME = 'boxbilling-database-name';

// DONT MAKE CHANGES AFTER THIS LINE
$con = mysql_connect($DBHOST, $DBUSER, $DBPASS) or die (mysql_error());
$sdb = mysql_select_db($DBNAME) or die (mysql_error());

$QueryIDSet = mysql_query("SELECT MIN(id) AS min, MAX(id) AS max FROM client") or exit(mysql_error());
$FetchIDSet = mysql_fetch_assoc($QueryIDSet);
$FirID = $FetchIDSet['min'];
$SecID = $FetchIDSet['max'];

while ( $FirID <= $SecID ):
		$FetchDetails = mysql_fetch_array(mysql_query("SELECT * FROM client WHERE id='$FirID'"));
			if ( $FetchDetails['first_name'] !== null ):
				//echo $FetchDetails['id']."&nbsp;".$FetchDetails['first_name']."</br>";

				// GENARATING NEW PASSWORD TO USERS
				$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
				$pass = array();
				$alphaLength = strlen($alphabet) - 1;
				for ($i = 0; $i < 8; $i++) {
					$n = rand(0, $alphaLength);
				    	$pass[] = $alphabet[$n];
				}

				$UserPasswords = implode($pass);

				// SET DETAILS TO ARRAY
				$postfields = array();
				$postfields["username"] = $username;
				$postfields["password"] = md5($password);
				$postfields["action"] = "addclient";
				$postfields["responsetype"] = "json";
				$postfields["firstname"] = $FetchDetails['first_name'];
				$postfields["lastname"] = $FetchDetails['last_name'];
				$postfields["companyname"] = $FetchDetails['company'];
				$postfields["email"] = $FetchDetails['email'];
				$postfields["address1"] = $FetchDetails['address_1'];
				$postfields["address2"] = $FetchDetails['address_2'];
				$postfields["city"] = $FetchDetails['city'];
				$postfields["state"] = $FetchDetails['state'];
				$postfields["postcode"] = $FetchDetails['postcode'];
				$postfields["country"] = $FetchDetails['country'];
				$postfields["phonenumber"] = $FetchDetails['phone'];
				$postfields["password2"] = $UserPasswords;
				$postfields["customfields"] = base64_encode(serialize(array("1"=>"Google")));
				$postfields["currency"] = "1";

				$query_string = "";
				foreach ($postfields AS $k=>$v) $query_string .= "$k=".urlencode($v)."&";
				 
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $URL);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 30);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				$jsondata = curl_exec($ch);
				if (curl_error($ch)) die("Connection Error: ".curl_errno($ch).' - '.curl_error($ch));
				curl_close($ch);

				$arr = json_decode($jsondata);

				print_r($arr);

				echo '<pre>'.$FetchDetails['first_name'].'&nbsp;'.$FetchDetails['last_name'].'&nbsp;:&nbsp;'.$FetchDetails['email'].'&nbsp;:&nbsp;'.$UserPasswords.'</pre>';

			endif;
	$FirID++;
endwhile;

// SCRIPT BY SHYAMIN AYESH
// fb.me/Shyamin.Ayesh
// please contact me if you have any issue with this script
// shyaminsaf@gmail.com
// https://www.hostinglions.com <- Sri Lankan Cheap web Hosting

?>
