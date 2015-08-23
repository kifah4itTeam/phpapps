<?
$old_error_handler = set_error_handler("myErrorHandler");
$err_msg = "";
addToLogFile($msg);
function myErrorHandler($errno, $errstr, $errfile, $errline){
	if (!(error_reporting() & $errno)) {
		// This error code is not included in error_reporting
		return;
	}
	$msg = "Err#$errno, Line($errline) at file: $errfile::$errstr";
	addToLogFile($msg);
	switch ($errno) {
		case E_USER_ERROR:
		echo $err_msg;
		exit(1);
		break;
		case E_USER_WARNING:
		echo $err_msg;
		break;
		case E_USER_NOTICE:
		echo $err_msg;
		break;
		default:
		echo $err_msg;
	}
	
	return true;
}
function addToLogFile($msg=""){
	mysql_query("INSERT INTO log SET `user`='".$_SESSION[".SESSION_USER_NAME."] ."',`ip`='".VisitorIP()."',`datetime_stamp`='".date('Y-m-d H:i:s')."',`page`='".$_SERVER['REQUEST_URI']."', msg='$msg'");
}
function VisitorIP(){
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
	$TheIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else $TheIp=$_SERVER['REMOTE_ADDR'];
	return trim($TheIp);
}
?>