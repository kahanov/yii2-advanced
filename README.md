# Запуск окружения

1. Скачиваем и устанавливаем virtualbox https://www.virtualbox.org/wiki/Downloads для своей ос.

2. Скачиваем и устанавливаем vagrant https://www.vagrantup.com/downloads.html для своей ос.

3. Перезагружаемся

4. Создаем токен на https://github.com/settings/tokens/new.

5. Открываем и редактируем файл vagrant/config/vagrant-local.yml (если у вас нет этого файла - у вас должен быть файл vagrant-local.example.yml - сохраните этот файл с именем vagrant-local.yml). 
В этом файле, вам нужно будет указать токен вашего аккаунта на github в параметре github_token.

6. В корне проекта запускаем в терминале команду 'vagrant up' и ждем установку виртуальной машины и после чего проект станет доступен по адресам:
	http://mall.test
	http://admin.mall.test
	http://phpmyadmin.mall.test/
	http://samara.mall.test/
	http://moscow.mall.test/
	
	для остановки проекта используйте команду 'vagrant suspend'
	для запуска/перезапуска проекта используйте команду 'vagrant reload'
	если сделали какие либо изменения в конфигурации используйте команду 'vagrant provision'
	
7. Если по какой то причине в sphinx не проиндексировались страны и города при развертывании проекта, 
   то необходимо проиндексировать страны и города в sphinx в ручную на виртуальной машине. 
   Для этого соединяемся с виртуальной машиной по ssh 192.168.83.140 
   Переходим в корень проекта командой «app» и запускаем команды:
   ```
   sudo searchd --stop
   sudo cp /app/vagrant/sphinx/sphinx.conf /etc/sphinxsearch/sphinx.conf
   sudo searchd --config /etc/sphinxsearch/sphinx.conf
   sudo indexer --all --rotate -c /etc/sphinxsearch/sphinx.conf
   ```
   
8. Теперь необходимо зарегистрировать пользователя http://mall.test/signup и назначить его администратором командой «php yii role/assign»

--------------------------------------

