<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){ 
if(isset($_SESSION['gid'])){
	$gid=$_SESSION['gid'];
	$sql = "SELECT * FROM galleries_galleries  where id=$gid limit 0,1";
	$result = mysql_query($sql);
	$numberOfRows = mysql_numrows($result);
	if ($numberOfRows>0) {
		$gallery_name= mysql_result($result,0,"gallery_name_en");
		$description= mysql_result($result,0,"description_en");
		$main_photo= mysql_result($result,0,"main_photo");
		$photos= mysql_result($result,0,"photos");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" type="text/css"  href="../css/style.css" />
<link rel="stylesheet" type="text/css"  href="../css/gallary.css" />
<link type="text/css" rel="stylesheet" href="../css/smoothness/jquery-ui.css"/>
	
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/jquery.form.js" ></script>
<script type="text/javascript" src="gallery.js" ></script>
<script>
$(document).ready(function(){ 	
	loadImages(2);
});
</script>
</head>
<body style="margin:10px; overflow:hidden">
<div id="upload_div" ></div>
<div id="loading">LOADING ...</div>
<div class="head"><div class="galTitle">Save Gallery</div></div>
<br>
<div style="display:inline-block"><div class="steps_inact_inact"> Step1: Add gallery Info. </div><div class="steps_inact_act"> Step2: Add photos to gallery </div><div class="steps_act_end">Step3: Save gallery</div></div>

<div style="margin-top:10px; margin-bottom:10px; float:left">
Please select the main photo from below by click on Flag :<img src="<?=_PREFICO?>mpa1.png"/></div>
<div style="margin-top:10px; margin-left:150px; float:right">
Drag to reorder:<img src="<?=_PREFICO?>Drag.png"/></div>
<div id="gallery_admin2"></div>

<div style="margin-top:10px;">
<div class="back" onClick="document.location='photosGallery.php'">Back</div>
<? if(isset($_SESSION['entergid']) && $_SESSION['entergid']==$gid){
	?><div class="next" style="margin-left:10px;" onClick="window.close()">Finsh</div><?
	}else{?>
<div class="next" style="margin-left:10px;" onClick="opener.document.location.reload(true);window.close()">Finsh</div>
<? }?>
</div>
</body>
</html>
<? } }}?>