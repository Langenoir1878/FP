#!/usr/local/bin/bash

declare -a s3ARR

#aws s3 rb s3://* --force
#aws s3 ls | grep bucket-name
s3api list-buckets --query 'Buckets[].Name'

mapfile -t s3ARR < <(aws s3 ls)

echo "The current s3 buckets are ${s3ARR[@]}"

LENGTH=${#s3ARR[@]}
echo "ARRAY LENGTH IS $LENGTH"
for (( i=0; i<${LENGTH}; i++)); 
  do
aws s3 rb s3://${s3ARR[i]} --output text
sleep 1

echo "All s3 buckets deleted associated with this project."
done