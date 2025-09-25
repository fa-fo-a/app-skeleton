# Default container name
CONTAINER_NAME=appnameplaceholder

# Default user ID
USER_ID=$(shell id -u)

# Auto-detect platform
PLATFORM=$(shell uname -m | sed 's/x86_64/linux\/amd64/;s/aarch64/linux\/arm64/;s/arm64/linux\/arm64/')

# Build and launch the project
build:
	@echo "Building and launching the project..."
	USER_ID=$(USER_ID) PLATFORM=$(PLATFORM) docker compose up -d --force-recreate

purge:
	@echo "Purging the project..."
	USER_ID=$(USER_ID) PLATFORM=$(PLATFORM) docker compose down --volumes --remove-orphans

# Start the project
start:
	@echo "Starting the project..."
	USER_ID=$(USER_ID) PLATFORM=$(PLATFORM) docker compose start

# Stop the project (preserves internals)
stop:
	@echo "Stopping the project..."
	USER_ID=$(USER_ID) PLATFORM=$(PLATFORM) docker compose stop

# Enter the container with xdebug enabled
enter-debug:
	@echo "Entering the container with xdebug enabled..."
	docker exec -u$(USER_ID) -it -w /var/www/html -e XDEBUG_MODE=debug $(CONTAINER_NAME) /bin/bash

# Enter the container without xdebug
enter bash:
	@echo "Entering the container without xdebug..."
	docker exec -u$(USER_ID) -it -w /var/www/html $(CONTAINER_NAME) /bin/bash

healthcheck:
	@echo "Tests and static analyzing of application..."
	@if ! docker ps --format '{{.Names}}' | grep -q "^$(CONTAINER_NAME)\$$"; then \
		echo "Container $(CONTAINER_NAME) is not running. Run it by make build or make start "; \
		exit 1; \
	fi
	docker exec -u$(USER_ID) -it -w /var/www/html $(CONTAINER_NAME) /bin/bash ./healthcheck.sh

uninstall_example:
	@if ! docker ps --format '{{.Names}}' | grep -q "^$(CONTAINER_NAME)\$$"; then \
		echo "Container $(CONTAINER_NAME) is not running. Run it by make build or make start "; \
		exit 1; \
	fi
	@echo "WARNING: This action can only be performed once, as it removes script that know what to remove."
	@echo "Please carefully review the files to be removed to ensure none are necessary."
	docker exec -u$(USER_ID) -it $(CONTAINER_NAME) /bin/bash /var/www/html/uninstall_example.sh
	docker exec -u$(USER_ID) -it $(CONTAINER_NAME) rm -f /var/www/html/uninstall_example.sh
