art := php artisan
composer := composer
npm := npm

.DEFAULT_GOAL := help

.PHONY: help
help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: analyse
analyse: vendor/autoload.php ## Run phpstan
	./vendor/bin/phpstan analyse

.PHONY: test
test: vendor/autoload.php  ## Run units tests
	vendor/bin/pest

.PHONY: ide-helper
ide-helper: vendor/autoload.php ## Refresh les ide-helpers
	$(art) ide-helper:generate
	$(art) ide-helper:meta
	$(art) ide-helper:models --nowrite

###################

vendor/autoload.php: composer.lock
	$(composer) install
	touch vendor/autoload.php

node_modules/time.txt: package.json
	$(npm) install
	touch node_modules/time.txt