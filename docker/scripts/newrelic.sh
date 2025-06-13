#!/bin/bash

set -v
if [ -n "$NEW_RELIC_LICENSE_KEY" ]; then

   curl -L https://download.newrelic.com/php_agent/archive/${NEW_RELIC_AGENT_VERSION}/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux.tar.gz | tar -C /tmp -zx;

      export NR_INSTALL_USE_CP_NOT_LN=1
      export NR_INSTALL_SILENT=1
      /tmp/newrelic-php5-${NEW_RELIC_AGENT_VERSION}-linux/newrelic-install install
      rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

   find /etc /usr/local/etc -type f -name newrelic.ini \
      -exec sed -i \
          -e "s/REPLACE_WITH_REAL_KEY/${NEW_RELIC_LICENSE_KEY}/" \
          -e "s/newrelic.appname[[:space:]]=[[:space:]].*/newrelic.appname = \"${NEW_RELIC_APP_NAME}\"/" \
          -e '$anewrelic.daemon.address="127.0.0.1:31339"' {} \;
fi
