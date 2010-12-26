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

$d = dir("tmp/");
while (false !== ($entry = $d->read()) && ($entry != ".") && ($entry != "..")) {
        unlink("tmp/".$entry);
}


//$query = "SELECT MAX(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as max, MIN(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as min, AVG(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as avg, COUNT(1) as count, SUM(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as sum FROM DutyTimes";
$query = "SELECT STD(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as SD, MAX(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as max, MIN(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as min, AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as avg, COUNT(1) as count, SUM(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as sum FROM DutyTimes";
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

	$query = "SELECT COUNT(1) as numOfUsers FROM (SELECT DISTINCT userid FROM Nodes) O";
	$result = mysql_query($query);	
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$numOfUsers = $row['numOfUsers'];
	}
	$query = "SELECT COUNT(1) as numOfNodes FROM Nodes";
	$result = mysql_query($query);	
	if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$numOfNodes = $row['numOfNodes'];
	}
	
	$query = "SELECT WEEK(MIN(start)) as startDate, YEAR(MIN(start)) as startYear, WEEK(MAX(end)) as endDate, Year(MAX(end)) as endYear FROM DutyTimes";
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
	echo "									Summary information:</span><span class=\"Apple-style-span\" style=\"border-collapse: separate; color: rgb(0, 0, 0); font-family: Times New Roman; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt; font-style: italic\"><br>\n";
	echo "									</span>\n";
	echo "									<span class=\"Apple-style-span\" style=\"border-collapse: separate; color: rgb(0, 0, 0); font-family: Times New Roman; font-style: normal; font-variant: normal; font-weight: normal; letter-spacing: normal; line-height: normal; orphans: 2; text-align: auto; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-border-horizontal-spacing: 0px; -webkit-border-vertical-spacing: 0px; -webkit-text-decorations-in-effect: none; -webkit-text-size-adjust: auto; -webkit-text-stroke-width: 0px; font-size: 12pt\">\n";
	echo "									- Number Of Users: ".$numOfUsers." (".$numOfNodes." Nodes)<br>\n";
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

$start = isset($_GET["hist_t_start"])?$_GET["hist_t_start"]:"";
$end = isset($_GET["hist_t_end"])?$_GET["hist_t_end"]:"";
$offset = isset($_GET["hist_t_offset"])?$_GET["hist_t_offset"]:"";
$histdataBool = (($start != "") && ($end != "") && ($offset != ""));
$xArray = array();
$probArray = array();
$disp=(($histdataBool)?"":"none");
echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('hist_1');showHideToggle('hist_2');\" style=\"cursor:hand;\">\nShow/Hide Usage probability (histogram) from T_start to T_end</a></u></td></tr>";
echo "<tr><td align=\"center\"><div id=\"hist_1\" style=\"display:".$disp.";\"><form method=\"get\">(all times given in seconds)<br>T Start: <input name=hist_t_start><br>T End: <input name=hist_t_end><br>T Offset: <input name=hist_t_offset><br><input type=submit value=\"Go!\"></form></div></td></tr>";
$i = 0;
if ($histdataBool) {
	$query = "SELECT COUNT(1) as count FROM DutyTimes"; // calculating total count
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$totalcount=$row["count"];
	for ($temp_t = $start; $temp_t < $end; $temp_t += $offset)
	{
		$temp_t2 = $temp_t+$offset;
		$query = "SELECT COUNT(1) as count FROM DutyTimes WHERE (UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) BETWEEN ".$temp_t." AND ".$temp_t2; // calculating Count(t>t2)
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$count = $row["count"];
		//mysql_query("DROP VIEW IF EXISTS mytempView2");
		$xArray[$i] = $temp_t;
		$probArray[$i++] = 100*$count/$totalcount;
	}


	echo "<tr><td align=\"center\"><div id=\"hist_2\" style=\"display:".$disp.";\"><p align=\"center\">";
	createGraphNoValues($xArray,$probArray, "Probability", "red", "", "Probabilities to stay between X and X+".$offset." seconds");
	echo "</p>";
	
	echo "<center><table border=1><tr><td>between X and X+".$offset." (seconds)</td>";
	foreach ($xArray as $value) {
		echo "<td>".$value."</td>";
	}
	echo "</tr><tr><td>Probability</td>";
	foreach ($probArray as $value) {
		echo "<td>".round($value, 3)."</td>";
	}
	echo "</tr></table></center>";
} else {
	echo "<tr><td align=\"center\"><div id=\"hist_2\" style=\"display:".$disp.";\"><p align=\"center\">";
}

