all: cs-test phpunit

cs-test:
	./vendor/bin/php-cs-fixer fix -v --dry-run --config-file=.php_cs src;
	./vendor/bin/php-cs-fixer fix -v --dry-run --config-file=.php_cs tests;

cs-fix:
	./vendor/bin/php-cs-fixer fix -v --config-file=.php_cs src;
	./vendor/bin/php-cs-fixer fix -v --config-file=.php_cs tests;

phpunit:
	./vendor/bin/phpunit
