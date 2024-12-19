#!/bin/bash

set -e

# Wait for the database to be ready
wait-for-it app-db:3306 -t 60

# Wait for RabbitMQ to be ready
wait-for-it rabbitmq:5672 -t 60

sleep 3