echo "</div></td></tr></table><br>";


$start = isset($_GET["accum_t_start"])?$_GET["accum_t_start"]:"";
$end = isset($_GET["accum_t_end"])?$_GET["accum_t_end"]:"";
$offset = isset($_GET["accum_t_offset"])?$_GET["accum_t_offset"]:"";
$accumdataBool = (($start != "") && ($end != "") && ($offset != ""));
$xArray = array();
$probArray = array();
$disp=(($accumdataBool)?"":"none");
echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('accum_1');showHideToggle('accum_2');\" style=\"cursor:hand;\">\nShow/Hide Usage accumilative probability of users with duty time greater than T</a></u></td></tr>";
echo "<tr><td align=\"center\"><div id=\"accum_1\" style=\"display:".$disp.";\"><form method=\"get\">(all times given in seconds)<br>T Start: <input name=accum_t_start><br>T End: <input name=accum_t_end><br>T Offset: <input name=accum_t_offset><br><input type=submit value=\"Go!\"></form></div></td></tr>";
$i = 0;
if ($accumdataBool) {
	$query = "SELECT COUNT(1) as count FROM DutyTimes"; // calculating total count
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	$totalcount=$row["count"];
	for ($temp_t = $start; $temp_t <= $end; $temp_t += $offset)
	{
		$temp_t2 = $temp_t+$offset;
		//$query = "SELECT COUNT(1) as count FROM DutyTimes WHERE (UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) BETWEEN ".$temp_t." AND ".$temp_t2; // calculating Count(t>t2)
		$query = "SELECT COUNT(1) as count FROM DutyTimes WHERE (UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) > ".$temp_t; // calculating Count(t>t2)
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$count = $row["count"];
		//mysql_query("DROP VIEW IF EXISTS mytempView2");
		$xArray[$i] = $temp_t;
		$probArray[$i++] = 100*$count/$totalcount;
	}


	echo "<tr><td align=\"center\"><div id=\"accum_2\" style=\"display:".$disp.";\"><p align=\"center\">";
	createGraphNoValues($xArray,$probArray, "Probability", "red", "", "Probabilities to stay more than X seconds");
	echo "</p>";
	
	echo "<center><table border=1><tr><td>more than X (seconds)</td>";
	foreach ($xArray as $value) {
		echo "<td>".$value."</td>";
	}
	echo "</tr><tr><td>Probability</td>";
	foreach ($probArray as $value) {
		echo "<td>".round($value)."</td>";
	}
	echo "</tr></table></center>";
} else {
	echo "<tr><td align=\"center\"><div id=\"accum_2\" style=\"display:".$disp.";\"><p align=\"center\">";
}

echo "</div></td></tr></table><br>";

$start = isset($_GET["t_start"])?$_GET["t_start"]*60.0:"";
$end = isset($_GET["t_end"])?$_GET["t_end"]*60.0:"";
$offset = isset($_GET["t_offset"])?$_GET["t_offset"]*60.0:"";
$t1t2dataBool = (($start != "") && ($end != "") && ($offset != ""));
$xArray = array();
$probArray = array();
$disp=(($t1t2dataBool)?"":"none");
echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('t1t2_1');showHideToggle('t1t2_2');\" style=\"cursor:hand;\">\nShow/Hide Usage probability for T_start & T_end</a></u></td></tr>";
echo "<tr><td align=\"center\"><div id=\"t1t2_1\" style=\"display:".$disp.";\"><form method=\"get\">(all times given in minutes)<br>T Start: <input name=t_start><br>T End: <input name=t_end><br>T Offset: <input name=t_offset><br><input type=submit value=\"Go!\"></form></div></td></tr>";
$i = 0;
//mysql_query("DROP VIEW IF EXISTS mytempView2");
if ($t1t2dataBool) {
	
	for ($temp_t = $start; $temp_t <= $end; $temp_t += $offset)
	{
		$query = "SELECT COUNT(1) as count FROM DutyTimes WHERE (UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) > $temp_t"; // calculating Count(t>t2)
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$count_t2 = $row["count"];
		$query = "SELECT COUNT(1) as count FROM DutyTimes WHERE (UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) > $start"; // calculating Count(t>t1)
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$count_t1 = ($row["count"]==0)?1e-12:$row["count"];
		//mysql_query("DROP VIEW IF EXISTS mytempView2");
		$xArray[$i] = $temp_t/60;
		$probArray[$i++] = 100*$count_t2/$count_t1;
	}


	echo "<tr><td align=\"center\"><div id=\"t1t2_2\" style=\"display:".$disp.";\"><p align=\"center\">";
	createGraphNoValues($xArray,$probArray, "Probability", "red", "", "Probabilities to stay more than X minutes if known that user stayed more than ". $start/60 ." minute(s)");
	echo "</p>";
	
	echo "<center><table border=1><tr><td>X (minutes)</td>";
	foreach ($xArray as $value) {
		echo "<td>".$value."</td>";
	}
	echo "</tr><tr><td>Probability</td>";
	foreach ($probArray as $value) {
		echo "<td>".round($value)."</td>";
	}
	echo "</tr></table></center>";
} else {
	echo "<tr><td align=\"center\"><div id=\"t1t2_2\" style=\"display:".$disp.";\"><p align=\"center\">";
}
echo "</div></td></tr></table><br>";

