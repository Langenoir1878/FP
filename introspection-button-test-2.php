<?php
// test page-2, simulated flag to trigger upload button on page-1 to be disabled
?>
<DOCTYPE html>
<html>
<head></head>
<body>
<p>testing if this can disable the upload button in page-1</p>
<form enctype="multipart/form-data" action="introspection-button-test-1.php" method="POST">
	<label><input type="checkbox" id="cbox1">Read-only Mode Trigger</label><br>
	<?php 
	//validate if cbox1 isset
	if (isset(cbox1)){
		?>
	<input type="submit" value="Enter">
	<?php
	}
	?>
	
	</form>

</body>
</html>
