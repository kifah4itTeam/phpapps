<?php
session_start();
if(!isset($_SESSION['sessionname']) && !isset($_SESSION['sessionpass'])){
	header("location:login.php");
	exit();
	}
echo "Welcome to admin page";
echo "<br/>
<a href='logout.php'>logout !</a>"

?>