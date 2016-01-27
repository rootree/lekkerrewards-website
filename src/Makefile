all: composer.phar vendors install_git_hooks

composer.phar:
	curl -sS https://getcomposer.org/installer | php

vendors:
	php composer.phar self-update && php composer.phar install -o -vvvv