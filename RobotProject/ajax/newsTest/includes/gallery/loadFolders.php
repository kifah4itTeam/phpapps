<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){  
	if(isset($_POST['path'])){
		$path=$_POST['path'];
		$actPath=$_POST['actPath'];
		echo loadFolders($path,$actPath);
	}
}
?>