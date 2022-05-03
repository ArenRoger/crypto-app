#!/usr/bin/env bash

cd _docker
sudo docker-compose up -d nginx mysql phpmyadmin laravel-horizon redis redis-webui
