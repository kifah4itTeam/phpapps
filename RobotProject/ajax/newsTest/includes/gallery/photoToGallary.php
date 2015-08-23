<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){  
if(isset($_POST['id']) && isset($_POST['opr'])){
	$id=$_POST['id'];
	$opr=$_POST['opr'];
	$gid=$_SESSION['gid'];	
	$Q=='';
	$ph=lookupField('galleries_galleries','id','photos',$gid);
	$Mph=lookupField('galleries_galleries','id','main_photo',$gid);
	$photos=explode(',',$ph);
	if($opr=='add'){
		if($ph!=''){
			$newPhotos=$ph.','.$id;			
		}else{
			$newPhotos=$id;
			$sql2="update galleries_galleries set main_photo='$id' where id='$gid'";
			$res2=mysql_query($sql2);
		}
	}
	if($opr=='del'){
		$first_id='';
		$newPhotos='';		
		$first=1;
		for($i=0;$i<count($photos);$i++){
			if($id!=$photos[$i]){
				if($first==1){
					$first=0;
					$first_id=$photos[$i];
				}else{
					$newPhotos.=',';
				}
				$newPhotos.=$photos[$i];			
			}
		}
		if($id==$Mph){
			$Q=" , main_photo='$first_id' ";
		}
	}
	
	//echo $newPhotos;
	echo $sql="UPDATE galleries_galleries set photos='$newPhotos' $Q where id='$gid'";
	$res=mysql_query($sql);
}
}
?>
