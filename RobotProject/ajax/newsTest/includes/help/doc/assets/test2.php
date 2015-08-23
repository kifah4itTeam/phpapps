<? include_once("../../../../includes/dbUtils.php"); ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"
"http://www.w3.org/TR/REC-html40/loose.dtd">
<html>
        <head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        </head>
        <body>
        		<p>Page generated on:</p>
                <p><?=date('r');?></p>
				FileName: <?echo  getFileName();?>
				M= <?=$_REQUEST['m'];?>
        </body>
</html>