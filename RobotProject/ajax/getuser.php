<!DOCTYPE html>
<html>
<head>
<style>
table {
    width: 50%;
    border-collapse:collapse;
}

table, td, th {
    border: 1px solid black;
    padding: 5px;
}

th {text-align: left; background-color:#CCC;}
</style>
</head>
<body>

<?php
require_once("dbconnection.php");
$q = intval($_GET['q']);

$sql="select * from employee where emp_id=$q"; 
$result=mysqli_query($con,$sql);   
   	echo "<table>
<tr>
<th>id</th>
<th>Employee Name</th>
<th>Employee Salary</th>

</tr>";
	
      while($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['emp_id'] . "</td>";
    echo "<td>" . $row['emp_name'] . "</td>";
    echo "<td>" . $row['emp_salary'] . "</td>";
    
    echo "</tr>";
}
echo "</table>";
mysqli_close($con);






?>
</body>
</html>
