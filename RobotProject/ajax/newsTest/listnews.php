<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<?php require_once("dbconnection/dbconnection.php");?>
<body>
<?php 

$sql_insert="INSERT INTO employee (emp_name, emp_salary) VALUES (?, ?)";

$stmt=mysqli_prepare($con,$sql_insert);//sql statement is created and  and sent to databse, Certain values are left unspecified,called parameters

//The database parses, compiles, and performs query optimization on the SQL statement template, and stores the result without executing it
//Execute: At a later time, the application binds the values to the parameters, and the database executes the statement. The application may execute the statement as many times as it wants with different values
mysqli_stmt_bind_param($stmt,"ss",$ename,$esalary);
//$tt='kjljlkj';
//$tt=trim($tt);
//$tt=empty($tt);
//echo "TT ".$tt;
//if(isset($tt))
//echo "isset";
//else 
//echo "not set";
$esalary=7777;

try{  
    if (!mysqli_stmt_execute($stmt))
     switch ( mysqli_errno($con)){
	    case 1062: throw new Exception("قيمة مكررة", mysqli_errno($con));
		break;
		case 1054: throw new Exception("حقل غير معرف تتم الاضافة فيه",mysqli_errno($con));
		break;
		case 1048: throw new Exception("الرجاء تعبئة جميع الحقول",mysqli_errno($con));
		break;
		default:  throw new Exception("General Error", mysqli_errno($con));
		   }   
		  
		   }
		
catch(Exception $e)
{   
 echo "<script>alert('".$e->getMessage()."  ".$e->getCode()."')</script>";
   
   
    
	}

 ?>
</body>
</html>