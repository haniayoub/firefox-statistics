<?

$con = mysql_connect('localhost', 'ee_project_ff_st', 'ee1234');
if (!$con)
 {
 die('Could not connect: ' . mysql_error());
 }

mysql_select_db("ee_project_ff_statistics", $con);

?>