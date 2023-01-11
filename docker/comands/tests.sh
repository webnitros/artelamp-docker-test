#!/bin/bash

source "./.env"
NAME=${COMPOSE_PROJECT_NAME}-node

docker exec -ti $(docker ps --filter name=$NAME -q) npm -c " run test"
