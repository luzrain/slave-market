.DEFAULT_GOAL := help


.PHONY: help
help: ## Справка по командам
	@grep -E '^.*:.*?## .*$$' $(MAKEFILE_LIST) \
		| grep -v '@grep' | grep -v 'BEGIN' | sort \
		| awk 'BEGIN {FS = ":.*? ## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'


.PHONY: build
build: prebuild tests/execute ## Билд приложения

prebuild:
	@docker-compose up -d --build
	@docker-compose exec php composer install


.PHONY: tests/execute
tests/execute: ## Запуск юнит-тестов
	@docker-compose exec php vendor/bin/phpunit
