<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css"  href="includes/css/style.css" />
<link type="text/css" rel="stylesheet" href="includes/css/smoothness/jquery-ui.css"/>	
<link rel="stylesheet" type="text/css"  href="includes/css/tipTip.css" />

<script type="text/javascript" src="includes/js/jquery.min.js"></script>
<script type="text/javascript" src="includes/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="includes/js/jquery.validate.pack.js" ></script>
<script type="text/javascript" src="includes/js/jquery.form.js" ></script>
<script type="text/javascript" src="includes/js/jquery.blockUI.js" ></script>
<script type="text/javascript" src="includes/js/jconfirmaction.jquery.js" ></script>
<script type="text/javascript" src="includes/js/alert/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="includes/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="includes/tinymce/jscripts/tiny_mce/plugins/imagemanager/js/mcimagemanager.js" ></script>
<script type="text/javascript" src="includes/js/jquery.tipTip.js"></script>
<script type="text/javascript" src="includes/js/config.js" ></script>
<!--Help Module -->
<script type="text/javascript" src="includes/js/jquery.tools.min.js"></script>
<script type="text/javascript" src="UIJQUERY.js"></script>
</head>
<?php require_once("dbconnection/dbconnection.php");?>
<body>
<?php 
     echo '<script>correctMessageWithoutBack("Record_deleted");</script>'; 
	  if($_POST)
	  {   
	   
	    $array = $_POST['rid'];
	if(isset($_POST['rid'])){
		$ids = implode(",", $array);
		   var_dump($ids);
		    } 
			}
	  
	  
 $sql="select * from employee";    
   	
      $result=mysqli_query($con,$sql);
	  if(mysqli_num_rows($result)>0)
	  { echo "<form action='#' method='post'>";
	  while($row=mysqli_fetch_assoc($result))
	  {   
	   echo "id: " . $row["emp_id"]. " - Name: " . $row["emp_name"]. " " . $row["emp_salary"];
	   echo '<span class="check"><input name="rid[]" type="checkbox" value="'.$row['emp_id'].'" /></span><br/>';
	   echo "<span dir='ltr' style='alignment-adjust:left'>";
	  //echo "id: " . $row["id"]. " - Name: " ."<span dir='ltr' style='alignment-adjust:left'>". $row["firstname"]. "</span> " . $row["surname"]. "<br>";
	   }
	   echo "<input type='submit' value='delete'>";
	   echo "</form>";
	  }
	  
       
	   
	    
		function JsCheck(){ 
		echo "<script>
		$(document).ready(function(){
			$('.selectAll').click(function(){
				$('.check :input').each(function(){
					$(this).attr('checked','checked');
				});
				
			});
			$('.disselectAll').click(function(){
				$('.check input').each(function(){
					$(this).attr('checked','');
				});
				
			});
		});	
		</script>
		<div style='cursor:pointer; float:right;'><span class='disselectAll'>DeselectAll</span> &nbsp; | &nbsp;	<span class='selectAll'>SelectAll</span>  </div>";}
		JsCheck();
		?>
	<a href="javascript:void(0)" onclick="open_window('http://www.google.com',400,400)">oooooooo</a>
		    
		<a href="javascript: open_window('file.html',900,600);">Open New Window</a>

<!-- OR -->

<a href="javascript: open_window('http://html-tuts.com',900,600);">Open New Window</a>

<!-- OR -->

<a onClick="open_window('file.html',900,600);">Open New Window</a>		  
		

</body>
</html>