# CRYPTO APP

## START

1.  Перейти в папку `_docker`, выполнить `cp .env.example .env`
2. Сгенерировать пароль для DB (юзеров `root` и тд) на [сервисе](http://www.sha1-online.com/), подставив `_docker/.env` вместо в `MYSQL_PASSWORD`, `MYSQL_ROOT_PASSWORD`, `PMA_PASSWORD` и `PMA_ROOT_PASSWORD`, а также занеся в `.env`-файл laravel (позже)
3. Выбрать `DATA_PATH_HOST`, который раньше не юзался, иначе ошибка будет (для *windows* аля `D:/.laradock/weather_app_data`)
4. \[Only Windows\] установить `COMPOSE_PATH_SEPARATOR` в `;`
5. При помощи упомянутого выше сервиса сгенерировать пароль и вставить в `.env-файл докера` переменную `REDIS_WEBUI_PASSWORD`
6. Перейти в папку `_docker/nginx/sites`, выполнить `cp api.conf.example api.conf`
7. Если это `production`, то заменить пути к сертификатам
8. Перейти в папку `_docker/laravel-horizon/supervisord.d` и выполнить `cp laravel-horizon.conf.example laravel-horizon.conf`
9. docker-compose up -d nginx mysql phpmyadmin laravel-horizon redis redis-webui (и `certbot` для продакшена)

\* при необходимости xdebug - нужно поставит true `WORKSPACE_INSTALL_XDEBUG` и `PHP_FPM_INSTALL_XDEBUG`.

### First setup
Войти в **workspace**-контейнер (`docker-compose exec workspace bash`) и выполнить команды:
```
composer install
cp .env.example .env
php artisan key:generate
php artisan passport:keys
chmod -R 777 storage bootstrap/cache
php artisan storage:link

[optional]:
php artisan migrate --seed
```  

Дальше необходимо повставлять пароли от БД, сервисов и установить различные настройки в `.env`-файле.

БД можно развернуть залив дамп через phpmyadmin.

## Администрирование
- phpmyadmin: {IP}:9999
- redis admin: {IP}:9987
- horizon (queue): /horizon

#### Полезно для schema
Чтобы заносить сразу в бд схемы, удобно использовать [этот сайт](https://onlinetexttools.com/json-stringify-text)   


