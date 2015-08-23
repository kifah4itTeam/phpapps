
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>sginup</title>
</head>

<body>
<?php include("connectdb.php");?>
 <?php
$name=$_POST['firstname'];
$email=$_POST['email'];
$sql="insert into form_recruitment (firstname,email)values ('$name' ,'$email')"; 
mysqli_query($connection,$sql);
	?>
</body>
</html>