<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){ 
$opr=$_REQUEST['opr'];
$Mid=$_REQUEST['id'];
$t=$_REQUEST['t'];
$sql3 = "SELECT * FROM galleries_photos where id='$Mid' limit 1 ";
$res3= mysql_query($sql3);
$rows3 = mysql_numrows($res3);
if($rows3>0){
$selThumb=mysql_result($res3,0,'thumb');
?><div style="float:right; margin:10px; margin-right:50px;"><img src="../../../uploads/<?=$selThumb?>"/></div><div style="margin:10px; float:left"><?
if(isset($_REQUEST['id']) && isset($_REQUEST['opr'])) {	
	if($opr=='info'){	
		$sql = "SELECT * FROM galleries_galleries ";
		$res= mysql_query($sql);
		$rows = mysql_numrows($res);
		if ($rows>0) {
			$i=0;
			$counter=0;
			$albs='';
			while ($i<$rows){
				$id= mysql_result($res,$i,"id");
				$gallery_name= mysql_result($res,$i,"gallery_name_en");
				$description= mysql_result($res,$i,"description_en");
				$main_photo= mysql_result($res,$i,"main_photo");
				$photos= mysql_result($res,$i,"photos");		
				$phos=explode(',',$photos);
				if(in_array($Mid,$phos)){
					$counter++;
					$albs.='<div>'.$counter.' - '.$gallery_name.'</div>';				
				}
				$i++;
			}
			if($counter>0){
				echo '<b>Albums that contain this image : ('.$counter.')</b> '.$albs;
				
			}else{
				echo 'Was not added to any album this image';
			}
		}
		?><div style=" margin-top:10px;">
        <div class="back" onClick="$('#upload_div').hide('fast')" style="margin-right:5px;">Cancel</div>
        <div class="next" onclick="del_photo(<?=$Mid?>,'del','<?=$t?>')">Delete</div>
        </div><?
	}

	if($opr=='del'){
		/*----------------Delete Photo from Galleries----------- */
		$sql = "SELECT * FROM galleries_galleries ";
		$res= mysql_query($sql);
		$rows = mysql_numrows($res);
		if ($rows>0) {
			$i=0;
			
			$albs='';
			while ($i<$rows){
				$id= mysql_result($res,$i,"id");
				$gallery_name= mysql_result($res,$i,"gallery_name");
				$description= mysql_result($res,$i,"description");
				$main_photo= mysql_result($res,$i,"main_photo");
				$photos= mysql_result($res,$i,"photos");
				$phos=explode(',',$photos);
				$e=0;
				$new_photos='';
				$counter=0;
				$Mpoto=0;
				for($f=0;$f<count($phos);$f++){
					if($phos[$f]==$Mid){
						$e=1;
						$counter++;	
						if($phos[$f]==$main_photo){
							$Mpoto=$phos[$f];
							
						}
					}else{
						$new_photos.=$phos[$f].',';
					}
				}
				$new_photos=substr($new_photos,0,strlen($new_photos)-1);
				$Q='';
				if($counter>0){
					if($Mpoto!=0){
						$mp2=explode(',',$new_photos);
						if(count($mp2)>0){
							$Q= " , `main_photo` = '".$mp2[0]."' ";
						}
					}
					$sql2 = "UPDATE `galleries_galleries` SET `photos` = '$new_photos' $Q  WHERE `id` =$id LIMIT 1 ;";
					$res2= mysql_query($sql2);
				}
				
				$i++;
			}

			
				$photo=mysql_result($res3,0,'photo');
				$folder=mysql_result($res3,0,'folder');
				$thumb=mysql_result($res3,0,'thumb');
				
				$sql4 = "DELETE FROM `galleries_photos` WHERE `id` ='$Mid' LIMIT 1 ;";
				$res4= mysql_query($sql4);
				@unlink('../../../uploads/'.$folder.$photo);
				@unlink('../../../uploads/'.$thumb);
	
		}
	}
}
?>
</div> <? }}?>