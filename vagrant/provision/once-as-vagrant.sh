#!/usr/bin/env bash

source /app/vagrant/provision/common.sh

#== Import script args ==

github_token=$(echo "$1")

#== Provision script ==

info "Provision-script user: `whoami`"

sudo update-alternatives --set php /usr/bin/php7.4

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"
cd /app
composer --no-progress --prefer-dist install

info "Init project"
sudo php init --env=Development --overwrite=y

info "Apply migrations"
php yii migrate --interactive=0
php yii_test migrate --interactive=0

info "Create bash-alias 'app' for vagrant user"
echo 'alias app="cd /app"' | tee /home/vagrant/.bash_aliases

info "add alias for codecept"
echo 'alias codecept="/app/vendor/bin/codecept"' | tee -a /home/vagrant/.bash_aliases

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc

info "Sphinx sphinxsearch.service"
#sudo systemctl restart sphinxsearch.service

info "Sphinx indexer"
sudo searchd --stop
sudo searchd --config /etc/sphinxsearch/sphinx.conf
sudo indexer --all --rotate -c /etc/sphinxsearch/sphinx.conf