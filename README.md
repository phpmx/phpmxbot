# phpmx/phpmxbot

A Slack bot for the [PHP Mexico](https://phpmexico.mx) Community.

This project, built by the community and for the community, uses the following languages, tools, and services:

- [PHP 7.4](https://php.net)
- [SQLite3](https://sqlite.org/)
- [Botman](https://botman.io)
- [Slack](https://slack.com)
- [Docker](https://www.docker.com)
- [ngrok](https://ngrok.com)
- [Adminer](https://www.adminer.org)
- [Flyway](https://flywaydb.org/)

You can start contributing to this project by following the Quick Start guide below, or read the [Wiki](https://github.com/phpmx/phpmxbot/wiki) for a more detailed description and documentation.

## Quick Start

1. Clone this repo in your computer.
1. Run `./bootstrap.sh`
1. Start the local bot and ngrok processes with `docker-compose up`.
1. (Only the first time) [Setup your Slack Application](https://github.com/phpmx/phpmxbot/wiki/Slack-setup).

## Contribute

Please feel free to submit pull requests or open issues.

## Code of Conduct

Help us keep this project open and inclusive. Please read and follow our [Code of Conduct](CODE_OF_CONDUCT.md).

## Run tests

Once the container of bot it's running on background please type the next command:

`docker-compose exec bot ../vendor/bin/phpunit ../tests`