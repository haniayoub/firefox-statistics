<script type="text/javascript">
function showHideToggle(myObj) {
	if (document.getElementById(myObj).style.display == '') {
        document.getElementById(myObj).style.display = 'none';
    } else {
        document.getElementById(myObj).style.display = '';
    }
}
</script>

<html>
<meta http-equiv="Content-Script-Type" content="text/javascript">
<head><title>Statistics page</title></head>
<body>

<?php
require_once("connect_to_db.php");
require_once("create_graphs.php");
require_once("funcs.php");
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

session_start();
$sid = session_id();
$currNodeid;
$query = "SELECT nodeid FROM sessions WHERE sid='".$sid."'";
$result = mysql_query($query);
if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$currNodeid = $row["nodeid"];
} else {
	echo "There is no such sid: $sid !";
}

$d = dir("tmp/");
while (false !== ($entry = $d->read()) && ($entry != ".") && ($entry != "..")) {
        unlink("tmp/".$entry);
}


$query = "SELECT STD(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as SD, MAX(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as max, MIN(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as min, AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as avg, COUNT(1) as count, SUM(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as sum FROM DutyTimes WHERE nodeid='".$currNodeid."'";
$result = mysql_query($query);
if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

	$min   = getTimeString($row['min']);
	$avg = getTimeString($row['avg']);
	$sd = getTimeString($row["SD"]);
	$max = getTimeString($row['max']);
	$sum = getTimeString($row['sum']);
	$count = $row['count'];
	$numOfNodes = "";
	$numOfUsers = "";
	$startDate = "";
	$endDate = "";

	$query = "SELECT WEEK(MIN(start)) as startDate, YEAR(MIN(start)) as startYear, WEEK(MAX(end)) as endDate, Year(MAX(end)) as endYear FROM DutyTimes WHERE nodeid='".$currNodeid."'";
	$result = mysql_query($query);	
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$startDate = $row['startDate']." ".$row['startYear'];
		$endDate = $row['endDate']." ".$row['endYear'];
	}

	echo "<br>";	
	echo "<p style=\"line-height: 150%\" align=\"center\"><b>\n";
	echo "<font face=\"Times New Roman\" style=\"font-size: 24pt; font-style: italic\" color=\"#0066CC\">\n";
	echo "DHT Firefox Add-On Global Statistics <br><br> From WW".$startDate." to WW".$endDate."</font></b></p>\n";
	echo "<blockquote>\n";
	echo "	<blockquote>\n";
	echo "		<blockquote>\n";
	echo "			<blockquote>\n";
	echo "				<blockquote>\n";
	echo "					<blockquote>\n";
	echo "						<blockquote>\n";
	echo "							<blockquote>\n";
	echo "								<blockquote>\n";
	echo "									<p style=\"line-height: 150%\" align=\"left\">\n";
	echo "									<b>\n";
	echo "									<font face=\"Times New Roman\" color=\"#0066CC\"><frame name=\"I1\" width=\"1252\" height=\"204\">\n";
	echo "									</font></b>\n";
	echo "									<span class=\"Apple-style-span\" style=\"border-collapse: separate; color: #0066CC; font-family: Times New Roman; font-style: italic; font-variant: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt; font-weight: 700\">\n";
	echo "									Node's summary information:</span><span class=\"Apple-style-span\" style=\"border-collapse: separate; color: rgb(0, 0, 0); font-family: Times New Roman; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt; font-style: italic\"><br>\n";
	echo "									</span>\n";
	echo "									<span class=\"Apple-style-span\" style=\"border-collapse: separate; color: rgb(0, 0, 0); font-family: Times New Roman; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt\">\n";
	echo "									- Mean duty time: ".$avg."<br>\n";
	echo "									- Duty time Standard deviation: ".$sd."<br>\n";
	echo "									- Max duty time: ".$max."<br>\n";
	echo "									- Min duty time: ".$min. "<br>\n";
	echo "									- Total duty time: ".$sum."<br>\n";
	echo "									- Add-on usage: ".$count."<br>\n";
	echo "								</blockquote>\n";
	echo "							</blockquote>\n";
	echo "						</blockquote>\n";
	echo "					</blockquote>\n";
	echo "				</blockquote>\n";
	echo "			</blockquote>\n";
	echo "		</blockquote>\n";
	echo "	</blockquote>\n";
	echo "</blockquote>\n";

}

echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('WWGraphs1');showHideToggle('WWGraphs2');\" style=\"cursor:hand;\">\nShow/Hide Usage vs. WW graphs</a></u></td></tr>";
//get info for graph
//$query = "SELECT AVG(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as duty, COUNT(1) as count,  WEEK(start) as ww, YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww";

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, WEEK(start) as ww, YEAR(start) as year FROM DutyTimes WHERE nodeid='".$currNodeid."' GROUP BY ww ORDER BY ww) O2, (SELECT COUNT(1) as users, ww, year FROM (SELECT distinct nodeid, WEEK(start) as ww, YEAR(start) as year FROM DutyTimes WHERE nodeid='".$currNodeid."' ORDER BY ww) O GROUP BY ww) O3 WHERE O2.ww=O3.ww AND O2.year=O3.year";
$result = mysql_query($query);
$wwArray = array();
$countArray = array();
$avgArray = array();
$i = 0;
while($row = mysql_fetch_array($result))
{
	$wwArray[$i] = $row["ww"]."/".substr($row["year"], 2);
	$countArray[$i] = $row["count"]/$row["users"];
	$avgArray[$i] = $row["duty"]/60;
	$i++;
}

echo "<tr><td align=\"center\"><div id=\"WWGraphs1\" style=\"display:none;\">";
createGraph($wwArray,$avgArray, "Avg duty time (min) vs. work week", "red", "", "How long have users been in Firefox (Average - in minutes)");
echo "</div></td></tr><tr><td align=\"center\"><div id=\"WWGraphs2\" style=\"display:none;\">";
createGraph($wwArray,$countArray, "# of usages per node vs. work week", "blue", "", "How many Firefox usage hits per node vs. work week");
echo "</div></td></tr></table><br>";


echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('DaysGraphs1');showHideToggle('DaysGraphs2');\" style=\"cursor:hand;\">\nShow/Hide Usage vs. Day of month graphs</a></u></td></tr>";
//get info for graph

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, DAYOFMONTH(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, DAYOFMONTH(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
$result = mysql_query($query);
$xArray = array();
$countArray = array();
$avgArray = array();
$i = 0;
while($row = mysql_fetch_array($result))
{
	$xArray[$i] = $row["cat"];
	$countArray[$i] = $row["count"]/$row["users"];
	$avgArray[$i] = $row["duty"]/60;
	$i++;
}

echo "<tr><td align=\"center\"><div id=\"DaysGraphs1\" style=\"display:none;\">";
createGraph($xArray,$avgArray, "Avg duty time (min) vs. day of month", "red", "", "How long have users been in Firefox (Average - in minutes)");
echo "</div></td></tr><tr><td align=\"center\"><div id=\"DaysGraphs2\" style=\"display:none;\">";
createGraph($xArray,$countArray, "# of usages per node vs. day of month", "blue", "", "How many Firefox usage hits per node vs. day of month");
echo "</div></td></tr></table><br>";

echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('DayWEEKGraphs1');showHideToggle('DayWEEKGraphs2');\" style=\"cursor:hand;\">\nShow/Hide Usage vs. Day of week graphs</a></u></td></tr>";
//get info for graph

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, DAYOFWEEK(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, DAYOFWEEK(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
$result = mysql_query($query);
$xArray = array();
$countArray = array();
$avgArray = array();
$i = 0;
while($row = mysql_fetch_array($result))
{
	$xArray[$i] = $row["cat"];
	$countArray[$i] = $row["count"]/$row["users"];
	$avgArray[$i] = $row["duty"]/60;
	$i++;
}

echo "<tr><td align=\"center\"><div id=\"DayWEEKGraphs1\" style=\"display:none;\">";
createGraph($xArray,$avgArray, "Avg duty time (min) vs. day of week", "red", "", "How long have users been in Firefox (Average - in minutes)");
echo "</div></td></tr><tr><td align=\"center\"><div id=\"DayWEEKGraphs2\" style=\"display:none;\">";
createGraph($xArray,$countArray, "# of usages per node vs. day of week", "blue", "", "How many Firefox usage hits per node vs. day of week");
echo "</div></td></tr></table><br>";

echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('MonthsGraphs1');showHideToggle('MonthsGraphs2');\" style=\"cursor:hand;\">\nShow/Hide Usage vs. Month graphs</a></u></td></tr>";
//get info for graph

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, MONTH(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, MONTH(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
$result = mysql_query($query);
$xArray = array();
$countArray = array();
$avgArray = array();
$i = 0;
while($row = mysql_fetch_array($result))
{
	$xArray[$i] = $row["cat"];
	$countArray[$i] = $row["count"]/$row["users"];
	$avgArray[$i] = $row["duty"]/60;
	$i++;
}

echo "<tr><td align=\"center\"><div id=\"MonthsGraphs1\" style=\"display:none;\">";
createGraph($xArray,$avgArray, "Avg duty time (min) vs. month", "red", "", "How long have users been in Firefox (Average - in minutes)");
echo "</div></td></tr><tr><td align=\"center\"><div id=\"MonthsGraphs2\" style=\"display:none;\">";
createGraph($xArray,$countArray, "# of usages per node vs. month", "blue", "", "How many Firefox usage hits per node vs. month");
echo "</div></td></tr></table><br>";

echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('HoursGraphs1');showHideToggle('HoursGraphs2');\" style=\"cursor:hand;\">\nShow/Hide Usage vs. Hour graphs</a></u></td></tr>";
//get info for graph

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, HOUR(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, HOUR(start) as cat FROM DutyTimes WHERE nodeid='".$currNodeid."' ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
$result = mysql_query($query);
$xArray = array();
$countArray = array();
$avgArray = array();
$i = 0;
while($row = mysql_fetch_array($result))
{
	$xArray[$i] = $row["cat"];
	$countArray[$i] = $row["count"]/$row["users"];
	$avgArray[$i] = $row["duty"]/60;
	$i++;
}

echo "<tr><td align=\"center\"><div id=\"HoursGraphs1\" style=\"display:none;\">";
createGraph($xArray,$avgArray, "Avg duty time (min) vs. month", "red", "", "How long have users been in Firefox (Average - in minutes)");
echo "</div></td></tr><tr><td align=\"center\"><div id=\"HoursGraphs2\" style=\"display:none;\">";
createGraph($xArray,$countArray, "# of usages per node vs. month", "blue", "", "How many Firefox usage hits per node vs. month");
echo "</div></td></tr></table><br>";


require_once("disconnect_from_db.php");
?>


<?php

/* OLD FILE
*/
/*
require_once("connect_to_db.php");
require_once("funcs.php");
require_once ('create_graphs.php');
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

session_start();
$sid = session_id();
$currNodeid;
$query = "SELECT nodeid FROM sessions WHERE sid='".$sid."'";
$result = mysql_query($query);
if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$currNodeid = $row["nodeid"];
} else {
	echo "There is no such sid: $sid !";
}


//$currNodeid = 3;
// get summary info
$query = "SELECT MAX(ABS(TIME_TO_SEC(end)-TIME_TO_SEC(start))) as max, MIN(ABS(TIME_TO_SEC(end)-TIME_TO_SEC(start))) as min, AVG(ABS(TIME_TO_SEC(end)-TIME_TO_SEC(start))) as avg, COUNT(1) as count, SUM(ABS(TIME_TO_SEC(end)-TIME_TO_SEC(start))) as sum FROM DutyTimes WHERE nodeid='".$currNodeid."'";
$result = mysql_query($query);
if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

	$min = getTimeString($row['min']) > 0 ? getTimeString($row['min']) : getTimeString(1);
	$avg = getTimeString($row['avg']);
	$max = getTimeString($row['max']);
	$sum = getTimeString($row['sum']);
	$count = $row['count'];
	$joindate = "";
	$specFF = "";
	$specOS = "";
	
	$query = "SELECT DAY(joindate) as day, MONTH(joindate) as month, YEAR(joindate) as year FROM Nodes WHERE nodeid='".$currNodeid."'";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$joindate = $row['day']."/".$row['month']."/".$row['year'];
	}
	$query = "SELECT os, ffversion FROM Specs WHERE nodeid='".$currNodeid."'";
	$result = mysql_query($query);
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$specOS = $row['os'];
		$specFF = $row['ffversion'];
	}

	echo "<br>";	
	echo "<p style=\"line-height: 150%\" align=\"center\"><b>\n";
	echo "<font face=\"Times New Roman\" style=\"font-size: 24pt; font-style: italic\" color=\"#0066CC\">\n";
	echo "DHT Firefox Add-On personal Statistics</font></b></p>\n";
	echo "<blockquote>\n";
	echo "	<blockquote>\n";
	echo "		<blockquote>\n";
	echo "			<blockquote>\n";
	echo "				<blockquote>\n";
	echo "					<blockquote>\n";
	echo "						<blockquote>\n";
	echo "							<blockquote>\n";
	echo "								<blockquote>\n";
	echo "									<p style=\"line-height: 150%\" align=\"left\">\n";
	echo "									<b>\n";
	echo "									<font face=\"Times New Roman\" color=\"#0066CC\"><frame name=\"I1\" width=\"1252\" height=\"204\">\n";
	echo "									</font></b>\n";
	echo "									<span class=\"Apple-style-span\" style=\"border-collapse: separate; color: #0066CC; font-family: Times New Roman; font-style: italic; font-variant: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt; font-weight: 700\">\n";
	echo "									Summary information:</span><span class=\"Apple-style-span\" style=\"border-collapse: separate; color: rgb(0, 0, 0); font-family: Times New Roman; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt; font-style: italic\"><br>\n";
	echo "									</span>\n";
	echo "									<span class=\"Apple-style-span\" style=\"border-collapse: separate; color: rgb(0, 0, 0); font-family: Times New Roman; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt\">\n";
	echo "									- Average duty time: ".$avg."<br>\n";
	echo "									- Max duty time: ".$max."<br>\n";
	echo "									- Min duty time: ".$min. "<br>\n";
	echo "									- Total duty time: ".$sum."<br>\n";
	echo "									- Add-on usage: ".$count."<br>\n";
	echo "									- Firefox version: ".$specFF.",  OS: ".$specOS."<br>\n";
	echo "									- Join date: ".$joindate."</span></p>\n";
	echo "								</blockquote>\n";
	echo "							</blockquote>\n";
	echo "						</blockquote>\n";
	echo "					</blockquote>\n";
	echo "				</blockquote>\n";
	echo "			</blockquote>\n";
	echo "		</blockquote>\n";
	echo "	</blockquote>\n";
	echo "</blockquote>\n";

}

//get info for graph
$query = "SELECT AVG(ABS(TIME_TO_SEC(end)-TIME_TO_SEC(start))) as duty, COUNT(1) as count,  WEEK(start) as ww, YEAR(start) as year FROM DutyTimes WHERE nodeid='".$currNodeid."' GROUP BY ww ORDER BY ww";
$result = mysql_query($query);
$wwArray = array();
$countArray = array();
$avgArray = array();
$i = 0;
while($row = mysql_fetch_array($result))
{
	$wwArray[$i] = $row["ww"]."/".substr($row["year"], 2);
	$countArray[$i] = intval($row["count"]);
	$avgArray[$i] = intval($row["duty"]/60);
	$i++;
}
require_once("disconnect_from_db.php");

if(empty($wwArray))
	$wwArray[0] = "ww";
if(empty($avgArray))
	$avgArray[0] = "0";
if(empty($countArray))
	$countArray[0] = "0";
	
echo "<p align=\"center\">";
createGraph($wwArray,$avgArray, "Avg duty time (min)", "red", "", "How long have you been in Firefox (Average - in minutes)");
createGraph($wwArray,$countArray, "# usage", "blue", "", "How many times Firefox used the add-on");
echo "</p>";
echo "<br>";
echo "<br>";
echo "<p align=\"center\"><i><span style=\"font-size: 14pt\">For global statistics, please \n"; 
echo "visit </span><a href=\"http://ffstatistics.juniorhosting.net/\">\n"; 
echo "<span style=\"font-size: 14pt\">DHT global statistics</span></a></i></p>\n";
*/
?>