install:
	composer install
	php ./app/check.php
	cd node/ && npm install && cd ../
	bower install
	php app/console -e prod doctrine:database:create
	node ./node/create_wallet.js

tests:
	./bin/phing -f app/build.xml
	./bin/phpunit -c app/