<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){  
	if(isset($_POST['gid']) && isset($_POST['ids'])){
		$gid=$_POST['gid'];
		$ids=$_POST['ids'];
		$ids_arr=explode(',',$ids);
		$mPhoto=lookupField('galleries_galleries','id','main_photo', $gid);
		if(!in_array($mPhoto,$ids_arr)){
			echo $mPhoto=$ids_arr[0];
		}
		$sql="UPDATE galleries_galleries set photos='$ids' where id='$gid'";
		mysql_query($sql);
	}
}
?>