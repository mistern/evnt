.PHONY: it
it: qa unused-checks required-checks

.PHONY: qa
qa: static-analysis tests mutation-tests

.PHONY: static-analysis
static-analysis: vendor
	vendor/bin/psalm --no-progress --no-cache

.PHONY: unused-checks
unused-checks: vendor
	vendor/bin/composer-unused --no-progress

.PHONY: required-checks
required-checks: vendor
	vendor/bin/composer-require-checker check \
		--config-file=composer-require-checker.json

.PHONY: mutation-tests
mutation-tests: vendor
	vendor/bin/infection run \
		--no-progress \
		-c infection.json

.PHONY: tests
tests: vendor
	vendor/bin/phpunit

vendor: composer.json composer.lock
	composer validate
	composer install
