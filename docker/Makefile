# Makefile for Docker Nginx PHP Composer MySQL

include .env

help:
	@echo ""
	@echo "usage: make COMMAND"
	@echo ""
	@echo "Commands:"
	@echo "  docker-start        Create and start containers"
	@echo "  docker-stop         Stop and clear all services"

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
	sh comands/modx-extract.sh

modx-backup:
	sh comands/modx-backup.sh

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
