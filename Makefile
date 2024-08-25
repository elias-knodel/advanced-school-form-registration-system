# === Docker ===
# =====================================
# Development environment
DOCKER_COMPOSE ?= docker compose

.PHONY: build
build:
	$(DOCKER_COMPOSE) build

.PHONY: up
up:
	$(DOCKER_COMPOSE) up -d

.PHONY: stop
stop:
	$(DOCKER_COMPOSE) stop

# === API / Symfony ===
# =====================================
# Backend management
.PHONY: api/shell
api/shell:
	$(DOCKER_COMPOSE) exec api bash

.PHONY: api/migration
api/migration:
	$(DOCKER_COMPOSE) exec api bin/console make:migration

# === Database ===
# =====================================
# DB Commands
.PHONY: db/drop
db/drop:
	$(DOCKER_COMPOSE) exec api bin/console doctrine:database:drop --force

.PHONY: db/create
db/create:
	$(DOCKER_COMPOSE) exec api bin/console doctrine:database:create

.PHONY: db/migrate
db/migrate:
	$(DOCKER_COMPOSE) exec api bin/console doctrine:migrations:migrate --no-interaction

