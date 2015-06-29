#!/usr/bin/env bash

debconf-set-selections <<< 'mysql-server mysql-server/root_password password root'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password root'

apt-get -y update

apt-get -y install \
  htop \
  mysql-client \
  mysql-server \
  nginx \
  php5-cli \
  php5-curl \
  php5-fpm \
  php5-gd \
  php5-mcrypt \
  php5-mysql \
  php5-redis \
  php5-xdebug \
  redis-server \
  redis-tools

echo '
php_flag[display_errors] = on
php_admin_value[error_log] = /var/log/php5-error.log
php_admin_flag[log_errors] = on

env[ENV]         = dev' >> /etc/php5/fpm/pool.d/www.conf

touch /var/log/php5-error.log
chmod 777 /var/log/php5-error.log

echo '
[xdebug]
xdebug.default_enable=1
xdebug.remote_enable=1
xdebug.remote_handler=dbgp
xdebug.remote_host=localhost
xdebug.remote_port=9000
xdebug.remote_autostart=1
xdebug.profiler_enable_trigger=1' >> /etc/php5/fpm/php.ini

cd ~
curl -sS -o ioncube_loaders_lin_x86-64.tar.gz http://downloads3.ioncube.com/loader_downloads/ioncube_loaders_lin_x86-64.tar.gz
tar -zxvf ioncube_loaders_lin_x86-64.tar.gz
mv ioncube/ioncube_loader_lin_5.5.so /usr/lib/php5/
rm -rf ioncube ioncube_loaders_lin_x86-64.tar.gz

sed -i "1i\\
zend_extension=/usr/lib/php5/ioncube_loader_lin_5.5.so" /etc/php5/fpm/php.ini

sed -i "880i\\
date.timezone = 'America/Los_Angeles'" /etc/php5/fpm/php.ini

echo '
max_allowed_packet=1G
innodb_log_buffer_size=1G
innodb_file_per_table' >> /etc/mysql/my.cnf

sed -i "48i\\
bind-address            = 192.168.100.101" /etc/mysql/my.cnf

ln -s /etc/php5/mods-available/mcrypt.ini /etc/php5/fpm/conf.d/20-mcrypt.ini
ln -s /etc/php5/mods-available/mcrypt.ini /etc/php5/cli/conf.d/20-mcrypt.ini

service nginx restart
service php5-fpm restart
service mysql restart
redis-server /etc/redis/redis.conf 

echo 'redis-server /etc/redis/redis.conf' > /etc/rc.local

echo '[client]
user=root
password=root' > ~/.my.cnf

mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY PASSWORD '*81F5E21E35407D884A6CD4A731AEBFB6AF209E1B' WITH GRANT OPTION;"
