#!/usr/bin/env bash

if [ ! -f "config.ini" ]; then
	cp config.example.ini config.ini
fi

if [ ! -f ".env" ]; then
	cp .env.example .env
fi

if [ ! -f "db/phpmxbot.db" ]; then
	stty_orig=$(stty -g)
	stty -echo
	IFS= read -s -p "SQLite Password for 'admin' user: " passwd
	stty "$stty_orig"
	echo ""
	docker run --rm -ti -v $PWD:/app -w /app php:7.4-cli-alpine php db/bootstrap.php "$passwd"
fi

if [ ! -d "vendor" ]; then
	docker run --rm -ti -v $PWD:/app composer install
fi

docker-compose up -d
