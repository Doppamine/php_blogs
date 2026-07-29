-include .env
export
.PHONY: up down build ps logs sh composer-install schema seed assets

up: # Start containers
	docker compose up -d

down: # Stop and remove containers
	docker compose down

build: # Rebuild and start containers
	docker compose up -d --build

ps: # List running containers
	docker compose ps

logs: # PHP container logs
	docker compose logs -f php

sh: # Open bash inside PHP container
	docker compose exec php bash

composer-install: # Install composer dependencies
	docker compose exec php composer install

schema: # Recreate database schema from scratch
	docker compose exec -T -e MYSQL_PWD="$(DB_PASSWORD)" mysql \
		mysql -u"$(DB_USER)" "$(DB_NAME)" < database/schema.sql

seed: # Seed database with initial fake data
	docker compose exec php php bin/console.php db:seed

assets: # Build assets (CSS, JS, etc.)
	docker compose exec php php bin/console.php assets:build