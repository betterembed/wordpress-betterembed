# BetterEmbed for WordPress

**Free yourself from third party crap.**

## Installation

### Composer (recommended)

Since the plugin is currently still under development you can only install it with [Composer](https://getcomposer.org/)
with something similar to this:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/betterembed/wordpress-betterembed"
            }
        ],
        "require": {
            "betterembed/wordpress-betterembed": "*"
        }
    }
Please consult the [Composer documentation](https://getcomposer.org/doc/05-repositories.md#loading-a-package-from-a-vcs-repository)
on how to require from github.

Later this plugin will probably be published as a package on [Packagist](https://packagist.org/) as
`betterembed/wordpress-betterembed` as well as on [wordpress.org](https://wordpress.org).

If you just want to quickly try it have a look at the "Development/Quick Start" section.

## Development

For development, it is recommended to use the included [Docker Compose](https://docs.docker.com/compose/) setup.

It offers a quick and easy way to launch a complete development environment.

### Quick start

To get yourself up and running quick just run the following command:

    docker-compose run --rm wp bash docker/bootstrap.sh

If everything worked well you should see this line:

    -------------------------------------------------------------------------------
     All ready! ${NC}"
     You can now open the demo page at http://localhost:8080/better-embed-demo/
     Or you can log in with user/password wp/wp http://localhost:8080/wp-login.php
    -------------------------------------------------------------------------------

To stop the development environment run this command:

    docker-compose stop

To restart the development environment run this command:

    docker-compose up -d

To completely wipe the development environment including the database and uploads run this command:

    docker-compose down -v

### Installing dependencies

For local development you'll need to install dependencies:

     docker-compose run --rm composer install
     docker-compose run --rm scripts npm install

### Building assets (JS, CSS)

This plugin uses the [`@wordpress/scripts`](https://github.com/WordPress/gutenberg/tree/master/packages/scripts)
package for development.

You can watch changes and automatically rebuild using the [`start` script](https://github.com/WordPress/gutenberg/blob/master/packages/scripts/README.md#start):

     docker-compose run --rm scripts npm run start

To run some checks on the codebase (e.g. linting) and build everything in a production ready way:

     docker-compose run --rm scripts npm run build

You should never commit anything created by the start script, always run the above build script before commiting.

### Coding standards

This plugins coding standards are based on the [WordPress Coding Standards for PHP_CodeSniffer](https://github.com/WordPress/WordPress-Coding-Standards)
with some adaptions as can be seen in the `phpunit.xml.dist` file.

You can automatically check your code style:

     docker-compose run --rm composer run-script phpcs

A lot of errors can even be fixed automatically:

     docker-compose run --rm composer run-script phpcbf

### Running Tests with PHPUnit

To run the tests you need to initialize the testing instance environment once:

    docker-compose run --rm tests bash docker/bootstrap-tests.sh

After that you can run tests for the PHP code using PHPUnit:

    docker-compose run --rm tests

## License

GPL 2.0 or later
