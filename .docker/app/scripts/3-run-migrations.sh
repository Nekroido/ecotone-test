#!/bin/bash

set -e

if [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Migrating" \
    && bin/console d:m:migrate --no-interaction
else
    echo "Not migrating" \
; fi
