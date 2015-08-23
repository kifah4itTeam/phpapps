<?php
$username=addslashes(strip_tags($_POST['username']));
$userpass=addslashes(strip_tags($_POST['userpass']));
if ($username && $userpass){
	$finduser=mysqli_query("SELECT * FROM member WHERE username='".$username."' AND userpass='".$userpass."'") or die("mysqli_error");
	if(mysqli_num_rows($finduser)!=0){
		while($row=mysqli_fetch_assoc($finduser)){
			$uname=stripslashes($row['username']);
				$upass=stripslashes($row['userpass']);
			
			}
			if($username==$uname AND $userpass==$upass){
				$_SESSION['sessionname']=$uname;
				$_SESSION['sessionpass']=$upass;
				echo "<h4>WELCOME ".$uname." to your cpanel</h4>
				
				<a href='cpanel.php'>GO to Admin page</a>";
				}else{
					die("<h2>username or password is not found</h2>");
					}
		}else{
			die("<h5>Not Found user</h5>
			<a href='login.php'>GO to login</a>");
			}
	
	} 


?>