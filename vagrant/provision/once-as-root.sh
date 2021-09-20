#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

timezone=$(echo "$1")

readonly IP=$2

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "AWK initial replacement work"
awk -v ip=$IP -f /app/vagrant/provision/provision.awk /app/environments/dev/*end/config/main-local.php

info "Update OS software"
apt-get update
apt-get upgrade -y

info "Add ppa:ondrej/php"
apt-get install -y python-software-properties
apt-get update && apt-get upgrade -y
add-apt-repository -y ppa:ondrej/php

info "Add MariaDb repository"
apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0xF1656F24C74CD1D8
#sudo add-apt-repository 'deb [arch=amd64] http://mirror.zol.co.zw/mariadb/repo/10.3/ubuntu xenial main' -y
add-apt-repository 'deb [arch=amd64,arm64,i386,ppc64el] http://mirror.timeweb.ru/mariadb/repo/10.3/ubuntu xenial main' -y

apt-get install -y mc

info "Install additional software"
apt-get install -y php7.4-curl php7.4-cli php7.4-intl php7.4-mysqlnd php7.4-gd php7.4-fpm php7.4-mbstring php7.4-xml php7.4-zip unzip nginx php.xdebug php7.4-memcached memcached graphviz

sudo sh -c 'DEBIAN_FRONTEND=noninteractive apt-get install -y mariadb-server mariadb-client'

info "Configure MySQL"
sudo cat /dev/null > /etc/mysql/my.cnf
sudo cat >> /etc/mysql/my.cnf <<'EOF'
[client]
port            = 3306
socket          = /var/run/mysqld/mysqld.sock
default-character-set = utf8

[mysqld_safe]
socket          = /var/run/mysqld/mysqld.sock
nice            = 0

[mysqld]
user            = mysql
pid-file        = /var/run/mysqld/mysqld.pid
socket          = /var/run/mysqld/mysqld.sock
port            = 3306
basedir         = /usr
datadir         = /var/lib/mysql
tmpdir          = /tmp
lc_messages_dir = /usr/share/mysql
lc_messages     = en_US
skip-external-locking
bind-address = 0.0.0.0
max_connections         = 100
connect_timeout         = 5
wait_timeout            = 600
max_allowed_packet      = 16M
thread_cache_size       = 128
sort_buffer_size        = 4M
bulk_insert_buffer_size = 16M
tmp_table_size          = 32M
max_heap_table_size     = 32M
character-set-server=utf8
collation-server=utf8_general_ci
init-connect="SET NAMES utf8"
skip-character-set-client-handshake
myisam_recover_options = BACKUP
key_buffer_size         = 128M
table_open_cache        = 400
myisam_sort_buffer_size = 512M
concurrent_insert       = 2
read_buffer_size        = 2M
read_rnd_buffer_size    = 1M
query_cache_limit               = 128K
query_cache_size                = 64M
log_warnings            = 2
slow_query_log_file     = /var/log/mysql/mariadb-slow.log
long_query_time = 10
log_slow_verbosity      = query_plan
log_bin                 = /var/log/mysql/mariadb-bin
log_bin_index           = /var/log/mysql/mariadb-bin.index
expire_logs_days        = 10
max_binlog_size         = 100M
default_storage_engine  = InnoDB
innodb_buffer_pool_size = 512M
innodb_log_buffer_size  = 32M
innodb_file_per_table   = 1
innodb_open_files       = 400
innodb_io_capacity      = 400
innodb_flush_method     = O_DIRECT
innodb_log_file_size=512M
innodb_strict_mode=0

[galera]
bind-address = 0.0.0.0

[mysqldump]
quick
quote-names
max_allowed_packet      = 16M
default-character-set = utf8

[mysql]
default-character-set = utf8

[isamchk]
key_buffer              = 16M

!include /etc/mysql/mariadb.cnf
!includedir /etc/mysql/conf.d/
EOF

sed -i "s/.*bind-address.*/bind-address = 0.0.0.0/" /etc/mysql/my.cnf
mysql -uroot <<< "CREATE USER 'root'@'%' IDENTIFIED BY ''"
mysql -uroot <<< "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%'"
mysql -uroot <<< "DROP USER 'root'@'localhost'"
mysql -uroot <<< "FLUSH PRIVILEGES"
echo "Done!"

