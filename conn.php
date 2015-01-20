<?php
header("Content-Type: text/html;charset=utf-8");
$db = new mysqli('localhost', 'root', '1a2b', 'KindleNote');
date_default_timezone_set('PRC');
if(mysqli_connect_error()){
	echo "Error:Could not connect to database.";
	exit;
}
?>