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
	echo "No db/phpmxbot.db found, creating from template..."

	# Disable terminal echoing to avoid showing the password
	stty_orig=$(stty -g)
	stty -echo

	# Ask for a password
	IFS= read -s -p "SQLite Password for 'admin' user: " passwd

	# Restore terminal echoing
	stty "$stty_orig"
	echo ""

	# Generate a new SQLite from SQL template
	docker run --rm -ti -v $PWD:/app -w /app php:7.4-cli-alpine php db/bootstrap.php "$passwd"
fi

echo "Testing for vendor..."
if [ ! -d "vendor" ]; then
	echo "No vendor found, running composer install..."
	docker run --rm -ti -v $PWD:/app composer install
fi

echo "Done!"
