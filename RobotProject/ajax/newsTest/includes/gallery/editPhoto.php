<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){  
	$id = $_POST['id'];
	$des = $_POST['des'];
	echo $sql = "update galleries_photos set description='$des' WHERE (id = '$id')";
	$result = MYSQL_QUERY($sql);
}
?>
