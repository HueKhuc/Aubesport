.PHONY: vendor
vendor:
	composer install --prefer-dist --no-progress --no-suggest

.PHONY: phpstan
phpstan:
	./vendor/bin/phpstan

.PHONY: cs-fix
cs-fix:
	./vendor/bin/phpcbf
