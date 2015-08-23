<?

//----------Define Direction -------------------------------------
$res=mysql_query("select dir from languages where lang='$pageLang'");
$rows=mysql_num_rows($res);
if($rows>0){define('DIR',mysql_result($res,0,'dir'));}
//----------Define lang keys -------------------------------------
$res=mysql_query("select `key`,`lang_".$pageLang."` as l from langs_keys ");
while($row=mysql_fetch_array($res)){define($row['key'],$row['l']);}


//----------Define Site Setting -------------------------------------
$res=mysql_query("select `define`,`value` from site_settings ");
while($row=mysql_fetch_array($res)){define($row['define'],$row['value']);}
?>