#!/usr/bin/env bash
# WARNING: destroys DB volume for a clean WP install
docker compose -f infra/docker-compose.yml down -v
rm -rf wp-content/*
mkdir -p wp-content
docker compose -f infra/docker-compose.yml up -d
