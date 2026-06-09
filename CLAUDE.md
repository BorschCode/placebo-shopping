# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**placebo-shopping** is a Symfony 8.1 application simulating a fake e-commerce/food delivery experience (inspired by the South Korean "dopamine site" trend). Users can fill carts, mock-checkout, and track phantom deliveries — all without real money or goods. The goal is psychological relief through simulated consumption.

## Stack

- **Runtime**: [FrankenPHP](https://frankenphp.dev) (PHP 8.4/8.5) + Caddy (TLS, HTTP/3)
- **Framework**: Symfony 8.1
- **Database**: PostgreSQL 16 (via Doctrine ORM + Migrations)
- **Real-time**: Mercure (built into FrankenPHP)
- **Frontend**: Stimulus (via `symfony/stimulus-bundle`), Turbo (`symfony/ux-turbo`), AssetMapper
- **Tests**: PHPUnit 13
- **AI tooling**: `symfony/ai-mate` (dev dependency, MCP server via `vendor/bin/mate`)

## Development Commands

All commands run **inside the PHP container** unless noted.

```bash
# Start the stack (from host)
docker compose up -d --wait

# Open a shell in the container
docker compose exec php bash

# Symfony console
docker compose exec php bin/console <command>

# Run all PHPUnit tests
docker compose exec php bin/phpunit

# Run a single test file or filter
docker compose exec php bin/phpunit tests/path/To/Test.php
docker compose exec php bin/phpunit --filter testMethodName

# Run database migrations
docker compose exec php bin/console doctrine:migrations:migrate --no-interaction

# Create a new migration after entity changes
docker compose exec php bin/console doctrine:migrations:diff

# Validate Doctrine schema
docker compose exec php bin/console doctrine:schema:validate

# Clear cache
docker compose exec php bin/console cache:clear

# Install JS dependencies (ImportMap)
docker compose exec php bin/console importmap:install

# Composer (inside container)
docker compose exec php composer require <package>
docker compose exec php composer install
```

## Architecture

```
src/
  Controller/   # Symfony controllers (empty at init)
  Entity/       # Doctrine entities (empty at init)
  Repository/   # Doctrine repositories (empty at init)
  Kernel.php    # Symfony kernel

assets/
  controllers/  # Stimulus JS controllers
  styles/       # CSS

config/
  packages/     # Per-package Symfony config (doctrine, security, mailer, etc.)
  routes/       # Route imports
  services.yaml # DI service definitions

mate/           # symfony/ai-mate MCP extension
  config.php    # DI config for mate services
  extensions.php # Enabled mate extensions
  AGENT_INSTRUCTIONS.md

frankenphp/
  Caddyfile              # Caddy/FrankenPHP server config
  conf.d/                # PHP INI files (base, dev, prod)
  docker-entrypoint.sh
```

## Docker / Environment

- **Dev**: `compose.yaml` + `compose.override.yaml` — uses `frankenphp_dev` image with bind-mount, Xdebug, hot reload (`FRANKENPHP_WORKER_CONFIG=watch`).
- **Prod**: `compose.prod.yaml` — uses the multi-stage `frankenphp_prod` Dockerfile target (distroless-style slim Debian image).
- **Database service name**: `shopping-app-database` (not `database`) in `compose.yaml`.
- **Default ports**: HTTP 80, HTTPS 443, HTTP3 443/UDP. Overridable via `.env`.
- `var/` is excluded from the bind-mount in dev (anonymous volume) for faster I/O.

## AI Mate (MCP)

`symfony/ai-mate` is installed as a dev dependency and exposes MCP tools (e.g., `server-info`). Prefer MCP tools over raw CLI equivalents when available. After installing/removing mate extensions, run:

```bash
docker compose exec php vendor/bin/mate discover
```

## Dev Container

Opening in VS Code's Dev Container provides a sandboxed environment with an outbound firewall. If `composer require` or other network calls fail, add the domain to the `ipset=` line in `.devcontainer/init-firewall.sh` and rebuild the container.

## CI

GitHub Actions (`.github/workflows/ci.yaml`) runs two jobs:
- **tests**: builds Docker images, starts services, checks HTTP/Mercure reachability.
- **lint**: runs `super-linter` (actionlint + zizmor + others; Checkov and Trivy are disabled).
