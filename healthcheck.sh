#!/bin/bash

#until bin/console -q -etest d:m:m; do sleep 5; done || exit 1
bin/console -e test cache:clear || exit 1
vendor/bin/phpunit --stop-on-error --stop-on-failure || exit 1
bin/console -edev
vendor/bin/phptools || exit 1
echo "Running: php -d xdebug.mode=coverage vendor/bin/phpunit --stop-on-error --stop-on-failure --coverage-text"
php -d xdebug.mode=coverage vendor/bin/phpunit --stop-on-error --stop-on-failure --coverage-text | grep 'Classes: 100.00%' || { echo "Command failed: General phpunit coverage check"; exit 1; }
