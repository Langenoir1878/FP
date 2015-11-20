<?php
/**
 * User: ln1878
 * Date: 10/25/2015
 * Time: 16:49:14 pm
 * @ Galvin Library 2 FL
 *
 * updated on Nov 19, 2015
 * added session control: authentication login page
 * 
 */

//namespace langenoir1878;
session_start();

if(!isset($_SESSION['user']))
{
    header("Location: login.php");
    exit;
}
?>

<?php
// setting up the db table
require 'vendor/autoload.php';
use Aws\Rds\RdsClient;

$client = RdsClient::factory(array(
  'version'=>'latest',
  'region'=>'us-east-1'
));


# updated Nov 13, for testing $client
$result = $client->describeDBInstances(array(
	'DBInstanceIdentifier'=>'simmon-the-cat-db'
	));

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
#print "============\n". $endpoint . "================\n";

#echo "begin database";
$link = new mysqli($endpoint,"LN1878","hesaysmeow","simmoncatdb") or die("Error in line 89 in setup.php" . mysqli_error($link)); 

/* check connection */

if (mysqli_connect_errno()) {

    printf("Connect failed: %s\n", mysqli_connect_error());

    exit();
  }

#echo "Here is the result: " . $link;
$sqlSTETEMENTstr='CREATE TABLE CAT_TABLE 
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
USERNAME VARCHAR(32),
EMAIL VARCHAR(100),
PHONE VARCHAR(30),
RAWS3URL VARCHAR(500),
FINISHEDS3URL VARCHAR(500),
IMGNAME VARCHAR(100),
STATE TINYINT(3) CHECK(STATE IN (0,1,2)),
TIMESTR VARCHAR(50) 
)';

$debug = $link->query($sqlSTETEMENTstr);

if ($debug){
  $successMsg = "CAT_TABLE created";
} 
else { $failureMsg = "Table exists"; }

$link->close();

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
	<title>Index</title>
</head>

<body>

	
	<div style="text-align:right">
	<font color = "white"><?php echo 'Hi, '. $_SESSION['user'] . ' !'; ?></font>
	&nbsp;&nbsp;&nbsp;
	<a href="login.php?logout=yes">logout</a>
	&nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	
<link rel="stylesheet" type="text/css" href="stylesheet.css" title="Style">
    <div class = "lay_content" align = "center" >
        <font color = "#FFFFFF"><h1> ITMO 544 MP-1 Y.Z. </h1></font>
        <p><?php echo $successMsg . $failureMsg ;?></p>
    </div>
    <div class = "left_side">

<form enctype="multipart/form-data" action="result.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />
    <!-- Name of input element determines name in $_FILES array -->
    <br><br><font color = "white">
    Send this file: </font><input name="userfile" type="file" /><br />
    <br><br><font color = "white">
Enter Email of user: </font><input type="email" name="useremail"><br />
	<br><br><font color = "white">
Enter Phone of user: </font><input type="phone" name="phone">


<input type="submit" value="Send File" />
<br><br><br>
</form>
<br>

<form enctype="multipart/form-data" action="gallery.php" method="POST">
  
  <br><font color = "white">  
Enter Email of user for gallery to browse: </font>
<input type="email" name="email">
<input type="submit" value="Load Gallery" />

<br><br><br><br>
<div align = "center">
<br><font color = "#00FF00"><?php
    //displaying the time
    date_default_timezone_set('America/Chicago');
            $myDate = date('j M Y - h:i:s A');
    
            print "CURRENT TIME: ". $myDate. " | EpochSeconds";
    ?></font>
    <br><br>
</div>
</form>

</div>
<br><br><br><br>
</body>
</html>



