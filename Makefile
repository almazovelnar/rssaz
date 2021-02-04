all: up composer init_project migrate_clickhouse migrate_mysql roles

up:
	@echo "Bringing up all containers"
	docker-compose up -d
composer:
	@echo "Installing dependencies"
	docker-compose exec php composer install --no-cache
init_project:
	docker-compose exec php php init-project
migrate_clickhouse:
	@echo "Creating tables in ClickHouse"
	docker-compose exec php php yii click-house/migrate
	docker-compose exec php php yii click-house/alter
migrate_mysql:
	@echo "Creating tables in MySql"
	docker-compose exec php php yii migrate
roles:
	@echo "Configuring roles and permissions"
	docker-compose exec php php yii rbac/init
	docker-compose exec php php yii roles/assign




