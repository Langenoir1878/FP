<?php
/*
 * This page will provide instruction for testing the application
 * Login page will navigate to setup and sutomatically prepare the database
 * added session control on Nov 19, 2015
 * 16:49:51 pm @ PS 3001- 718
 */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">

<style>
.left_align{
	margin-left: 47px;
	width: 98%;
   // border:1px solid grey;
}
.bgImage{
	position:fixed;
	margin-top:30px;
	margin-left:417px;
	margin-right: 70px;
	margin-bottom: 30px;
}
</style>



<head>
	<meta charset="UTF-8">
	<title>Login</title>
<link rel="stylesheet" type="text/css" href="stylesheet.css" title="Style">

</head>
<body background = "black">

<div class = "bgImage">
 <img src="wall2.png" height="600" width="900">
</div>

<div class="left_align">
	<br>
		<form action="Guest-index.php" method="POST">
			<h1><font color = "white"> Welcome! Guest!</font></h1>
			<br>
			
			<font color = "white">
				<h1>Testing Instruction:</h1><br>
				<p>This is the guest page</p>
				<p>The guest system won't automatically subscribe to this application</p><br>
				<p>This page will automatically prepare the database for user</p>
				<p>Please <a href= "login.php"> Log In </a> & subscribe! </p><br>
				<p><font color = "grey"> ITMP 544 MP-2 15Fall </font></p>
				<br>
				<br><br><br>
			</font>
			
			<input type="submit" value = "Continue as Guest">
			<br>
		</form>
</div>

</body>
</html>