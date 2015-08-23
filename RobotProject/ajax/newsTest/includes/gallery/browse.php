<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){
	$img_arr = array("jpg","jpeg","gif","png","JPG","JPEG","GIF","PNG");
	$gid=$_SESSION['gid'];
	if($gid){?>
<link rel="stylesheet" type="text/css"  href="../css/tipTip.css" />
<script type="text/javascript" src="../js/jquery.tipTip.js"></script>
<script>
	$("img").tipTip({defaultPosition:"top"});
	$(".thumb_img").tipTip({defaultPosition:"top",attribute:"des"});
	
	function fixPhotos(){
		var orderChanges="";
		$("#gal_order div[class=thumb]").each(function(){
			var columnId=$(this).attr("id");
			orderChanges+=columnId+",";				
		});
		var orderChanges=orderChanges.substr(0,orderChanges.length-1);
		loader(1);
		$.post("orderPhotos.php", {gid:<?=$gid?>,ids:orderChanges} ,function(data){
			loader(0);
			if(data!=''){
				setMainPhoto(data);
			}
		});	
	}
	$(document).ready(function(){
		fixPhotos();
		$("#gal_order").hover(function(){
			$(this).css( "cursor", "move" );
		});		
		$("#gal_order").sortable({
			connectWith: "div",
			cursor: "move",
			forcePlaceholderSize: true,
			opacity: 1,
			stop: function(event, ui){
				var orderChanges="";
				var sortorder="";
				$("#gal_order div[class=thumb]").each(function(){
					var columnId=$(this).attr("id");
					orderChanges+=columnId+",";				
				});
				var orderChanges=orderChanges.substr(0,orderChanges.length-1);
				loader(1);
				$.post("orderPhotos.php", {gid:<?=$gid?>,ids:orderChanges} ,function(data){				
					loader(0);
				});	
			}
		})
	})
	</script>
	<input name="des2" type="hidden" id="des2" value="d" size="100"><?
	$sql = "SELECT * FROM galleries_galleries  where id=$gid limit 0,1";
	$result = mysql_query($sql);
	$numberOfRows = mysql_numrows($result);
	if ($numberOfRows>0) {
		$gallery_name= mysql_result($result,0,"gallery_name_en");
		$description= mysql_result($result,0,"description_en");
		$mph= mysql_result($result,0,"main_photo");
		$photos= mysql_result($result,0,"photos");
		$resPhotos=explode(',',$photos);
		if( $photos != ''){
			$sql = "SELECT * FROM galleries_photos where id IN ($photos) ORDER BY FIELD(id,$photos);";
			$resPhotos=array();
	
			$result = MYSQL_QUERY($sql);
			$numberOfRows = MYSQL_NUMROWS($result);
			$i=0;
			
			?><div id="gal_order"><?
			while ($i<$numberOfRows){
				$id= MYSQL_RESULT($result,$i,"id");
				$photo= MYSQL_RESULT($result,$i,"photo");
				$folder= MYSQL_RESULT($result,$i,"folder");
				$description= MYSQL_RESULT($result,$i,"description");
				$thumb= MYSQL_RESULT($result,$i,"thumb");
				if(file_exists('../../../uploads/'.$folder.$photo)){
					if($thumb!='video'){
						resizeToFile('../../../uploads/'.$folder.$photo, 90, 90,'../../../uploads/'.$thumb); 
						$thumbN=_PREF.'uploads/'.$thumb;		
					}else{
						$thumbN=_PREFICO.'video.png';
					}
					$MainPhotoClass='mainPhoto';
					if($mph==$id){
						$MainPhotoClass='mainPhotoAct';
						echo '<script>mainPhoto='.$id.'</script>';
					}
					resizeToFile('../../../uploads/'.$photo, 90, 90,'../../../uploads/'.$thumb); ?>
					<div class="thumb" align="center" id="<?=$id?>">										
					
					<div class="thumb_img" align="center" style="background-image:url(<?=$thumbN?>)" des="<?=$photo.' '.stripslashes($description)?>">
                    <div class="<?=$MainPhotoClass?>" id="main_<?=$id?>" onClick="setMainPhoto(<?=$id?>)"></div></div>
					<? 
					$bgcol='abe656';
					$checked='checked';?>
					<div class="thumb_tools" style="background-color:#<?=$bgcol?>" id="tools_<?=$id?>" >
					<div>
					<input type="checkbox" id="ch_<?=$id?>"  value="<?=$id?>" <?=$checked?> onClick="addToGallery(<?=$id?>,2)" />
					</div><div>	
					<img src="../css/images/icons/Delete.png"  title="Delete" width="20" hspace="3" style="cursor:pointer" 
					onClick="del_photo(<?=$id?>,'info',2)"/>
					</div><div>
					<img src="../css/images/icons/Edit.png" title="Edit" width="20" hspace="3" style="cursor:pointer" 
					onClick="edit_photo(<?=$id?>,'<?=$description?>','<?=$thumb?>',2)"/>
					</div></div>
					</div>

				<? }
				$i++;
			} 
			?></div><? 
		}
	}
}
}//end for
?>