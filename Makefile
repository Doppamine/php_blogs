.PHONY: up down build ps logs sh composer-install

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