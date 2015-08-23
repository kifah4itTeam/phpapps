<?php  //MySQL is a database system used on the web  
define("SERVERNAME","localhost");
define("USERNAME","root");
define("PASSWORD","");
define("DATABASE","news");

// Create connection
//$conn = mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);
//set default character set to be used from and to databse server
//mysql_set_charset($conn,"utf8");
// Check connection
try{
$con = @mysqli_connect(SERVERNAME,USERNAME,PASSWORD,DATABASE);
//echo "con".mysqli_connect_errno($con);
// Check connection
if (mysqli_connect_errno())
  {
  throw new Exception("Can not connection",mysqli_connect_errno($con));
  } 
  }   
  
  catch(Exception $e)
  {   echo $e->getMessage();
      echo $e->getCode();
	 var_dump($e->getTrace());
   
    }
?>

			
