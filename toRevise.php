<?php
/*
 * Cited from https://github.com/jhajek/itmo-544-444-fall2015/blob/master/setup.php
 * Oct 25th, 2015
 * Yiming Zhang
 * ITMO 544 MP 1
 *
 */
?>
<DOCTYPE html>
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
  <title>Setup</title>
</head>

<body>
<link rel="stylesheet" type="text/css" href="stylesheet.css" title="Style">
    <div class = "lay_content" align = "center" >
      <font color = "yellow"><h2>
<?php
// Start the session
require 'vendor/autoload.php';
use Aws\Rds\RdsClient;
#$rds = new Aws\Rds\RdsClient(array(
 #   'version' => 'latest',
  #  'region'  => 'us-east-1'
#));
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
  echo "CAT_TABLE created";
} 
else { echo "Create table failed"; }

$link->close();

?></h2>
</font>
<br><br>
<font color = "#00FF00"><h1><a href="index.php"> * Index * </a></h1></font>
</div>

</body>
</html>
