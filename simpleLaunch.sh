# testing simple launch without autoscaling
aws ec2 run-instances --image-id $1 --count $2 --instance-type $3 --security-group-ids $4 --subnet-id $5 --key-name $6  --associate-public-ip-address --iam-instance-profile Name=$7 --user-data file://install-webserver.sh

#testing database connection
aws rds create-db-instance --db-name simmoncatdb --db-instance-identifier simmon-the-cat-db --db-instance-class db.t1.micro --engine mysql --master-username LN1878 --master-user-password hesaysmeow --allocated-storage 5

# MP2 test
# aws sns create-topic --name mp2 

#wait
echo -e "\nPlease wait for a few minute, creating database : simmoncatdb . . ."

aws rds wait db-instance-available --db-instance-identifier simmon-the-cat-db

echo -e "\n Finished creating the database."

