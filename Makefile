init: build composer-install

build:
	docker-compose build

composer-install:
	docker-compose run --rm php-cli composer install

up:
	docker-compose up -d

stop:
	docker-compose stop

down:
	docker-compose down

restart:
	docker-compose restart