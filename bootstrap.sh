#!/usr/bin/env bash

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
	docker run --rm -ti -v $PWD:/app composer:2.0.8 install
fi

echo "Done!"
