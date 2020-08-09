include ./.env.local
export

local:
	touch database/database.sqlite;
	php composer.phar install;
	php -S 127.0.0.1:9000 ./server.php;
	
