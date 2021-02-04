# Initialization
- Git clone repository
- Copy .env.example -> .env
- Add for user:**root** the role:**admin**


### Requirements
- Download **docker** and **docker-compose** for initialization
- **make** executable

*Executing docker as regular user:* **(for linux only)**
```
sudo groupadd docker
sudo usermod -aG docker ${USER}
su -s ${USER}

For testing: docker --version
```

Execute command: 
```
make
```

### Domains
- https://rss.az -> frontend/web
- https://api.rss.az -> api/web
- https://cabinet.rss.az -> cabinet/web
- https://admin.rss.az -> admin/web
- https://static.rss.az -> uni-cropper

## Manual for launching clickhouse-server manually and fix columnt problem

systemctl stop clickhouse-server
mkdir /run/clickhouse-server && chmod 777 /run/clickhouse-server
sudo -u clickhouse /usr/bin/clickhouse-server --config=/etc/clickhouse-server/config.xml --pid-file=/run/clickhouse-server/clickhouse-server.pid
systemctl start clickhouse-server
