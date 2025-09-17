#!/bin/bash

# db migrations
# until bin/console -q -etest d:m:m; do sleep 5; done || exit 1
bin/console -e test cache:clear || exit 1
bin/console -edev -q
echo "Running: vendor/bin/phptools (it may take a while if nothing found - no output)"
vendor/bin/phptools || exit 1
if find tests/ -type f -name '*Test.php' | grep -q .; then
    vendor/bin/phpunit --stop-on-error --stop-on-failure || exit 1
    echo "Running: XDEBUG_MODE=coverage php vendor/bin/phpunit --stop-on-error --stop-on-failure --coverage-text"
    XDEBUG_MODE=coverage php vendor/bin/phpunit --stop-on-error --stop-on-failure --coverage-text | grep 'Classes: 100.00%' || { echo "Command failed: General phpunit coverage check"; exit 1; }
else
    echo "No test files found in tests/ directory. Skipping PHPUnit checks."
fi

echo "All health checks passed successfully. No errors detected."
