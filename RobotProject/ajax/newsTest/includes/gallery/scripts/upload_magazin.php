<?session_start(); 
include_once('../../../../config.php'); 
include_once('../../../common/dbConnection.php'); 
include_once('../../dbUtils.php'); 

if (!empty($_FILES)) {
	$m_id = $_REQUEST['m_id'];
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$targetPath = str_replace('//','/',$targetPath);
	$targetFile = $targetPath. $_FILES['Filedata']['name'];
	$thumbFile =  "mcith/mcith_".$_FILES['Filedata']['name'];
	$photo  = $_FILES['Filedata']['name'];
	$availableEx = array("jpg","gif","png","jpeg","pdf","doc","docx","xls","xlsx");
	$ext = strtolower(end(explode('.',$photo)));
	//if(in_array($ext,$availableEx)){
	
		
		move_uploaded_file($tempFile,$targetFile);
		//$thumb = resizeToFile($targetFile, 90, 90, $targetPath.$thumbFile);
		
		$ord=getMagOrder($m_id);

		$SQL = "INSERT INTO magz_pages (`photo`,`content`,`ord`,`magz_id`) VALUES ('$photo','$content','$ord','$m_id')";
	
		$result = mysql_query($SQL);
		echo "<script>alert('$SQL')</script>";
		
	//}
}
?>