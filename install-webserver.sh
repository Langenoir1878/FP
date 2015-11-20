#!/bin/bash
# Repo ref: https://github.com/jhajek/itmo-544-444-env/blob/master/install-webserver.sh

sudo apt-get update -y
sudo apt-get install -y apache2 git php5 php5-mysql php5-curl mysql-client curl



sudo git clone https://github.com/Langenoir1878/MP2Application-setup.git

mv ./MP2Application-setup/images /var/www/html/images
mv ./MP2Application-setup/*.png /var/www/html
mv ./MP2Application-setup/*.js /var/www/html
mv ./MP2Application-setup/*.css /var/www/html
mv ./MP2Application-setup/*.php /var/www/html

mv ./MP2Application-setup/*.eot /var/www/html
mv ./MP2Application-setup/*.svg /var/www/html
mv ./MP2Application-setup/*.ttf /var/www/html
mv ./MP2Application-setup/*.woff /var/www/html
mv ./MP2Application-setup/*.woff2 /var/www/html

mv ./MP2Application-setup/css var/www/html
mv ./MP2Application-setup/js var/www/html
mv ./MP2Application-setup/fonts var/www/html




curl -sS https://getcomposer.org/installer | sudo php &> /tmp/getcomposer.txt

sudo php composer.phar require aws/aws-sdk-php &> /tmp/runcomposer.txt

sudo mv vendor /var/www/html &> /tmp/movevendor.txt

sudo php /var/www/html/setup.php &> /tmp/database-setup.txt



echo "Hi, install-webserver.sh has been called!" > /tmp/hello.txt