/*
$query = "SELECT * from (SELECT COUNT(1) as count, WEEK(start) as ww , YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww) O2 , (SELECT COUNT(1) as users, ww FROM (SELECT distinct nodeid, WEEK(start) as ww FROM DutyTimes ORDER BY ww) O GROUP BY ww) O3 WHERE O2.ww=O3.ww";
$result = mysql_query($query);
$wwArray = array();
$countArray = array();
$i = 0;
while($row = mysql_fetch_array($result))
{
	$wwArray[$i] = $row["ww"]."/".substr($row["year"], 2);
	$countArray[$i] = $row["count"]/$row["users"];
	$i++;
}

echo "<p align=\"center\">";
createGraph($wwArray,$countArray, "# usage per work week by different users", "blue", "", "How many times Firefox used the add-on");
echo "</p>";

echo "<p align=\"center\"><font size=\"6\">------------------------------------------------------</font></p>";
*/

echo "\n<table border=0 width=\"100%\" bgcolor=\"#C0C0C0\"><tr><td align=\"center\"><u><a onclick=\"javascript:showHideToggle('WWGraphs1');showHideToggle('WWGraphs2');\" style=\"cursor:hand;\">\nShow/Hide Usage vs. WW graphs</a></u></td></tr>";
//get info for graph
//$query = "SELECT AVG(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as duty, COUNT(1) as count,  WEEK(start) as ww, YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww";

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, WEEK(start) as ww, YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww) O2, (SELECT COUNT(1) as users, ww, year FROM (SELECT distinct nodeid, WEEK(start) as ww, YEAR(start) as year FROM DutyTimes ORDER BY ww) O GROUP BY ww) O3 WHERE O2.ww=O3.ww AND O2.year=O3.year";
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
//$query = "SELECT AVG(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as duty, COUNT(1) as count,  WEEK(start) as ww, YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww";

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, DAYOFMONTH(start) as cat FROM DutyTimes GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, DAYOFMONTH(start) as cat FROM DutyTimes ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
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
//$query = "SELECT AVG(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as duty, COUNT(1) as count,  WEEK(start) as ww, YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww";

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, DAYOFWEEK(start) as cat FROM DutyTimes GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, DAYOFWEEK(start) as cat FROM DutyTimes ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
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
//$query = "SELECT AVG(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as duty, COUNT(1) as count,  WEEK(start) as ww, YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww";

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, MONTH(start) as cat FROM DutyTimes GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, MONTH(start) as cat FROM DutyTimes ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
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
//$query = "SELECT AVG(TIME_TO_SEC(end)-TIME_TO_SEC(start)+DATEDIFF(end,start)*86400) as duty, COUNT(1) as count,  WEEK(start) as ww, YEAR(start) as year FROM DutyTimes GROUP BY ww ORDER BY ww";

$query = "SELECT * FROM (SELECT AVG(UNIX_TIMESTAMP(end)-UNIX_TIMESTAMP(start)) as duty, COUNT(1) as count, HOUR(start) as cat FROM DutyTimes GROUP BY cat ORDER BY cat) O2, (SELECT COUNT(1) as users, cat FROM (SELECT distinct nodeid, HOUR(start) as cat FROM DutyTimes ORDER BY cat) O GROUP BY cat) O3 WHERE O2.cat=O3.cat";
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