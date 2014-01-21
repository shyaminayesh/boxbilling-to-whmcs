<?php

// SET YOUR BOXBILLING DATABASE DETAUKS HERE
$DBHOST = 'localhost';
$DBUSER = 'whmcs-database-user';
$DBPASS = 'whmcs-database-password';
$DBNAME = 'whmcs-database-name';

// DONT MAKE CHANGES AFTER THIS LINE
$con = mysql_connect($DBHOST, $DBUSER, $DBPASS) or die (mysql_error());
$sdb = mysql_select_db($DBNAME) or die (mysql_error());

$QueryIDSet = mysql_query("SELECT MIN(id) AS min, MAX(id) AS max FROM tblclients") or exit(mysql_error());
$FetchIDSet = mysql_fetch_assoc($QueryIDSet);
$FirID = $FetchIDSet['min'];
$SecID = $FetchIDSet['max'];

while ( $FirID <= $SecID ):

	$GetStatus = mysql_fetch_array(mysql_query("SELECT status FROM tblclients WHERE id='$FirID'"));
	$ClientStatus = $GetStatus['status'];

		if ( $ClientStatus !== 'Active' ):
			$ActivateQuery = mysql_query("UPDATE $DBNAME.`tblclients` SET `status` = 'Active' WHERE `tblclients`.`id` = $FirID");
		endif;

	$FirID++;
endwhile;

// SCRIPT BY SHYAMIN AYESH
// fb.me/Shyamin.Ayesh
// please contact me if you have any issue with this script
// shyaminsaf@gmail.com
// https://www.hostinglions.com <- Sri Lankan Cheap web Hosting

?>
