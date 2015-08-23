<!--
<link href="../includes/upload_photo/css/default.css" rel="stylesheet" type="text/css" />
<link href="../includes/upload_photo/css/uploadify.css" rel="stylesheet" type="text/css" />
<script src="../includes/upload_photo/script/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../includes/upload_photo/script/uploadify.css"></script>
<script type="text/javascript">
$(document).ready(function() {
   	$("#uploadify").uploadify({
		'formData'     : {
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
		},
		'swf'      : '../includes/upload_photo/scripts/uploadify.swf',
		'uploader' : '../includes/upload_photo/scripts/uploadify.php',
		'onUploadSuccess' : function(file, data, response) {
            alert('The file ' + file.name + ' was  with a response of ' + response + ':' + data);
        }
	});
});
</script>


-->

</script>
<script src="../includes/upload_photo/script/jquery.uploadify.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../includes/upload_photo/script/uploadify.css">
<style type="text/css">
body {
	font: 13px Arial, Helvetica, Sans-serif;
}
</style>



<div style="padding:10px;">

<form>

    <div id="queue"></div>
    <input id="file_upload" name="file_upload" type="file" multiple="true">
</form>
</div>
<script type="text/javascript">
	<?php $timestamp = time();?>
	$(function() {
		$('#file_upload').uploadify({
			'formData'     : {
				'timestamp' : '<?php echo $timestamp;?>',
				'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
			},
			'swf'      : '../includes/upload_photo/script/uploadify.swf',
			'uploader' : '../includes/upload_photo/script/uploadify.php',
			'onUploadSuccess' : function(file, data, response) {
				//vPhotos//alert('The file ' + file.name + 'response of ' + response + ':' + data);				
				showphoto(data);
			},
			'onQueueComplete': function(){				
				//$("#upload_div").hide('slow');
				
			}	

		});
	});
</script>
