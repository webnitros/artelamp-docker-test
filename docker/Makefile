# Makefile for Docker Nginx PHP Composer MySQL

include .env

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  docker-start        Create and start containers"
	@echo "  docker-stop         Stop and clear all services"
	@echo "  modx-extract        Export elements modx"

composer-up:
	@docker run --rm -v $(shell pwd)/web/app:/app composer update

yarn-up:
	@docker exec -it $(shell docker-compose ps -q node) yarn -c install

docker-start: init
	docker-compose up --build --detach

docker-stop:
	docker-compose stop


docker-restart:
	@make docker-stop
	@make clean
	@make docker-start

modx-extract:
	@docker exec -i $(shell docker-compose ps -q php-fpm) bash -c "gitify extract"

modx-backup:
	@docker exec -i $(shell docker-compose ps -q php-fpm) bash -c "rm -rf ./_backup/db.sql && rm -rf ./_backup/db.tar.gz && gitify backup db.sql && cd ./_backup && tar -czvf db.tar.gz db.sql && rm -rf db.sql && cd ../ && gitify extract"

logs:
	@docker-compose logs -f

tests:
	@docker exec -it $(shell docker-compose ps -q node) npm run test

clean:
	@rm -Rf ../modx/composer.lock
	@rm -Rf ../modx/core/cache/
	@rm -Rf ../modx/vendor/
	@rm -Rf ../assets/node_modules/

.PHONY: clean test code-sniff init
