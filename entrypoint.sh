#!/usr/bin/env bash

trap "pkill consul-template consul; exit;" 1 2 3 15

if [[ "$CONSUL_NODE_IP" ]]; then
    echo "Joining Consul Cluster $CONSUL_NODE_IP"
    nohup consul agent \
        -join="$CONSUL_NODE_IP" \
        -config-dir=/consul/config \
        -data-dir=/consul/data &

    sleep 5

    nohup consul-template \
        -template "/code/.env.tpl:/code/.env" &
fi

cd /code/public
echo "Starting application"
php -S 0.0.0.0:8000
