<?php
/*
 * 
 * NOV 29 2015 SUN
 * Yiming Zhang
 * ITMO 544 MP- FINAL
 * Introspection 
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
  <title>Introspection</title>
</head>

<body>
<link rel="stylesheet" type="text/css" href="stylesheet.css" title="Style">
    <div class = "lay_content" align = "center" >
      <font color = "yellow"><h2>
<?php
// Start the session
require 'vendor/autoload.php';
use Aws\Rds\RdsClient;
$rds = new Aws\Rds\RdsClient(array(
    'version' => 'latest',
    'region'  => 'us-east-1'
));

# updated Nov 29, for testing $rds
$result = $rds->describeDBInstances(array(
	'DBInstanceIdentifier'=>'simmon-the-cat-db'
	));

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

$link = new mysqli($endpoint,"LN1878","hesaysmeow","simmoncatdb") or die("Error in line 58 in introspection.php" . mysqli_error($link)); 

/* check connection */

if (mysqli_connect_errno()) {

    printf("Connect failed: %s\n", mysqli_connect_error());

    exit();
  }

//triggering upload button -> disabled. still working on the logic for this part
?></h2></font>



<br><br>
<font color = "#00FF00"><h1><a href="index.php"> * Index * </a></h1></font>
</div>

</body>
</html>
