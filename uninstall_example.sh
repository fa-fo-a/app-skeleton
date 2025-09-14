#!/bin/bash

rm src/UI/Http/Controller/HelloWorldController.php
rm -r src/UI/Http/Factory
rm -r src/Infrastructure/ArgumentResolver

rm config/routes.yaml && touch config/routes.yaml

mv docker-compose.yml.base docker-compose.yml
mv config/services.yaml.base config/services.yaml

composer remove psr/http-message psr/http-factory guzzlehttp/guzzle symfony/psr-http-message-bridge
