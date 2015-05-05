install:
	composer install
	php ./app/check.php
	cd node/ && npm install && cd ../
	bower install

tests:
	./bin/phing -f app/build.xml
	./bin/phpunit -c app/