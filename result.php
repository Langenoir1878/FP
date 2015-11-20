<?php


/*
 * Cited from https://github.com/jhajek/itmo-544-444-fall2015/blob/master/result.php
 * Oct 25th, 2015
 * Yiming Zhang
 * ITMO 544 MP 1
 * updated passwords & username
 * Nov 4, 2015
 * login added on Nov 19
 */



// Start the session

//namespace langenoir1878;
session_start();
if(!isset($_SESSION['user']))
{
    header("Location: login.php");
    exit;
}
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<style>
.lay_content {
    background-image: url("bg.png");
    background-size: 1200px 571px;
    background-color: black;
    font-style: oblique;
    padding: 57px;
    margin-left: 10px;
    margin-right: 10px;
    margin-top: 10px;
}
.left_side {
    margin-left: 10px;
    width: 98%;
    border:1px solid black;
}

</style>

<head>
<meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <title>RESULT</title>
</head>

<body>
<div class = "lay_content">
   <font color = "white"> <h1>File Uploading Result</h1></font>
    <font color = "white"><a href = "index.php">Go back to the INDEX </a></font>
</div>

<div class = "left_side">
<?php
echo "Email:" . $_POST['useremail'];
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
$fname = $_FILES['userfile']['name'];
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}
echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";

require 'vendor/autoload.php';

use Aws\S3\S3Client;
$client = S3Client::factory(array(
    'version' => 'latest',
    'region'  => 'us-east-1'
));

$bucket = uniqid("ln1878bucket-",false);

#$result = $client->createBucket(array(
#    'Bucket' => $bucket
#));
# AWS PHP SDK version 3 create bucket
$result = $client->createBucket(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket
));

$client->waitUntil('BucketExists',array('Bucket' => $bucket));
#Old PHP SDK version 2
$key = $uploadfile;
$result = $client->putObject(array(
    'ACL' => 'public-read',
    'Bucket' => $bucket,
    'Key' => $key,
    'SourceFile' => $uploadfile 
));


$url = $result['ObjectURL']; // store to be used later...
echo "URL is: " . $url;

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
    'version' => 'latest',
    'region'  => 'us-east-1'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'simmon-the-cat-db',
));

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

echo "Endpoint: \n". $endpoint . "";
echo "*** begin database";
//below line occur connection errors
$link = mysqli_connect($endpoint,"LN1878","hesaysmeow","simmoncatdb")or die("Error in line 132 in result.php, db connection error." . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
/* Prepared statement, stage 1: prepare */
if (!($stmt = $link->prepare("INSERT INTO CAT_TABLE 
    (ID,USERNAME,EMAIL,PHONE,RAWS3URL,FINISHEDS3URL,IMGNAME,STATE,TIMESTR) VALUES (NULL,?,?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $link->errno . ") " . $link->error;
}

$USERNAME = "Y. Z. LN1878";
$EMAIL = $_POST['useremail'];
$PHONE = $_POST['phone']; 
$RAWS3URL = $url; //obtained from far above..
$IMGNAME = basename($_FILES['userfile']['name']);
$FINISHEDS3URL = "..";
$STATE =0;

date_default_timezone_set('America/Chicago');
            #$myDate = date('j M Y - h:i:s A');
$TIMESTR= "Current time: " . date('j M Y - h:i:s A');
//print "line 154 in result.php can be reached if printed out. Preparing for binding";
$stmt->bind_param("ssssssis",$USERNAME,$EMAIL,$PHONE,$RAWS3URL,$IMGNAME,$FINISHEDS3URL,$STATE,$TIMESTR);


if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
printf("%d Row inserted.\n", $stmt->affected_rows);

//obtaining the input email from page:
$myEmail = $_POST['useremail'];
//echo $myEmail;

//SNS
$sns = new Aws\Sns\SnsClient(array(
    'version' => 'latest',
    'region'  => 'us-east-1'
));
#echo "line 166 in result.php, the sns is: " . $sns;

$Arn = $sns->createTopic(array(
    'Name' => 'mp2'
));
#echo "line 170 in result.php, the arn is: " . $Arn;

#echo "Obtaining useremail: " . $myEmail;

$subscribe = $sns->subscribe(array(
    'Protocol' => 'email',
    #'Endpoint' => $myEmail,
    'Endpoint'=>"yzhan214@hawk.iit.edu",
    'TopicArn' => $Arn['TopicArn'],
));

//updated Nov 20, 2015
$topicAttributes = $sns->setTopicAttributes(array(
    'AttributeName' => 'DisplayName',
    'AttributeValue' => 'mp2' ,
    'TopicArn' => $Arn['TopicArn'],
));

$publisher = $sns->publish(array(
    'Message' => 'testing pulisher msg',
    'TopicArn' => $Arn['TopicArn']
));


#echo "line 193 in result.php, publish message: " . $pulisher;





/* explicit close recommended */
$stmt->close();

//display all records
$link->real_query("SELECT * FROM CAT_TABLE");
$res = $link->use_result();

echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo $row['ID'] . " " . $row['EMAIL']. " " . $row['PHONE'];
}

$link->close();
//add code to detect if subscribed to SNS topic 
//if not subscribed then subscribe the user and UPDATE the column in the database with a new value 0 to 1 so that then each time you don't have to resubscribe them
// add code to generate SQS Message with a value of the ID returned from the most recent inserted piece of work
//  Add code to update database to UPDATE status column to 1 (in progress)
?>
</div>

</body>
</html>