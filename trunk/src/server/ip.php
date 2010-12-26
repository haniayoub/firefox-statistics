<?php
include_once('ip2c/ip2country.php');
$ip2c=new ip2country();
$ip2c->mysql_host='juniorhosting.net';
$ip2c->db_user='ee_project_ff_st';
$ip2c->db_pass='ee1234';
$ip2c->db_name='ee_project_ff_statistics';
$ip2c->table_name='ip2c';
echo 'Your country name is '. $ip2c->get_country_name() . '<br>';
echo 'Your country code is ' . $ip2c->get_country_code();
?>