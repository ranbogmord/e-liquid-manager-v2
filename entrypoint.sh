#!/usr/bin/env bash

if [[ "$CONSUL_NODE_IP" ]]; then
    echo "Joining Consul Cluster $CONSUL_NODE_IP"

    consul-template \
        -consul-addr="$CONSUL_NODE_IP:${CONSUL_NODE_PORT:-8500}" \
        -template "/code/.env.tpl:/code/.env" &
fi

cd /code/public
echo "Starting application"
/usr/local/bin/php -S 0.0.0.0:8000
