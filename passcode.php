<?php
session_start();

if(isset($_POST['passcode'])) {
	$pass = $_POST['passcode'];

	if ($pass == '1234') {
		print true;

		$_SESSION["pin"] = "totes";
	} else {
		print false;

		$_SESSION["pin"] = "error";
	}

} else {
	echo 'No direct access';
	header('location:index.php'); 
}


?>