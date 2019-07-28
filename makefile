up:
	docker-compose up

stop:
	docker-compose stop

php:
	docker-compose exec php-fpm sh

rebuild:
	docker-compose up -d --build --force-recreate

logs:
	docker-compose logs -f

mysql:
	docker-compose exec mysql sh
