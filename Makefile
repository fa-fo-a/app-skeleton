# Default container name
CONTAINER_NAME=appnameplaceholder

# Default user ID
USER_ID=$(shell id -u)

# Build and launch the project
build:
	@echo "Building and launching the project..."
	USER_ID=$(USER_ID) docker compose up -d --force-recreate

# Start the project
start:
	@echo "Starting the project..."
	USER_ID=$(USER_ID) docker compose start

# Stop the project (preserves internals)
stop:
	@echo "Stopping the project..."
	USER_ID=$(USER_ID) docker compose stop

# Enter the container with xdebug enabled
enter-debug:
	@echo "Entering the container with xdebug enabled..."
	docker exec -u$(USER_ID) -it -w /var/www/html -e XDEBUG_MODE=debug $(CONTAINER_NAME) /bin/bash

# Enter the container without xdebug
enter:
	@echo "Entering the container without xdebug..."
	docker exec -u$(USER_ID) -it -w /var/www/html $(CONTAINER_NAME) /bin/bash
