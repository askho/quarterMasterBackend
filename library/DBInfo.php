<?php
	$DBPass = "password";
	$DBUser = "root";
	$DBHost = "127.0.0.1";
	$DBName = "inventory";
	$DBConn = new mysqli($DBHost, $DBUser, $DBPass, $DBName);
	if ($DBConn->connect_error) {
    	die("Connection failed: " . $DBConn->connect_error);
	}
?>