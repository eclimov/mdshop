1. On VM:  
1 Update apps  
`sudo apt-get update`  
2 Open port 22  
`ufw allow 22`  
3 Install Docker  
`sudo apt-key adv --keyserver hkp://p80.pool.sks-keyservers.net:80 --recv-keys 58118E89F3A912897C070ADBF76221572C52609D`  
`sudo apt-add-repository 'deb https://apt.dockerproject.org/repo ubuntu-xenial main'`  
`sudo apt-get update`  
`apt-cache policy docker-engine`  
`sudo apt-get install -y docker-engine`  
`sudo systemctl status docker`  
`sudo usermod -aG docker $(whoami)`  
4 Install docker-compose  
<code>sudo curl -L https://github.com/docker/compose/releases/download/1.18.0/docker-compose-`uname -s`-`uname -m` -o /usr/local/bin/docker-compose</code>  
`sudo chmod +x /usr/local/bin/docker-compose`  
`docker-compose --version`  
5 Install git  
`sudo apt-get install git`    
6 Check out SSH instructions:  
https://medium.com/@lewdaly/circleci-docker-digital-ocean-409628f5a428  
7 Install npm  
`apt install npm`  
within project's directory: `npm install`  
8 Install composer  
`apt install composer`  
within project's directory: `composer install`  
9 Reboot  
`sudo shutdown -r now`  
10 Check router:  
`bin/console debug:router --env=prod`  
11 Add swap memory  
`https://www.digitalocean.com/community/tutorials/how-to-add-swap-space-on-ubuntu-16-04`  
12 Add permissions to log folder (from within container)  
`chmod -R 777 var/log/` 

2. On LM:  
1 Install pbcopy  
`sudo apt-get install -y xclip`  
`alias pbcopy="xclip -sel clip"`
