#!/bin/bash

source "./.env"

rm -rf ../dumps/db.tar.gz
rm -rf ../dumps/db.sql
cp -r ../modx/_backup/db.tar.gz ../docker/dumps/db.tar.gz
tar -xf ./dumps/db.tar.gz
rm -rf ./dumps/db.tar.gz
mv ./db.sql ./dumps/db.sql
