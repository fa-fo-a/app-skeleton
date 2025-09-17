#!/bin/bash

rm src/UI/Http/Controller/SumTwoPositiveNumbersController.php
rm -r src/UI/Http/Factory
rm -r src/Infrastructure/ArgumentResolver src/Infrastructure/Generator
rm .env
rm -r config/doctrine config/packages
rm -r src/Core/Entity src/Core/Factory src/Core/Generator src/Core/Saver src/Core/Exception
rm -r src/UseCase/DTO src/UseCase/Exception src/UseCase/Validator
rm src/Infrastructure/Persistence/ResultSaver.php
rm src/Infrastructure/Persistence/Migrations/*

rm -r tests/Core/Factory
rm -r tests/TestDouble/Core/Generator tests/TestDouble/Core/Factory tests/TestDouble/Core/Saver
rm tests/Acceptance/*
rm tests/Infrastructure/Persistence/*
rm tests/UseCase/*
rm src/UseCase/*

rm config/routes.yaml && touch config/routes.yaml

mv docker-compose.yml.base docker-compose.yml
mv config/services.yaml.base config/services.yaml

composer remove psr/http-message psr/http-factory guzzlehttp/guzzle symfony/psr-http-message-bridge symfony/uid symfony/monolog-bundle doctrine/dbal doctrine/doctrine-bundle doctrine/doctrine-migrations-bundle doctrine/orm
composer remove --dev symfony/browser-kit

rm test_request.sh

mv config/bundles.php.dist config/bundles.php
