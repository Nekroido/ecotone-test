#!/bin/bash

set -e

/entrypoint.d/1-wait-4-services.sh
#/entrypoint.d/2-fix-permissions.sh
#/entrypoint.d/3-run-migrations.sh
/entrypoint.d/4-setup-projections.sh
