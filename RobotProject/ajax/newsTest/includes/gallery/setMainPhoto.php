<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){  
	if(isset($_POST['id'])){
		$id=$_POST['id'];
		$gid=$_SESSION['gid'];	
		$sql2="update galleries_galleries set main_photo='$id' where id='$gid'";
		$res2=mysql_query($sql2);
	}
}
?>