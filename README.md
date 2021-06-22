#NGINX
 - client_max_body_size 100M;

#NOTE
 - git clone git@github.com:winex01/hris.git
 - cp .env-example .env
 - composer install
 - sudo chown -R www-data:www-data storage/ 
 - sudo chown -R www-data:www-data bootstrap/cache/
 - php artisan migrate:fresh
 - php artisan db:seed
 - php artisan storage:link 
 - ex. how to run my built in artisan command: php artisan winex:factories 50 --priority=Employee