sudo service mariadb restart

# Install phpmyadmin
TEMP_DIR="/home/vagrant"
PHPMYADMIN_DIR="/usr/share/phpmyadmin"
#PHPMYADMIN_ARCHIVE="phpMyAdmin-4.8.5-all-languages"
#PHPMYADMIN_ARCHIVE_URL="https://files.phpmyadmin.net/phpMyAdmin/4.8.5/${PHPMYADMIN_ARCHIVE}.tar.gz"
PHPMYADMIN_ARCHIVE="phpMyAdmin-5.0.4-all-languages"
PHPMYADMIN_ARCHIVE_URL="https://files.phpmyadmin.net/phpMyAdmin/5.0.4/${PHPMYADMIN_ARCHIVE}.tar.gz"

echo -e "\n--- Downloading phpmyadmin... ---\n"
sudo wget -O ${TEMP_DIR}/${PHPMYADMIN_ARCHIVE}.tar.gz ${PHPMYADMIN_ARCHIVE_URL} > /dev/null 2>&1;

echo -e "\n--- Uncompressing phpmyadmin... ---\n"
sudo tar -xzvf ${TEMP_DIR}/${PHPMYADMIN_ARCHIVE}.tar.gz > /dev/null 2>&1;

echo -e "\n--- Installing phpmyadmin... ---\n"
sudo mv ${TEMP_DIR}/${PHPMYADMIN_ARCHIVE} ${PHPMYADMIN_DIR} > /dev/null 2>&1;

echo -e "\n--- Configure phpmyadmin to connect automatically for rzmonitor2 DB ---\n"
sudo cat /dev/null > ${PHPMYADMIN_DIR}/config.inc.php
sudo cat >> ${PHPMYADMIN_DIR}/config.inc.php <<'EOF'
<?php
/**
 * phpMyAdmin sample configuration, you can use it as base for
 * manual configuration. For easier setup you can use setup/
 *
 * All directives are explained in documentation in the doc/ folder
 * or at <http://docs.phpmyadmin.net/>.
 *
 * @package PhpMyAdmin
 */
$cfg['blowfish_secret'] = '';
/**
 * Servers configuration
 */
$i = 0;
$i++;
/* Authentication type */
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = '';
/* Server parameters */
$cfg['Servers'][$i]['host'] = '192.168.83.140';
$cfg['Servers'][$i]['connect_type'] = 'tcp';
$cfg['Servers'][$i]['compress'] = false;
$cfg['Servers'][$i]['AllowNoPassword'] = true;
EOF

echo -e "\n-----------------------------------------------------------------"
echo -e "\n------------------- Your phpmyadmin is ready --------------------"
echo -e "\n-----------------------------------------------------------------"
echo -e "\n* Type http://phpmyadmin.mall.test for your MySQL db admin."
echo -e "\n-----------------------------------------------------------------"

info "Configure PHP-FPM"
sed -i 's/user = www-data/user = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/group = www-data/group = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
sed -i 's/owner = www-data/owner = vagrant/g' /etc/php/7.4/fpm/pool.d/www.conf
cat << EOF > /etc/php/7.4/mods-available/xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable=1
xdebug.remote_connect_back=1
xdebug.client_port=9000
xdebug.remote_autostart=1
xdebug.client_host = 192.168.83.1
xdebug.mode = debug,develop
EOF
echo "Done!"

info "Configure NGINX"
sed -i 's/user www-data/user vagrant/g' /etc/nginx/nginx.conf
echo "Done!"

info "Enabling site configuration"
ln -s /app/vagrant/nginx/app.conf /etc/nginx/sites-enabled/app.conf
echo "Done!"

info "Initailize databases for MySQL"
mysql -uroot <<< "CREATE DATABASE mall"
mysql -uroot <<< "CREATE DATABASE mall_test"
echo "Done!"

info "Install composer"
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

info "Install Sphinx"
sudo apt-get install -y sphinxsearch
sudo cp /app/vagrant/sphinx/sphinx.conf /etc/sphinxsearch/sphinx.conf
sudo sed -i 's/START=no/START=yes/g' /etc/default/sphinxsearch
