local:
	touch database/database.sqlite;
	php composer.phar install;
	php artisan serve;
	
token:
	php artisan tinker --quiet --execute="echo \App\User::first(['api_token'])->toJson() . PHP_EOL;" 
