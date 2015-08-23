<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
<?php
include("connectdb.php");
if($id=$_GET['id']){
echo $id;
}
?>
</body>
</html>