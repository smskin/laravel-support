#!/usr/bin/env bash

docker compose build
docker-compose up -d php-cli
docker-compose exec php-cli bash
