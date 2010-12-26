<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-15952194-1");
pageTracker._trackPageview();
} catch(err) {}
</script>

<?php

require_once("connect_to_db.php");

$openedAtStamp=$_POST['OpenedAt'];
$closedAtStamp=$_POST['ClosedAt'];
$currentStamp=$_POST['currentTimeStamp'];
$openedStamps = split(",", $openedAtStamp);
$closedStamps = split(",", $closedAtStamp);
$ip = $_SERVER['REMOTE_ADDR'];

if (isset($_POST['userId']) && ($_POST['userId'] != "")) {
    $query = "SELECT * FROM Users WHERE userid='".$_POST['userId']."'";
    $result = mysql_query($query);
    if (!($row = mysql_fetch_array($result))) {
        $query = "INSERT INTO Users VALUES ('".$_POST['userId']."', '".date('Y-m-d G:i:s')."')";
        mysql_query($query);
    }
    $query = "UPDATE Nodes SET userid='".$_POST['userId']."' WHERE (nodeid='".$_POST['nodeId']."')";
    mysql_query($query);
}

if (count($closedStamps) > 0) {
	$query = "SELECT locationentry as lid FROM Locations WHERE ((nodeid='".$_POST['nodeId']."') AND (ip='".$ip."')) ORDER BY lid DESC";
	$result = mysql_query($query);
	while (!($row = mysql_fetch_array($result))) {
		mysql_query("INSERT INTO Locations (nodeid, ip) VALUES ('".$_POST['nodeId']."', '".$ip."')");
		$query = "SELECT locationentry as lid FROM Locations WHERE ((nodeid='".$_POST['nodeId']."') AND (ip='".$ip."')) ORDER BY lid DESC";
		$result = mysql_query($query);
	}
	$lid = $row['lid'];
}
for ($i=0; $i < count($openedStamps); $i++) {
	if (($openedStamps[$i] != "0") && ($closedStamps[$i] != "0") && ($openedStamps[$i] != "undefined") && ($closedStamps[$i] != "undefined")) {
		$openedAt=date('Y-m-d G:i:s', time()-($currentStamp-$openedStamps[$i]));
		$closedAt=date('Y-m-d G:i:s', time()-($currentStamp-$closedStamps[$i]));
		$query = "INSERT INTO DutyTimes (start, end, nodeid, locationentry) VALUES ('".$openedAt."','".$closedAt."', '".$_POST['nodeId']."', '".$lid."')";
		if (mysql_query($query)) {
			echo "SUCCESS!";
		} else {
			echo "BAD!: " . mysql_error();
		}
	}
}


session_start();
$sid = session_id();
$query = "DELETE FROM sessions WHERE nodeid='".$_POST['nodeId']."'";
mysql_query($query);
$query = "INSERT INTO sessions (sid, nodeid) VALUES ('".$sid."','".$_POST['nodeId']."')";
mysql_query($query);
//echo "BAD!: " . mysql_error();

require_once("disconnect_from_db.php");
?>
