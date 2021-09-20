#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Provision script ==

info "Provision-script user: `whoami`"

info "Restart web-stack"
service php7.4-fpm restart
service nginx restart
service mariadb restart
## убить процессы демона перед перезапуском
info "shpinx `pidof searchd`"
sudo killall searchd
sudo systemctl restart sphinxsearch.service
sudo searchd
