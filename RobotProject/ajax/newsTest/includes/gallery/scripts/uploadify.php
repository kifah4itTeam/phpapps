<?  
session_start();
include_once('../../../../config.php'); 
include_once('../../../common/dbConnection.php'); 
include_once('../../dbUtils.php'); ?>
<?php
if (!empty($_FILES)) {
	$availableEx = array("jpg","gif","png","jpeg","pdf","doc","docx","xls","xlsx","flv","mp4","wmv");
	$videoEx = array("flv","mp4","wmv");
	
	$upfolder =$_REQUEST['upfolder'];
	$tempFile = $_FILES['Filedata']['tmp_name'];
	
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$targetPath = str_replace('//','/',$targetPath);
	
	$targetFile = $targetPath.$_FILES['Filedata']['name'];
	$realName = str_replace(" ","_",$_FILES['Filedata']['name']);
	$photo=$realName;
	while(file_exists($targetPath.$photo)){
		$fileNameParts = explode(".", "$photo");
		$fileExtension = end($fileNameParts); // part behind last dot
		$ext = $fileExtension."";
		$photo=$fileNameParts[0]."1.".$ext;
	}
	$targetFile = $targetPath.$photo;
	
	$fileNameParts = explode(".", $_FILES['Filedata']['name']);
	$fileExtension = end($fileNameParts);
	if(in_array($ext,$videoEx)){
		$thumbFile='video';
	}else{
		$thumbFile =  "mcith/mcith_".randomStringUtil(20).'.'.$fileExtension;
	}
	
	

	$ext = strtolower(end(explode('.',$photo)));
	if(in_array($ext,$availableEx)){		
		move_uploaded_file($tempFile,$targetFile);

		$SQL = "INSERT INTO galleries_photos (photo , description , thumb, folder ) VALUES ('$photo','&nbsp;','$thumbFile' ,'$upfolder' )";
		$result = mysql_query($SQL);
		if($result)echo 1;		
	}
	
}
?>