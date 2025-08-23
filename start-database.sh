#!/bin/bash

source ./.env

docker run --name hatshepsut-db \
  -e MYSQL_ROOT_PASSWORD="$DB_PASSWORD" \
  -e MYSQL_DATABASE="$DB_NAME" \
  -e MYSQL_USER="$DB_USER" \
  -e MYSQL_PASSWORD="$DB_PASSWORD" \
  -p "$DB_PORT":3306 \
  -d mysql:latest
