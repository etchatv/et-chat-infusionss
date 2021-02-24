<?php

	session_start();

	$username = $_SESSION['phpfusion_username'];
	$gender = "n";

	$_SESSION['etchat_v3_logout_url'] = "../index.php?logout=true";

	function __autoload($class_name) {
			require_once ('class/'.$class_name.'.class.php');		
	}

	new CheckUserName(true, $username, $gender);

?>