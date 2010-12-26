<?php
require_once("connect_to_db.php");

$query = "SELECT * FROM Users WHERE userid='".$_POST['MyHash']."'";
$result = mysql_query($query);
if (!mysql_fetch_array($result)) {
	$query = "INSERT INTO Users VALUES ('".$_POST['MyHash']."', '".date('Y-m-d G:i:s')."')";
	mysql_query($query);
}
$query = "SELECT MAX(maxnodeid) as node FROM nodeids";
$result = mysql_query($query);
if (!($row = mysql_fetch_array($result))) {
	$nodeid = 1;
} else {
	$nodeid = $row['node']+1;
}
$maxid = $nodeid+1;
mysql_query("UPDATE nodeids SET maxnodeid='".$maxid."'");
$nodeid = md5(md5(md5($nodeid)));

$bVer=substr($_POST['bVer'], strpos($_POST['bVer'], "Firefox/")+strlen("Firefox/"));
if (strpos($bVer, " ") > 0) {
	$bVer=substr($bVer, 0, strpos($bVer, " "));
}

$query = "INSERT INTO Nodes (nodeid, userid, joindate) VALUES ('".$nodeid."', '".$_POST['MyHash']."', '".date('Y-m-d G:i:s')."')";
mysql_query($query);
$query = "INSERT INTO Specs (nodeid, ffversion, os) VALUES ('".$nodeid."', '".$bVer."', '".$_POST['OS']."')";
mysql_query($query);



session_start();
$sid = session_id();
$query = "INSERT INTO sessions (sid, nodeid) VALUES ('".$sid."','".$nodeid."')";
mysql_query($query);
//echo "BAD!: " . mysql_error();


echo $nodeid;
require_once("disconnect_from_db.php");
?>