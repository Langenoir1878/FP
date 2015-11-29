#!/usr/local/bin/bash

declare -a s3ARR

#aws s3 rb s3://* --force
#aws s3 ls | grep bucket-name
aws s3api list-buckets --query 'Buckets[].Name'
#aws s3 ls --query 'Buckets[].Name'
mapfile -t s3ARR < <(aws s3api list-buckets --query 'Buckets[].Name')
#mapfile -t s3ARR < <(aws s3 ls --query 'Buckets[].Name')

echo "The current s3 buckets are ${s3ARR[@]}"

LENGTH=${#s3ARR[@]}
echo "ARRAY LENGTH IS $LENGTH"

aws s3 rb s3://${s3ARR[@]} --force --output text
sleep 1

echo "All s3 buckets deleted associated with this project."