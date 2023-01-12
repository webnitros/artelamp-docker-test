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

docker-start: init
	docker-compose up --build --detach

docker-stop:
	docker-compose stop
	@make clean

docker-restart:
	@make docker-stop
	@make clean
	@make docker-start

modx-extract:
	@docker exec -i $(shell docker-compose ps -q php-fpm) bash -c "gitify extract"

modx-gitify-build:
	@docker exec -i $(shell docker-compose ps -q php-fpm) bash -c "gitify build"

modx-restore:
	@echo "Import db.sql mysql..."
	@docker exec -i $(shell docker-compose ps -q mariadb) mysql -u"$(MARIADB_USERNAME)" -p"$(MARIADB_PASSWORD)" "$(MARIADB_DATABASE)" < $(MYSQL_DUMPS_DIR)/db.sql
	@rm -Rf $(MYSQL_DUMPS_DIR)/db.sql

modx-restore-install:
	sh docker/install.sh
	@make modx-restore

modx-backup:
	@docker exec -i $(shell docker-compose ps -q php-fpm) bash -c "rm -rf ./_backup/db.sql && rm -rf ./_backup/db.tar.gz && gitify backup db.sql && cd ./_backup && tar -czvf db.tar.gz db.sql && rm -rf db.sql && cd ../ && gitify extract"

logs:
	@docker-compose logs -f

clean:
	@rm -Rf ./modx/vendor
	@rm -Rf ./modx/composer.lock

.PHONY: clean test code-sniff init
