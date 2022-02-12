.PHONY: help
help: ## Справка по командам
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z1-9_-]+:.*?## / {printf "  \033[32m%-16s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Билд образа
	@docker-compose build

.PHONY: start
start: up vendor ## Старт приложения

.PHONY: stop
stop: ## Стоп приложения
	@docker-compose down

.PHONY: clean
clean: ## Стоп приложения и очистка файлов
	@docker-compose down -v
	@rm -rf vendor var

.PHONY: up
up:
	@docker-compose up -d

vendor: composer.json composer.lock
	@docker-compose exec php composer install
	@touch -c vendor

.PHONY: sh
sh: ## Войти в консоль php контейнера
	@docker-compose exec php sh

.PHONY: tests
tests: ## Запуск юнит-тестов
	@docker-compose exec php vendor/bin/phpunit

.PHONY: psalm
psalm: ## Запуск статического анализатора кода
	@docker-compose exec php vendor/bin/psalm

.PHONY: cs-fixer
cs-fixer: ## Запустить cs-fixer и исправить codestyle
	docker-compose exec php vendor/bin/php-cs-fixer fix --using-cache no --using-cache=no

.PHONY: cs-fixer-test
cs-fixer-test: ## Запустить cs-fixer и показать diff (не исправлять)
	docker-compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff --using-cache=no --show-progress=dots