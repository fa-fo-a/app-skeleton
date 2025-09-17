#!/bin/bash

rm src/UI/Http/Controller/SumTwoPositiveNumbersController.php
rm -r src/UI/Http/Factory
rm -r src/Infrastructure/ArgumentResolver src/Infrastructure/Generator
rm .env.test .env
rm -r config/doctrine
rm -r src/Core/Entity src/Core/Factory src/Core/Generator src/Core/Saver src/Core/Exception
rm -r src/UseCase/DTO src/UseCase/Exception src/UseCase/Validator
rm src/UseCase/SumTwoPositiveNumbersHandler.php

rm -r tests/Core/Factory
rm -r tests/TestDouble/Core/Generator tests/TestDouble/Core/Factory tests/TestDouble/Core/Saver

rm config/routes.yaml && touch config/routes.yaml

mv docker-compose.yml.base docker-compose.yml
mv config/services.yaml.base config/services.yaml

composer remove psr/http-message psr/http-factory guzzlehttp/guzzle symfony/psr-http-message-bridge symfony/orm-pack symfony/uid symfony/browser-kit symfony/monolog-bundle

rm test_request.sh
