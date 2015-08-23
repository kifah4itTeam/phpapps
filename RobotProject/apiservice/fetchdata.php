<?php
header('Access-Control-Allow-Origin: *');  
$sqlconn=mysqli_connect('localhost','root','','robotsite_db') or die(mysqli_error());
$dataquery=mysqli_query($sqlconn,"SELECT * FROM post");
while($r=mysqli_fetch_object($dataquery)){
	$arr=array();
	array_push($arr,array("post_id" => $r->post_id,"post_title" => $r->post_title ,"post_text" => $r->post_text));
	}
	print_r(json_encode($arr));
?>