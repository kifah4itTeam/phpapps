<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php include("connectdb.php"); ?>
<?php 
$name=$_POST['firstname'];
$email=$_POST['email'];
$surname=$_POST['lastname'];
$pass=$_POST['passwd'];
$phone=$_POST['phone'];
$sql="insert into member(member_firstname,member_surname,member_email,member_pass,member_phone)values ('$name' ,'$surname','$email','$pass','$phone')"; 
mysqli_query($connection,$sql);
echo $_POST['email'];
echo "<br>";
 echo $_POST['firstname'];
 echo "<br>"; 
 echo $_POST['lastname']; 
 echo "<br>";
echo $_POST['passwd'];
echo "<br>"; 
echo $_POST['phone'];
echo "<br>"; 
 ?>
</body>
</html>