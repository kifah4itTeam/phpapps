<? include('gal_header.php');
if (!defined('GOOD')) die();
if(checkGallogin()){?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css"  href="../css/style.css" />
<link rel="stylesheet" type="text/css"  href="../css/gallary.css" />
</head>
<body style="margin:10px; overflow:hidden">
<div class="head"><div class="galTitle">New Gallery</div></div>
<br>
<div style="display:inline-block"><div class="steps_act_inact"> Step1: Add gallery Info. </div><div class="steps_inact_inact"> Step2: Add photos to gallery </div><div class="steps_inact_end">Step3: Save gallery</div></div>
<?php
$action = $_REQUEST['sub'];
if($action){
	$gallery_name_en = ($_REQUEST['gallery_name_en']);
	$description_en = ($_REQUEST['description_en']);
	$gallery_name_ar = ($_REQUEST['gallery_name_ar']);
	$description_ar = ($_REQUEST['description_ar']);
	$main_pho = ($_REQUEST['main_pho']);
	$photosArr = $_REQUEST['sel_pho']; 
	$photos = "";//implode(",",$photosArr);

	$SQL = "INSERT INTO galleries_galleries 
	(gallery_name_en , description_en , gallery_name_ar , description_ar) VALUES 
	('$gallery_name_en' , '$description_en', '$gallery_name_ar' , '$description_ar')";
	$result = mysql_query($SQL);
	if($result){
		$id = mysql_insert_id();
		$_SESSION['gid']=$id;
		echo '<script>window.location="'._PREF.'modules/includes/gallery/photosGallery.php";</script>';
	}
}// Insert record
?>
<form method='post' name='main' action='<?= $_SERVER['PHP_SELF']?>' enctype='multipart/form-data' onsubmit='return formValidator()'>
    <div style="height:340px; margin-top:20px">    
        <div style="float:left">
        <div>Gallery Name (EN): </div>
            <div><input type="text" name="gallery_name_en" class="inputText"/></div>
            <div style="margin-top:10px">Gallery Description (EN): </div>
            <div><textarea name="description_en" class="inputTextarea"></textarea></div>
        </div>
        
        <div  style="float:left; margin-left:25px;">
            <div>Gallery Name (AR):</div> 
            <div><input type="text" name="gallery_name_ar" class="inputText"/></div>
            <div style="margin-top:10px">Gallery Description (AR):</div>
            <div><textarea name="description_ar" class="inputTextarea"></textarea></div>
        </div>    
    </div>
<input type="submit" value="Next" name="sub" class="next" />
</form>
<script>
function formValidator(){
	if(this.main.gallery_name_en.value.length>0 ) return true;
	else{
		alert("Please enter the gallery name");
		 return false;
	}
}
</script>
</body>
</html>
<? }?>