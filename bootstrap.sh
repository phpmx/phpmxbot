#!/usr/bin/env bash

echo "Testing for config.ini..."
if [ ! -f "config.ini" ]; then
	echo "No config.ini found, creating from template..."
	cp config.example.ini config.ini
fi

echo "Testing for .env..."
if [ ! -f ".env" ]; then
	echo "No .env found, creating from template..."
	cp .env.example .env
fi

echo "Testing for db/phpmxbot.db..."
if [ ! -f "db/phpmxbot.db" ]; then
	echo "No db/phpmxbot.db found, running initial migration..."
	./migrate.sh
fi

echo "Testing for vendor..."
if [ ! -d "vendor" ]; then
	echo "No vendor found, running composer install..."
	docker run --rm -ti -v $PWD:/app composer install
fi

echo "Done!"
