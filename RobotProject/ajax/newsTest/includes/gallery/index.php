<?session_start();
if (!defined('GOOD')) die();
$gal = $_SESSION['gal'];
$folder =$_REQUEST['folder'];
?>
<link href="css/default.css" rel="stylesheet" type="text/css" />
<link href="css/uploadify.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/swfobject.js"></script>
<script type="text/javascript" src="scripts/jquery.uploadify.v2.1.0.min.js"></script>
<?//$_REQUEST['gal'] = 1;?>
<script type="text/javascript">
$(document).ready(function() {
	var uploadFolder=$('#correntPath').val();
	$("#uploadify").uploadify({
		'uploader'       : 'scripts/uploadify.swf',
		'script'         : 'scripts/uploadify.php?upfolder='+uploadFolder,
		'cancelImg'      : 'cancel.png',
		'folder'         : '../../../uploads/'+uploadFolder,
		'queueID'        : 'fileQueue',
		'auto'           : true,
		'multi'          : true,
		'onAllComplete'	 : function(){
			//location.reload();
			loadImages();
			$("#upload_div").hide('show');
			stopSelFolder=0;
		}
	});
});
</script>
<div style="padding:10px;">
<div class="closee" onClick="$('#upload_div').hide('slow');stopSelFolder=0"></div>
<div style="float:left">Select multiple file to upload by clicking on the Browse button</div>
<input type="file" name="uploadify" id="uploadify" />
<div id="fileQueue"></div>
<p><a href="javascript:jQuery('#uploadify').uploadifyClearQueue()">Cancel All Uploads</a></p>
</div>
