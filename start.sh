#!/usr/bin/env bash

cd _docker
sudo docker-compose up -d nginx postgres_crypto_app phpmyadmin laravel-horizon redis redis-webui
