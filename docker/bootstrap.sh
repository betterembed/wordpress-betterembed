#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
ORANGE='\033[0;33m'
NC='\033[0m' # No Color

for i in {1..15}
do
    if wp db check > /dev/null 2>&1
    then
        break
    fi

    if test $i -eq 1
    then
        echo -e "${GREEN}Waiting for up to 15 seconds for DB to be ready.${NC}"
    fi

    sleep 1
    echo -n .

    if test $i -eq 15
    then
        echo -e " ${RED}Timed out waiting for database.${NC}"
        exit 1
    fi
done

if ! wp core is-installed;
then
    echo
    wp core install --url=localhost:8080 --title=BetterEmbed --admin_user=wp --admin_password=wp --admin_email=betterembed@local.test --skip-email
    wp plugin activate betterembed
    wp plugin install wordpress-importer --activate
    wp import docker/demo-content.xml --authors=create
    wp option update permalink_structure "/%postname%/" --quiet
    wp rewrite flush --hard  --quiet
    echo -e "${GREEN}-------------------------------------------------------------------------------${NC}"
    echo -e "${GREEN} All ready! ${NC}"
    echo -e "${GREEN} You can now open the demo page at http://localhost:8080/better-embed-demo/    ${NC}"
    echo -e "${GREEN} Or you can log in with user/password wp/wp http://localhost:8080/wp-login.php ${NC}"
    echo -e "${GREEN}-------------------------------------------------------------------------------${NC}"
else
    echo -e "${ORANGE}---------------------------------------------------------------${NC}"
    echo -e "${ORANGE} Already installed. Consult the documentation on how to reset. ${NC}"
    echo -e "${ORANGE}---------------------------------------------------------------${NC}"
fi
