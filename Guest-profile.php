<?php
/* Yiming ZHANG ITMO 544-01 MP-1
 * Added on Nov 4, 2015
 * profile.php
 * TODO: session control blocks are currently missing
 *
 */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<style>
.lay_content {
    background-image: url("bg.png");
    background-size: 1200px 571px;
    background-color: black;
 	font-style: oblique;
    padding: 187px;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 10px;
}
.left_side {
	margin-left: 10px;
	width: 98%;
    border:1px solid #00FF00;
}


</style>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
<title>Profile</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css" title="Style">

<div style="text-align:left;">
<a href="index.php" class="btn btn-primary btn-lg active" role="button"> <h2> &nbsp;&nbsp; Index </h2> </a>
</div>
</div>
<div class = "lay_content" align = "center">
<font color = "#FFFFFF"><h1>Yiming Zhang</h1></font>
</head>
</div>
<body>
<link rel="stylesheet" type="text/css" href="stylesheet.css" title="Style">

<div align = "center">
	<p><font face= "verdana" size = "2"> &nbsp; ITMO 544-01 MP-2 Photo Gallery App </font></p>
	<p><font face= "verdana" size = "2"> &nbsp; Fall 2015 Oct-Nov </font></p>
	<p><font face= "verdana" size = "2"> &nbsp; UID: A20296055</font></p>
	<br>
	<p>
	<?php
	date_default_timezone_set('America/Chicago');
			$myDate = date('j M Y - h:i:s A');
	
			print "CURRENT TIME: ". $myDate. " | EpochSeconds";
	?></p>
</div>

</body>
</html>