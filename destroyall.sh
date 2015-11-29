#!/usr/local/bin/bash

declare -a s3ARR
declare -a s3CountARR

#aws s3 rb s3://* --force
#aws s3 ls | grep bucket-name
mapfile -t s3CountARR < <(aws s3 ls)
LENGTH=${#s3CountARR[@]}

echo "ARRAY LENGTH IS $LENGTH"

for ((i=${LENGTH}; i>0; i--))
do
aws s3api list-buckets --query 'Buckets[0].Name'

#aws s3 ls --query 'Buckets[].Name'
mapfile -t s3ARR < <(aws s3api list-buckets --query 'Buckets[0].Name')
#mapfile -t s3ARR < <(aws s3 ls --query 'Buckets[].Name')

echo "The current s3 bucket is ${s3ARR[@]}"


aws s3 rb s3://${s3ARR[@]} --force --output text
sleep 1
done

echo "All s3 buckets deleted associated with this project."