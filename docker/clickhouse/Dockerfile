FROM yandex/clickhouse-server

LABEL maintainer="vagif@rufullazada.me"

COPY init.sh /docker-entrypoint-initdb.d
RUN chmod 0755 /docker-entrypoint-initdb.d/init.sh