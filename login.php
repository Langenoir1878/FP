<?php
/*
 * This page will provide instruction for testing the application
 * Login page will navigate to setup and sutomatically prepare the database
 * added session control on Nov 19, 2015
 * 16:49:51 pm @ PS 3001- 718
 */
//namespace langenoir1878;

session_start();
//require_once 'credentials.php';
if (isset($_GET['logout'])) {
	session_destroy();
}
if(isset($_SESSION['user']))
{
    header("Location: index.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Shorten Request Variables if they are set
	$username = isset($_POST['username']) ? trim($_POST['username']) : '';
	$password = isset($_POST['password']) ? trim($_POST['password']) : '';
	$valid_user = 'Yiming';
	$valid_hash = '$2y$10$XwCIua.7devIMB8M2nWb1OGG19co13hZZUDofh4zcs6aMHb5MC.mK';//hashed password
	if ($username == $valid_user && password_verify($password, $valid_hash)) {
		$_SESSION['user'] = $valid_user;
		header("Location: index.php");
    	exit;
	}
}
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
		<form action="#" method="POST">
			<h1><font color = "white"> Please sign in: </font></h1>
			<br>
			<label><font color = "white">Username: &nbsp;</font><input type="text" name="username" style="color: white; background-color: transparent;"></label><br>
			<br>
			<label><font color="white">Password: &nbsp;</font><input type="password" name="password" style="color: white; background-color: transparent;"></label><br>
			<br><br><br>
			<font color = "white">
				<h1>Testing Instruction:</h1><br>
				<p>Username: Yiming</p>
				<p>Password: ZHANG (case sensitive)</p><br>
				<p>This page will automatically prepare the database for user after login.</p>
				<p>Please fill in the above fields for testing.</p><br>
				<p><font color = "grey"> ITMP 544 MP-2 15Fall </font></p>
				<br>
				<br><br><br>
			</font>
			
			<input type="submit" value = "Login & Subscribe!"> 
			&nbsp;&nbsp;&nbsp; 
			<font color = "blue">
			<a href = "Guest-login.php"> Continue as a guest </a>
			</font>
			<br>
		</form>
</div>

</body>
</html>