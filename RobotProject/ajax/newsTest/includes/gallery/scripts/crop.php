<?php
header("cache-control: no-cache"); 
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$targ_w = $targ_h = 90;
	$jpeg_quality = 100;

	$src = "../".$_POST['photo'];
	$thumb = "../../../uploads/".$_POST['thumb'];
	echo implode(",",$_POST);
	$img_r = imagecreatefromjpeg($src);
	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
	$targ_w,$targ_h,$_POST['w'],$_POST['h']);
			
	/*header('Content-type: image/jpeg');*/
	imagejpeg($dst_r,$thumb,$jpeg_quality);
	
	echo 	"<img src=\"$thumb\">";
	echo 	$thumb = "../../../uploads/".$_POST['thumb'];


	exit;
}

// If not a POST request, display page below:

?>