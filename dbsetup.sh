#!/bin/bash

composer dump-autoload
../../../artisan migrate:reset
rm -f ../../../database/stubdb.sqlite
rm -f ../../../database/database.sqlite
touch ../../../database/stubdb.sqlite
../../../artisan migrate --database=setuptest --path=packages/inoplate/account/database/migrations
cp ../../../database/stubdb.sqlite ../../../database/database.sqlite