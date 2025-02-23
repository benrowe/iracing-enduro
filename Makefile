PWD := $(dir $(MAKEPATH))
VHOST := $$(cat $(PWD).env | awk '{matched=0}/NGINX_VIRTUAL_HOST=/ {matched=1; print $$1}' | awk -F= '{print $$NF}')
SECURE="false"

up:
	docker compose up
upd:
	docker compose up -d
ssh:
	docker compose exec workspace bash

rebuild:
	docker builder prune && \
	docker compose down --remove-orphans && \
	docker compose build

prod-build:
	docker build --target release -t release-bot:latest -f env/docker/php/Dockerfile .
