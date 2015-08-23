<? session_start();
		// Some settings
	include('../../../../../../../config.php');
	include_once("../../../../../../../modules/common/dbConnection.php");
	$res=mysql_query("select `define`,`value` from site_settings ");
while($row=mysql_fetch_array($res)){define($row['define'],$row['value']);}
	$msg = "";
	$username= files_user;
	$password= files_pass;
	if (!$password)
		$msg = 'You must set a password in the file "login_session_auth.php" inorder to login using this page or reconfigure it the authenticator config options to fit your needs. Consult the <a href="http://wiki.moxiecode.com/index.php/Main_Page" target="_blank">Wiki</a> for more details.';
		
	if (isset($_POST['submit_button'])) {
		function lookupField($table, $id_field, $lookup_field, $id_value) {
			$sql_lookup = "SELECT `$lookup_field` from `$table` where `$id_field` = '$id_value'";
			$result_lookup = MYSQL_QUERY($sql_lookup);			
			$rows_lookup = MYSQL_NUM_ROWS($result_lookup);
			if ($rows_lookup == null || $rows_lookup = 0) {
				return 0;
			} else {
				$filds =explode(",",$lookup_field);
				$ind = 0; $value="";
				while($ind < count($filds)){
					$value.= stripslashes(MYSQL_RESULT($result_lookup,0,$filds[$ind])." ");
					$ind++;
				}
				return trim($value);
			}
		}
		// If password match, then set login
		if ($_POST['login'] == $username && $_POST['password'] == $password && $password) {
			// Set session
			
			$_SESSION['isLoggedIn'] = true;
			$_SESSION['user'] = $_POST['login'];

			// Override any config option
			//$_SESSION['imagemanager.filesystem.rootpath'] = 'some path';
			//$_SESSION['filemanager.filesystem.rootpath'] = 'some path';

			// Redirect
			echo "<script>document.location='".$_POST['return_url']."'</script>";
			//header("location: " . $_POST['return_url']);
			die;
		} else
			$msg = "Wrong username/password.";
	}
?>

<html>
<head>
<title>Sample login page</title>
<style>
body { font-family: Arial, Verdana; font-size: 11px; }
fieldset { display: block; width: 170px; }
legend { font-weight: bold; }
label { display: block; }
div { margin-bottom: 10px; }
div.last { margin: 0; }
div.container { position: absolute; top: 50%; left: 50%; margin: -100px 0 0 -85px; }
h1 { font-size: 14px; }
.button { border: 1px solid gray; font-family: Arial, Verdana; font-size: 11px; }
.error { color: red; margin: 0; margin-top: 10px; }
</style>
</head>
<body>

<div class="container">
	<form action="login_session_auth.php" method="post">
		<input type="hidden" name="return_url" value="<?=isset($_REQUEST['return_url']) ? htmlentities($_REQUEST['return_url']) : ""; ?>" />

		<fieldset>
			<legend>Example login</legend>

			<div>
				<label>Username:</label>
				<input type="text" name="login" class="text" value="<?=isset($_POST['login']) ? htmlentities($_POST['login']) : ""; ?>" />
			</div>

			<div>
				<label>Password:</label>
				<input type="password" name="password" class="text" value="<?=isset($_POST['password']) ? htmlentities($_POST['password']) : ""; ?>" />
			</div>

			<div class="last">
				<input type="submit" name="submit_button" value="Login" class="button" />
			</div>

<? if ($msg) { ?>
			<div class="error">
				<?=$msg; ?>
			</div>
<? } ?>
		</fieldset>
	</form>
</div>

</body>
</html>
