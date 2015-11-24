#!/bin/bash
# Repo ref: https://github.com/jhajek/itmo-544-444-env/blob/master/install-webserver.sh

sudo apt-get update -y
sudo apt-get install -y apache2 git php5 php5-mysql php5-curl mysql-client curl php5-imagick



sudo git clone https://github.com/Langenoir1878/FP.git

mv ./FP/images /var/www/html/images
mv ./FP/*.png /var/www/html
mv ./FP/*.js /var/www/html
mv ./FP/*.css /var/www/html
mv ./FP/*.php /var/www/html

mv ./FP/*.eot /var/www/html
mv ./FP/*.svg /var/www/html
mv ./FP/*.ttf /var/www/html
mv ./FP/*.woff /var/www/html
mv ./FP/*.woff2 /var/www/html

mv ./FP/css var/www/html
mv ./FP/js var/www/html
mv ./FP/fonts var/www/html




curl -sS https://getcomposer.org/installer | sudo php &> /tmp/getcomposer.txt

sudo php composer.phar require aws/aws-sdk-php &> /tmp/runcomposer.txt

sudo mv vendor /var/www/html &> /tmp/movevendor.txt

sudo php /var/www/html/setup.php &> /tmp/database-setup.txt



echo "Hi, install-webserver.sh has been called!" > /tmp/hello.txt

