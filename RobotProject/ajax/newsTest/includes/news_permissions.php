<? require_once("../common/top-admin.php");
if (!defined('GOOD')) die();
$u = $_GET['u'];//user
$c = $_GET['c'];//category
$p = $_GET['p'];//permissins
$v = $_GET['v'];//value
if(!$v)$v=0;
$permissins=array('','add','edit','del','pub','com');
$per=$permissins[$p];

$sql="select id from tnews_permissions where user='$u' and cat='$c' limit 1";
$res=mysql_query($sql);
$rows=mysql_num_rows($res);
if($rows>0){
	$rec_id=mysql_result($res,0,'id');
	if(mysql_query("UPDATE tnews_permissions SET  `$per` = '$v' WHERE id='$rec_id'")) echo $v;	
}else{	
	if(mysql_query("insert into tnews_permissions (`user`,`cat`,`$per`)values('$u','$c','$v')")) echo $v;	
}
mysql_query("delete from tnews_permissions where `add`=0 and `edit`=0 and `del`=0 and `pub`=0 and `com`=0 ");


?>