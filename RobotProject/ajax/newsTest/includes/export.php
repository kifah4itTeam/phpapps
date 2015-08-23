<?
if (!defined('GOOD')) die();
/**
 * To use this script you should pass the following variables in request :
 * 		1- $_POST['table'] : table from which the data will be exported
 *		2- $_POST['fields'] : (Optional) A comma separated list of fields to export from the specified table
 *		3- $_POST['condition'] : (Optional) Free condition according to which data will be retrieved
 * 		4- $_POST['orderBy'] : (Optional) A comma separated list of fields to order exported data according to it
 *		5- $_POST['outputFilename'] : (Optional) The name of the excel file to be exported 
 * Or just pass the SQL variable $_POST['SQL'] 
 */
include_once("../common/top.php");

//If SQL statement is provided use it directly
if(isset($_POST['SQL']))
{
	if($_POST['SQL']!=""){
		if(!isset($_POST['outputFilename'])){
			$outputFilename = "default.xls";
		}
		else{
			if($_POST['outputFilename']==""){
				$outputFilename = "default.xls";
			}
			else{
				$outputFilename = $_POST['outputFilename'];
			}
		}
		$sql=$_POST['SQL'];
		
		//Export data . . 
		exportSqlToExcel($sql,$outputFilename);
		
		//End script . . 
		exit();
	}
}

//Check values and set defaults . . 
if(!isset($_POST['table']))
{
	echo "Nothing to export!";
	exit();
}
else
{
	if($_POST['table']==""){
		echo "Nothing to export!";
		exit();
	}
	else {
		$table = $_POST['table'];
	}
}

if(!isset($_POST['fields'])){
	$fields = "*";
}
else {
	if($_POST['fields']==""){
		$fields = "*";
	}
	else {
		$fields = $_POST['fields'];
	}
}

if(!isset($_POST['condition'])){
	$condition = "";
}
else{
	$condition = $_POST['condition'];
}

if(!isset($_POST['orderBy'])){
	$orderBy = "";
}
else{
	$orderBy = $_POST['orderBy'];
}

if(!isset($_POST['outputFilename'])){
	$outputFilename = "default.xls";
}
else{
	if($_POST['outputFilename']==""){
		$outputFilename = "default.xls";
	}
	else{
		$outputFilename = $_POST['outputFilename'];
	}
}

//Export data . . 
exportDataToExcel($table,$fields,$condition,$orderBy,$outputFilename);

//End script . . 
exit();
?>