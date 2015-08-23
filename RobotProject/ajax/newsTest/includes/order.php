<? require_once("../common/top-admin_ajax.php");
if (!defined('GOOD')) die();
$table = $_POST['ot'];
$filed = $_POST['of'];
$order_id = $_POST['oi'];
$ids = $_POST['ids'];
$changes=explode("|",$ids);
$old_oders=array();
for($i=0;$i<count($changes)-1;$i++){
	$idsFT=explode(",",$changes[$i]);
	$old_ord=$idsFT[1];	
	$sql="select `$filed` from  $table where $order_id ='$old_ord' ";
	$res=mysql_query($sql);
	$old_oders[$i]=mysql_result($res,0,$filed);
}
for($i=0;$i<count($changes)-1;$i++){
	$idsFT=explode(",",$changes[$i]);
	$id_from=$idsFT[1];
	$id_to=$idsFT[0];
	echo $sql2="UPDATE $table SET  $filed = '".$old_oders[$i]."' WHERE $order_id = '$id_to'";
	if(mysql_query($sql2)){
		 echo 1;
	}else{
		echo 0;
	}
}
?>