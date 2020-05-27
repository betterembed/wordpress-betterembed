#!/bin/bash

#Call default entrypoint
docker-entrypoint.sh apache2
#Chown wp-content to apache user to make it writeable for e.g. languages
chown www-data:www-data /var/www/html/wp-content
chown www-data:www-data /var/www/html/wp-content/plugins
apache2-foreground
