<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){  
	if(isset($_POST['p']) && isset($_POST['n'])){
		$c=$_POST['p'];
		$f=$_POST['n'];
		echo $dir='../../../uploads/'.$c.$f;
		mkdir($dir, 0755);
	}
}
?>