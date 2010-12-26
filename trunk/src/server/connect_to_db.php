<?php
if (!date_default_timezone_set("Asia/Jerusalem")) die ("TimeZone Error!");
$conn = mysql_connect("localhost", "ee_project_ff_st", "ee1234");

if (!$conn) {
	die("Error connecting to SQL server!: " . mysql_error());
}

mysql_select_db("ee_project_ff_statistics");
?>