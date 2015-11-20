# testing simple launch without autoscaling
aws ec2 run-instances --image-id $1 --count $2 --instance-type $3 --security-group-ids $4 --subnet-id $5 --key-name $6  --associate-public-ip-address --iam-instance-profile Name=$7 --user-data file://../MP2Environment-setup/install-webserver.sh --debug

#testing database connection
aws rds create-db-instance --db-name simmoncatdb --db-instance-identifier simmon-the-cat-db --db-instance-class db.t1.micro --engine mysql --master-username LN1878 --master-user-password hesaysmeow --allocated-storage 5

# MP2 test
# aws sns create-topic --name mp2 

#wait
echo -e "\nPlease wait for a few minute, creating database : simmoncatdb . . ."

aws rds wait db-instance-available --db-instance-identifier simmon-the-cat-db

echo -e "\n Finished creating the database."

# db read-only replica
#echo "\n Creating read-only replica . . ."

#aws rds create-db-instance-read-replica --db-instance-identifier simmon-the-cat-db-read-only --source-db-instance-identifier simmon-the-cat-db 

#echo "Read-only replica created: simmon-the-cat-db-read-only "

#skipping the manual setup process
#echo "\nSetting up database for testing, please wait . . . "

#sudo php ../MP2Application-setup/setup.php


#echo -e "\nDone! Please navigate to index.php in the web browser bar for testing. "

# cloudwatch, updated Nov 19th, 2015 ---to be added into launch.sh
# aws cloudwatch put-metric-alarm --metric-name SIMMON-METRIC --alarm-name SIMMON-ALARM --alarm-description "SIMMON-ALARM triggered! " --namespace AWS/EC2 --dimensions Name=SIMMON-AUTO-SCALE,Value=SIMMON-AUTO-SCALE --statistic Average  --metric-name CPUUtilization --comparison-operator GreaterThanOrEqualToThreshold --threshold 30 --period 360 --evaluation-periods 4 -- alarm-actions arn:(----to be added---)
