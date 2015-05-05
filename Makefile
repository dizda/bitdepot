install:
	composer install
	php ./app/check.php
	cd node/ && npm install && cd ../
	bower install
	php app/console doctrine:database:create -e prod
	php app/console doctrine:schema:update --force -e prod
	node ./node/create_wallet.js

tests:
	./bin/phing -f app/build.xml
	./bin/phpunit -c app/