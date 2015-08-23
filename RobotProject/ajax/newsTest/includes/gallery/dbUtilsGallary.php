<?
if (!defined('GOOD')) die();
function loadFolders($rootPath='',$actPath='',$insidPath='',$level=0,$end=0){
	//echo "(".$rootPath."|".$actPath.")<br>";
	$root='../../../uploads/';
	$rootName=$rootPath;
	if($rootPath==''){$rootName='root';}
	$ret='';	
	if($level==0){
		$insidPath=$rootPath;
		$Fclass="folderI";
		if($actPath==$insidPath){$Fclass="folderI2";}
		$ret.='<input type="hidden"  id="correntPath" value="'.$actPath.'"/>
		<div class="folderCont" onClick="loadFolderList(\''.$rootPath.'\',\''.$insidPath.$file.'\',0)">
		<div class="'.$Fclass.'">root</div></div>';
		$level=1;
		
	}	
	$fullPath=$root.$insidPath;
	//echo $fullPath.'<br>';
	$Files=0;
	$Folders=0;
	if(file_exists($fullPath)){
		if ($Folder = opendir($fullPath)){
			while (false !== ($file = readdir($Folder))){
				if ($file != "." && $file != "..") {
					if(is_dir($fullPath.$file)){
						$Folders++;
					}else{
						$Files++;
					}
				}
			}
			closedir($Folder);
		}
		
		if ($Folder = opendir($fullPath)){
			$i=0;
			$xFolsers=array('mcith','_notes','.quarantine','.tmb','cash','temp','help');
			while (false !== ($file = readdir($Folder))){
				if ($file != "." && $file != "..") {
					if (in_array($file,$xFolsers)) {
						$Folders--;
						$i++;
					}else{
						if(is_dir($fullPath.$file)){ 
							$i++;
							$ret.='<div class="folderCont" onClick="loadFolderList(\''.$rootPath.'\',\''.$insidPath.$file.'/\',0)">';
							for($l=1;$l<$level;$l++){
								if($l<$level-$end){
									$ret.='<div class="folder_line"></div>';								
								}else{
									$ret.='<div class="folder_line_empty"></div>';								
								}
							}
							if($i==$Folders){
								$end=$end+1;
								$ret.='<div class="folder_end_line"></div>';							
							}else{
								$ret.='<div class="folder_sub_line"></div>';	
								$end=0;						
							}
							$Fclass="folderI";
							if($actPath==$insidPath.$file.'/'){$Fclass="folderI2";}						
							$ret.='<div class="'.$Fclass.'">'.$file.' </div></div>';
							
							$ret.=loadFolders($rootPath,$actPath,$insidPath.$file.'/',$level+1,$end);					
						}
					}
				}				
			}
			closedir($Folder);
		}
	}
	return $ret;
	
}
$files=array();
function loadFiles($path='',$f=0){
	global $files;
	$root='../../../uploads/';
	$path2=$path;
	if($f==0){	
		$path2=$root.$path;
	}

	if ($Folder = opendir($path2)){
		$xFolsers=array('mcith','_notes','.quarantine','.tmb','cash','temp','help');
		while (false !== ($file = readdir($Folder))){
			if ($file != "." && $file != "..") {
				if (!in_array($file,$xFolsers)){
					if(is_dir($path2.$file)){												
						loadFiles($path2.$file.'/',1);					
					}else{
						$files[]=$path2.$file;
						$ret.='<div class="'.$Fclass.'">'.$file.'</div>';
					}
				}
			}			
		}
		closedir($Folder);
	}
	return $files;
}
function delPhoto($id){
	mysql_query("delete from galleries_photos where id='$id'");
}
function checkFile($p){	
	$exs=array('jpg','jpeg','png','gif','flv','mp4','wmv');
	$videos = array('flv','mp4','wmv');
	$c=explode('/',$p);
	$file= end($c); 
	$folder=str_replace($file,'',$p);
	$folder=str_replace('../../../uploads/','',$folder);
	
	$ex=getFileEx($file);
	if(in_array($ex,$exs)){
		if(in_array($ex,$videos)){
			$thumbFile='video';
		}else{
			$thumbFile =  "mcith/mcith_".randomStringUtil(20).'.'.$ex;
		}
		
		$sql="select count(*) as c from galleries_photos where photo='$file' and folder='$folder'";
		$res=mysql_query($sql);
		$count=mysql_result($res,0,'c');
		if($count==0){		
	
			$sql="INSERT INTO galleries_photos  (folder,photo,thumb)values('$folder','$file','$thumbFile')";
			$res=mysql_query($sql);
			return false;
		}else{
			return true;
		}
	}else{ return true ;}
}
function getFirstFolder($gid){
	$photoFolder='';
	$photos=lookupField('galleries_galleries', 'id', 'photos', $gid); 
	if($photos!=''){
		$ph=explode(',',$photos);
		$photoId=$ph[0];
		$photoFolder=lookupField('galleries_photos', 'id', 'folder', $photoId); 
	}
	return $photoFolder;
}

?>