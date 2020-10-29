# phpmx/phpmxbot

A Slack bot for the [PHP Mexico](https://phpmexico.mx) Community.

This project, built by the community and for the community, uses the following languages, tools, and services:

- [PHP 7.4](https://php.net)
- [SQLite3](https://sqlite.org/)
- [Botman](https://botman.io)
- [Slack](https://slack.com)
- [Docker](https://www.docker.com)
- [ngrok](https://ngrok.com)

You can start contributing to this project by following the Quick Start guide below, or read the [Wiki](wiki) for a more detailed description and documentation.

## Quick Start

1. Clone this repo in your computer.
1. Create a Slack Application.
1. Duplicate `config.example.ini` and rename it as `config.ini`.
1. Update the token value in `config.ini` with your Slack Bot token.
1. Run the following command: `php db/bootstrap.php your_admin_password` where `your_admin_password` is a password for the `admin` user.
1. Start the local bot and ngrok processes with `docker-compose up`.

## Contribute

Please feel free to submit pull requests or open issues.

## Code of Conduct

Help us keep this project open and inclusive. Please read and follow our [Code of Conduct](CODE_OF_CONDUCT.md).
