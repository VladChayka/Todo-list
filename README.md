### Software Stack
- Docker 24.0.6
- PHP 8.2
- PostgreSQL 15
- Laravel 10.10
- Swagger Api Documentation


### Base Settings
1. Run git clone https://github.com/VladChayka/Todo-list.git
2. Run cp .env.example .env
3. Set up your settings in .env:
    - NGINX_CONTAINER_PORT, CURRENT_UID
    - APP_URL
4. Run docker compose build
5. Run docker compose up -d
6. Run docker exec -it drum-n-code-app bash
7. Run composer install
8. Run php artisan migrate
9. Run php artisan passport:install
10. Run php artisan db:seed
