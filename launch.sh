# LAST UPDATED: NOV 6, 2015
# Revised on OCT 25, 13:12:11 PM
# Yiming Zhang
# UPDATED INSTRUCTIONS UPON LAUNCHING NOV 4th, 2015
# 10:09:35
# 7 args to be put in the sequence below:

# $1 image-ids
# $2 count of instances to launch
# $3 instance-type
# $4 security-group-ids
# $5 subnet-id
# $6 key-name
# $7 iam-instance-profile

# 1. execute the cleanup.sh
./cleanup.sh

# 2. declare an array in bash

declare -a instanceARR

# 3. mapfile (updated Nov 4, 2015)
mapfile -t instanceARR < <(aws ec2 run-instances --image-id $1 --count $2 --instance-type $3 --security-group-ids $4 --subnet-id $5 --key-name $6  --associate-public-ip-address --iam-instance-profile Name=$7 --user-data file://../MP2Environment-setup/install-webserver.sh --output table | grep InstanceId | sed "s/|//g" | tr -d ' ' | sed "s/InstanceId//g")

echo ${instanceARR[@]}

# 4. wait cli 
aws ec2 wait instance-running --instance-ids ${instanceARR[@]}

echo "instances are running."

# 5. create ELB (updated 11/02/2015, Nov 4, 2015. dif *** ???security-group representations for ec2 and elb?)
ELBURL=(`aws elb create-load-balancer --load-balancer-name SIMMON-THE-CAT --listeners Protocol=HTTP,LoadBalancerPort=80,InstanceProtocol=HTTP,InstancePort=80 --security-groups $4 --subnets $5 --output=text`) 
echo $ELBURL

#sleep
echo -e "\nELB created, sleep 7 seconds"
for i in {0..7}; do echo -ne '.'; sleep 1; done
echo -e "\n"

# 5.5 (Nov 7, 2015 cookie stickyness session)
#aws elb create-lb-cookie-stickiness-policy --load-balancer-name SIMMON-THE-CAT --policy-name SIMMON-COOKIE-POLICY --cookie-expiration-period 60
#aws elb set-load-balancer-policies-of-listener --load-balancer-name SIMMON-THE-CAT --load-balancer-port 443 --policy-names SIMMON-COOKIE-POLICY


# 6. register instances with ELB (updated 11/02/2015)
aws elb register-instances-with-load-balancer --load-balancer-name SIMMON-THE-CAT --instances ${instanceARR[@]}

# 7. ELB health-check configuration (updated Nov 4, 2015)
aws elb configure-health-check --load-balancer-name SIMMON-THE-CAT --health-check Target=HTTP:80/index.html,Interval=30,UnhealthyThreshold=2,HealthyThreshold=2,Timeout=3

# wait additional 1 min
#echo -e "\nOpening the ELB in 1 minute in web browser"
#for i in {0..60}; do echo -ne '.'; sleep 1; done


# 8. launch configuration (updated Nov 4, 2015)
echo -e "\nCreating launch configuration: SIMMON-CONFIG-LAUNCH"
aws autoscaling create-launch-configuration --launch-configuration-name SIMMON-CONFIG-LAUNCH --image-id $1 --instance-type $3 --security-groups $4 --key-name $6 --user-data file://../Environment-setup/install-webserver.sh --iam-instance-profile $7 --debug

# 9. auto-scaling (updated Nov 4, 2015)
echo -e "\nCreating the auto scaling group"
aws autoscaling create-auto-scaling-group --auto-scaling-group-name SIMMON-AUTO-SCALE --launch-configuration-name SIMMON-CONFIG-LAUNCH --load-balancer-name SIMMON-THE-CAT --health-check-type ELB --min-size 1 --max-size 3 --desired-capacity 2 --default-cooldown 600 --health-check-grace-period 120 --vpc-zone-identifier $5

# secure the count of total instances
# ref: http://docs.aws.amazon.com/AutoScaling/latest/APIReference/API_PutScalingPolicy.html
aws autoscaling put-scaling-policy --auto-scaling-group-name SIMMON-AUTO-SCALE --policy-name SIMMON-SCALE-POLICY --scaling-adjustment 1 --adjustment-type ExactCapacity


# 10. rds instance 
echo -e "\nCreating database"
mapfile -t dbInstanceARR < <(aws rds describe-db-instances --output json | grep "\"DBInstanceIdentifier" | sed "s/[\"\:\, ]//g" | sed "s/DBInstanceIdentifier//g")

#if [ ${#dbInstanceARR[@]} -gt 0 ]
#   then 
#echo ${#dbInstanceARR[@]}

LENGTH=${#dbInstanceARR[@]}
#echo $LENGTH	

for (( i=0; i<=${LENGTH}; i++));
do
   	if [[ ${dbInstanceARR[i]} == "simmon-the-cat-db" ]]
    then 
    echo "simmon-the-cat-db exists"
    else
    	# create the database instance, aws rds
    	#updated on Nov 3, added --db-name simmoncatdb for testing
    	aws rds create-db-instance --db-name simmoncatdb --db-instance-identifier simmon-the-cat-db --db-instance-class db.t1.micro --engine MySQL --master-username LN1878 --master-user-password hesaysmeow --allocated-storage 5 
    	# below added for testing revised ports 
    	# Nov 17th, 2015 @IIT-GL 2 FL
    	#--vpc-security-group-ids sg-c0f45da6  --availability-zone us-east-1e
    fi  
done

#wait additional 3 min for creating the database
#echo -e "\nPlease wait 3 min, creating database : SIMMON-THE-CAT"
#for i in {0..180}; do echo -ne '. '; sleep 1; done

# Revised wait command for creating database
# Date Revised: 08:25 AM, W, Nov 17th, 2015 @ IIT-GL 2 FL
echo -e "\nPlease wait for a few minute, creating database : simmoncatdb . . ."

aws rds wait db-instance-available --db-instance-identifier simmon-the-cat-db

echo -e "\n Finished creating the database."

# db read-only replica
aws rds create-db-instance-read-replica --db-instance-identifier simmon-the-cat-db-read-only --source-db-instance-identifier simmon-the-cat-db

echo "db read-only replica created! "

# 11 cloudwatch metrics (updatd Nov 6, 2015)
# ref: http://docs.aws.amazon.com/AmazonCloudWatch/latest/DeveloperGuide/UsingAlarmActions.html#UsingCLIorAPI
aws cloudwatch put-metric-alarm --metric-name SIMMON-CW-METRIC --alarm-name SIMMON-ALARM --alarm-description "SIMMON-ALARM triggered! " --namespace AWS/EC2 --dimensions Name=SIMMON-AUTO-SCALE,Value=SIMMON-AUTO-SCALE --statistic Average  --metric-name CPUUtilization --comparison-operator GreaterThanOrEqualToThreshold --threshold 30 --period 360 --evaluation-periods 4 --alarm-actions arn:aws:sns:us-east-1:186069030643:mp2 --unit Percet
aws cloudwatch put-metric-alarm --metric-name SIMMON-SNS-METRIC --alarm-name SIMMON-SNS-ALARM --alarm-description "SNS alarm triggered: Updates available" --namespace AWS/EC2 --dimensions Name=SIMMON-AUTO-SCALE,Value=SIMMON-AUTO-SCALE --statistic Average  --metric-name CPUUtilization --comparison-operator GreaterThanOrEqualToThreshold --threshold 30 --period 360 --evaluation-periods 4 --alarm-actions arn:aws:sns:us-east-1:186069030643:mp2
# 12 skipping the manual setup process (added Nov 19, 2015)
#echo "\nSetting up database for testing, please wait . . . "

#sudo php ../MP2Application-setup/setup.php


echo -e "\nDone! Please navigate to index.php in the web browser bar for testing. "
