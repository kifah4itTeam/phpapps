<?
session_start();
define('GOOD', True);
header("cache-control: no-cache"); 
require_once("../../../config.php");
require_once("../../common/dbConnection.php"); 
require_once("../dbUtils.php");
require_once("dbUtilsGallary.php");
if(!chechUserPermissions('listGalleries.php')){
	session_destroy();
	@header("location:/");
	exit;
}
?>