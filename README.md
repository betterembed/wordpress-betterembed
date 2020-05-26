# BetterEmbed for WordPress

**Free yourself from third party crap.**

## Installation

### [Composer](https://getcomposer.org/) (recommended)

Since the plugin is currently still under development you can only install it with composer.

Please consult the [Composer documentation](https://getcomposer.org/doc/05-repositories.md#loading-a-package-from-a-vcs-repository)
on how to require from github.

Later this plugin will probably be published as a package on packagist as `betterembed/wordpress-betterembed` as well as
on wordpress.org.

If you just want to quickly try it have a look at the "Development/Quick Start" section.

## Development

For development, it is recommended to use the included [Docker Compose](https://docs.docker.com/compose/) setup.

It offers a quick and easy way to launch a complete development environment.

### Quick start

To get yourself up and running quick just run the following command:

    docker-compose up -d

### Installing dependencies

     docker-compose run --rm composer install
     docker-compose run --rm scripts npm install

### Building assets (JS, CSS)

This plugin uses the [`@wordpress/scripts`](https://github.com/WordPress/gutenberg/tree/master/packages/scripts)
package for development.

You can watch changes and automatically rebuild using the [`start` script](https://github.com/WordPress/gutenberg/blob/master/packages/scripts/README.md#start):

     docker-compose run --rm scripts npm run start

To run some checks on the codebase (e.g. linting) and build everything in a production ready way:

     docker-compose run --rm scripts npm run build

### Coding standards

This plugins coding standards are based on the [WordPress Coding Standards for PHP_CodeSniffer](https://github.com/WordPress/WordPress-Coding-Standards)
with some adaptions as can be seen in the `phpunit.xml.dist` file.

You can automatically check your code style:

     docker-compose run --rm composer run-script phpcs

A lot of errors can even be fixed automatically:

     docker-compose run --rm composer run-script phpcbf

### Running Tests with PHP Unit

YOu can run tests for the PHP code using PHP Unit:

     docker-compose run --rm tests

## License

GPL 2.0 or later
