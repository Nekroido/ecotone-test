#!/bin/bash

set -e

if [ "$APP_INSTALL_DEPENDENCIES" = "yes" ]; then
    echo "Setting up projections" \
    && bin/console ecotone:es:initialize-projection examples
else
    echo "Not Setting up projections" \
; fi
