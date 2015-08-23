<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){
?>
<link rel="stylesheet" type="text/css"  href="../css/tipTip.css" />
<script type="text/javascript" src="../js/jquery.tipTip.js"></script>
<script>
	$("img").tipTip({defaultPosition:"top"});
	$(".thumb_img").tipTip({defaultPosition:"top",attribute:"des"});
	
function del_conform(id){
	dleID=id;
	$('#delDiv').html('Loading....');
	$('#delDiv').dialog("open");
	$.post("del.php",{id:id,opr:'info'}, function(data){
		$('#delDiv').html(data);
	});
}
function del_file(){
	$.post("del.php",{id:dleID,opr:'del'}, function(data){
		$('#delDiv').dialog("close");
		$('#box'+dleID).hide('slow');
		dleID=0;
	});
}
function edit_conform(id){	
	$('#editDiv').html('Loading....');
	$('#editDiv').dialog("open");
	$.post("edit.php",{id:id}, function(data){
		$('#editDiv').html(data);
	});
}
function edit_file(){
	$('#editDiv').html('Loading....');
	var des=$('#des2').val();
	$.post("edit.php",{des:des}, function(data){
		$('#editDiv').html(data);
		if(data!=0){
			$('#editDiv').dialog("close");
			$('#box'+data).hide('normal');
		}
	});
}
var dleID=0;
<? if(isset($_REQUEST['f'])){ ?>
$(document).ready(function(){
	$('#delDiv').dialog({
		autoOpen: false,
		width:420,
		title:'<?=Delete?>',
		modal: true, 
		buttons: {
			"<?=Delete?>": function() {del_file()} ,
			"<?=Cancel?>": function() {$(this).dialog("close");} 
		}
	});
	
	$('#editDiv').dialog({
	autoOpen: false,
	width:420,
	title:'<?=Edit?>',
	modal: true, 
	buttons: {
		"<?=Save?>": function() {edit_file()} ,
		"<?=Cancel?>": function() {$(this).dialog("close");} 
	}
});
	
});
<? }?>
</script>
<input name="des2" type="hidden" id="des2" value="d" size="100">
<?php
// Images Extentions
$img_arr = array("jpg","jpeg","gif","png","JPG","JPEG","GIF","PNG");
$gid=$_SESSION['gid'];
$ph=lookupField('galleries_galleries','id','photos',$gid);
$photos=explode(',',$ph);

$folder = $_REQUEST['folder'];
$sql = "SELECT * FROM galleries_photos where folder='$folder' $sort  order by id DESC";	
$result = MYSQL_QUERY($sql);
$numberOfRows = MYSQL_NUMROWS($result);
$i=0;
while ($i<$numberOfRows){
	$id= MYSQL_RESULT($result,$i,"id");
	$photo= MYSQL_RESULT($result,$i,"photo");
	$folder= MYSQL_RESULT($result,$i,"folder");
	$description= MYSQL_RESULT($result,$i,"description");
	$thumb= MYSQL_RESULT($result,$i,"thumb");
	$file_path='../../../uploads/'.$folder.$photo;
	if(file_exists($file_path)){
		if($thumb!='video'){
			$thumbN=resizeToFile('../../../uploads/'.$folder.$photo, 90, 90,'../../../uploads/'.$thumb); 
		}else{
			$thumbN=_PREFICO.'video.png';
		}?>		
        <div class="thumb" align="center" id="box<?=$id?>"  >        
            
        <div class="thumb_img" align="center" style="background-image:url(<?=$thumbN?>)" des="<?=$photo.' '.stripslashes($description)?>"></div>
        
        <? 
		$bgcol='ccc';
		$checked='';
		if(in_array($id,$photos)){
			$checked= " checked ";
			$bgcol='abe656';
		}?>
        
        <div class="thumb_tools" style="background-color:#<?=$bgcol?>" id="tools_<?=$id?>" >
        <div>
        <input type="checkbox" id="ch_<?=$id?>"  value="<?=$id?>" <?=$checked?> onClick="addToGallery(<?=$id?>,1)" />
        </div><div>	
        <img src="../css/images/icons/Delete.png" title="Delete" width="20" hspace="3" style="cursor:pointer" 
        onClick="del_photo(<?=$id?>,'info',1)"/>
        </div><div>
        <img src="../css/images/icons/Edit.png" title="Edit" width="20" hspace="3" style="cursor:pointer" 
        onClick="edit_photo(<?=$id?>,'<?=$description?>','<?=$thumb?>',1)"/>
        </div></div>
        </div><?		
	}
	$i++;
}//end for
}?>
