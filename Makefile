local:
	touch database/database.sqlite;
	php composer.phar install;
	php artisan serve;
