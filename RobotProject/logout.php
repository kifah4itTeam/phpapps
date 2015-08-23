<?php
ob_start();
session_start();
if(!isset($_SESSION['sessionname']) && !isset($_SESSION['sessionpass'])){
	header("location:workshop.php");
	exit();
	}
else{
	session_destroy();
	die("<h3>logout</h3>");
	}

ob_end_flush();
?>