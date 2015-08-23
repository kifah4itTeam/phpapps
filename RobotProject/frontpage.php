
<?php
include('includes/header.php');	
include('class/databasefunction.php');
$dbfunc= new databasefunctions();
?>
<div class="well">
<?php $dbfunc->pull_latest_post();?>
</div>
</body>
<?php
include('includes/footer.php');
?>