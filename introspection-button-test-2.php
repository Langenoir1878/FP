<?php
// test page-2, simulated flag to trigger upload button on page-1 to be disabled
?>
<DOCTYPE html>
<html>
<style>
    .hidden-div {
        display:none
    }
    </style>
<head></head>


<body>
<p>testing if this can disable the upload button in page-1</p>
<form enctype="multipart/form-data" action="#" method="POST">
	<label><input type="checkbox" id="cbox1">Read-only Mode Trigger</label><br>
	<?php 
	//validate if cbox1 isset
	if (isset(cbox1)){
		?>
		<div class="hidden_div">
	<input type="submit" value="test">
	<div>

	<?php
	} else{
	?>
	
	<input type="submit" value="test" >



	<?php	
	}

	?>
	
	</form>

</body>
</html>
