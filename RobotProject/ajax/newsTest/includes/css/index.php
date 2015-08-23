<?
session_start();
include_once("../config.php");
include_once("common/dbConnection.php");
include_once("login/login.inc.php");

$pageLang=$_SESSION['pageLang']="en";

include_once("includes/defines.php");
include_once("includes/dbUtils.php");
//$thisfile = getCurrentFormName();
//$thisUserId = getCurrentUserId();
function filter($str){
	$str=addslashes($str);
	return $str;
}
if($_SESSION["enterCMS"]=='go'){
	  @header("location:home/home.php");
	  exit();
}else{ 
	if(isset($_POST["submit"])){
		$_SESSION['cur_menu']='menuList_00';
		$_SESSION['activeGroup']=0;
		if((filter($_POST["username"])==admin_user)&&(filter($_POST["password"])==admin_pass)){	    
			$_SESSION["enterCMS"]="go";
			@header("location:home/home.php");
			exit();
		}else{
			@header("location:index.php");
		}
		
	}else{?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html lang="en-US">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Enter CMS</title>
    <style type="text/css">
    body,td,th {
        color: #000;
        font-family:calibri;
        font-size:14px;
    }
    body {
        background: url(includes/css/images/icons/adminVoila-bg.gif) top center repeat-x;
        padding-top: 498px;
        margin:0;
    }
    label{
        background:url(includes/css/images/icons/adminVoila-label.png) no-repeat;
        width:74px;
        height:27px;
        color:#FFF;
        line-height:27px;
        position:absolute;
        padding-left:2px;
    }
    input{
        border:#000 1px solid;
        color:#000;
        margin-left:70px;
        height:24px;
        padding-left:4px;
    }
    .bt{
        background:url(includes/css/images/icons/adminVoila-submit.png) no-repeat;
        width:74px;
        height:27px;
        color:#FFF;
        line-height:25px;
        border:0;
        margin-left:0;
    }
    .bt:hover{
        opacity:0.8;
        margin:0;
    }
    </style>
    </head>
    <body>
    <form action="index.php" method="post">
    <table width="960" height="78" border="0" align="center">
      <tr>
        <td width="230">
        <label for="username">Username</label><input type="text" name="username">   
         </td>
         <td width="230">
        <label for="username">Password </label><input type="password" name="password">  
        </td>
        <td width="560" align="left">
        <input type="submit" name="submit" value="LogIn" class="bt">
        </td>
        </tr>
    </table>
    </form>
        </body>
    </html><?
	}
}
?>
