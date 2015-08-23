<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){  
?><div style=" margin:10px;" align="left"><?
/************** check deleted images***************/
$sql="select * from galleries_photos ";
$res=mysql_query($sql);
$deletedRecs=0;
$missinFiles='';
while($row=mysql_fetch_array($res)){
	$fid=$row['id'];
	$folder=$row['folder'];
	$photo=$row['photo'];
	$checkSlash=substr($folder,0,1);
	if($checkSlash=='/'){
		$folder=substr($folder,1);
		updateFolder($folder,$fid);
	}
	$file='../../../uploads/'.$folder.$photo;	
	if(!file_exists($file)){
		delPhoto($row['id']);
		$missinFiles.='<div style="color:#600">'.$file.'</div>';
		$deletedRecs++;
	}
}
echo 'Missing Files : '.$deletedRecs;
echo "<p>".$missinFiles."</p>";

/**************Sync files***************/
$files=loadFiles($_POST['folder']);
$syncFiles=0;
$syncFilesList='';
for($i=0;$i<count($files);$i++){
	//echo "<br>".$files[$i];
	if(!checkFile($files[$i])){
		$syncFiles++;
		$syncFilesList.='<div style="color:#060">-'.$files[$i].'</div>';	
	}
}
echo 'Syne Files : '.$syncFiles;
echo "<p>".$syncFilesList."</p>";
}

function updateFolder($folder,$fid){
	$sql2="UPDATE galleries_photos set folder='$folder' where id ='$fid'";
	$res=mysql_query($sql2);
}
?>
</div>