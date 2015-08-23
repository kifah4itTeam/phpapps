<?require_once("../common/top-admin_ajax.php");
if (!defined('GOOD')) die();
$table = $_GET['t'];
$fname = $_GET['fn'];
$fvalue = ($_GET['fv']) ? "1" : "0";
$idname = $_GET['idn'];
$idvalue = $_GET['idv'];
if($_SESSION["enterCMS"] == 'topNews'){
	if($table=='tnews_comments'){
		if(getPubPer($news_user_id,$idvalue,'com')>0){
			if(mysql_query("UPDATE $table SET  $fname = '$fvalue' $Q WHERE $idname = '$idvalue'")){
				 echo $fvalue;
				 if($fvalue)$opr=6; else $opr=7;
				 logNews($news_user_id,$opr,0,$idvalue);
			}		
			else echo !$fvalue;
		}
	}else{
		if(getPubPer($news_user_id,$idvalue,'pub')>0){
			$user_publisher=lookupField('tnews_tnews','id','user_publisher' , $idvalue);		
			if($user_publisher==0)$Q=" ,user_publisher='$news_user_id' , publish_date ='$curr_date'";
			if(mysql_query("UPDATE $table SET  $fname = '$fvalue' $Q WHERE $idname = '$idvalue'")){
				 echo $fvalue;
				 if($fvalue)$opr=4; else $opr=5;
				 logNews($news_user_id,$opr,$idvalue);
			}		
			else echo !$fvalue;
		}
	}
}else{
	if(mysql_query("UPDATE $table SET  $fname = '$fvalue' WHERE $idname = '$idvalue'")) echo $fvalue;
	else echo !$fvalue;
}
?>