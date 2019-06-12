1. PSPStorm plugins:  
https://blog.martinhujer.cz/best-phpstorm-plugins-for-symfony-development/
2. On VM:  
   1. Update apps  
   `sudo apt-get update`  
   2. Open port 22  
   `ufw allow 22`  
   3. Install Docker  
   `sudo apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D`  
   `sudo apt-add-repository 'deb https://apt.dockerproject.org/repo ubuntu-xenial main'`  
   `sudo apt-get update`  
   `apt-cache policy docker-engine`  
   `sudo apt-get install -y docker-engine`  
   `sudo systemctl status docker`  
   `sudo usermod -aG docker $(whoami)`  
   4. Install docker-compose  
   <code>sudo curl -L https://github.com/docker/compose/releases/download/1.18.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose</code>  
   `sudo chmod +x /usr/local/bin/docker-compose`  
   `docker-compose --version`
   5. Install git  
   `sudo apt-get install git`    
   6. Create SSH key for deploy:  
   (more details here: https://medium.com/@lewdaly/circleci-docker-digital-ocean-409628f5a428)  
   `cd /root/.ssh && ssh-keygen -t rsa` (without password) > `/root/.ssh/id_rsa_deploy` > `cat id_rsa_deploy.pub >> authorized_keys`  
   7. Reboot  
   `sudo shutdown -r now`  
   8. Run containers and execute migrations:  
   `docker-compose exec php-fpm bash` > `bin/console d:m:mi`
   9. Check router:    
   `bin/console debug:router --env=prod`  
   10. Add swap memory    
   `https://www.digitalocean.com/community/tutorials/how-to-add-swap-space-on-ubuntu-16-04`  
   11. Create 'uploads' directory and set the permissions from within container:    
   `chmod -R 777 public/uploads/`
   12. Grant all permissions to 'uploads' directory on host:    
   `sudo chmod -R 777 uploads`  
   13. Configure database backup:  
   Edit crontab  
   `crontab -e`  
   Prepare crontab job to run every week  
   `@weekly cd /root/opt/mdshop/ && docker-compose exec mysql bash -c "mysqldump --no-create-info -u root -p'$(grep -oP '^MYSQL_ROOT_PASSWORD=\K.*' .env)' $(grep -oP '^MYSQL_DATABASE=\K.*' .env) > './public/uploads/mysqlbackups/$(date +%d-%m-%Y).sql'"`


3. On LM:  
   1. `scp <user>@<droplet_ip>:~/.ssh/id_rsa_deploy ~/.ssh/ && ssh-add ~/.ssh/id_rsa_deploy`
   2. Install pbcopy  
   `sudo apt-get install -y xclip`  
   `alias pbcopy="xclip -sel clip"`  
   3. `pbcopy < ~/.ssh/id_rsa_deploy`
 
НЕ ОСТАВЛЯТЬ ДЕФОЛТНЫЕ РЕКВИЗИТЫ К БАЗЕ, А ТО БУДЕТ КАК В ПРОШЛЫЙ РАЗ
