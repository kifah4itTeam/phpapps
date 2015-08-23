<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php define("servername","localhost");
define("username","root");
define("password","");
define("DB","robotsite_db");
$connection=mysqli_connect(servername,username,password,DB);
mysqli_set_charset($connection,"utf8");
if(!$connection)//الاتصال يتم لم
{
	die("error.connect".mysqli_connect_error());
	
	
	}

//echo "succsful connect";


 ?>
</body>
</html>