# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Magento 2.4.8-p3 Community Edition** development environment using [Mark Shust's Docker Configuration](https://github.com/markshust/docker-magento) (v52.1.0). The Magento application lives in `src/` and is gitignored—only Docker configuration and helper scripts are version controlled.

## Common Commands

All commands use the Makefile which wraps `bin/` scripts:

### Container Management
```bash
make start                    # Start all containers
make stop                     # Stop containers
make restart                  # Restart containers
make status                   # Check container status
```

### Magento CLI
```bash
make magento [command]        # Run bin/magento commands
make cli [command]            # Run any command in phpfpm container
make bash                     # Interactive shell in container
```

### Code Quality
```bash
make phpcs [path]             # PHP CodeSniffer with Magento2 standard
make phpcbf [path]            # Auto-fix CodeSniffer issues
make analyse [path]           # PHPStan static analysis
```

### Testing
```bash
make test [path]              # Run PHPUnit tests
make mftf [args]              # Run Magento Functional Testing Framework
make setup-integration-tests  # Configure integration test environment
```

### Deployment
```bash
make deploy [locales]         # Deploy static content (defaults to en_US)
make fixowns                  # Fix filesystem ownership
make fixperms                 # Fix filesystem permissions
```

### Database
```bash
make mysql                    # MySQL CLI (uses env/db.env credentials)
make mysqldump                # Backup database
```

### Debugging
```bash
make xdebug debug             # Enable Xdebug debug mode
make xdebug off               # Disable Xdebug
make debug-cli [IDE_KEY]      # Enable Xdebug for CLI (default: PHPSTORM)
make log [file]               # Tail Magento logs (no args = all logs)
```

## Architecture

### Docker Services
- **app** (nginx:1.24) - Web server on ports 80/443
- **phpfpm** (php:8.3-fpm) - PHP processor
- **db** (mariadb:11.4) - Database on port 3306
- **redis** (valkey:8.1) - Cache/sessions on port 6379
- **opensearch** (2.12) - Search engine on port 9200
- **rabbitmq** (4.1) - Message queue on ports 5672/15672
- **mailcatcher** - Email testing on port 1080
- **phpmyadmin** - Database UI on port 8080 (dev only)

### File Structure
```
src/                          # Magento root (gitignored)
├── app/code/                 # Custom modules go here
├── app/design/               # Theme customizations
├── app/etc/                  # Configuration (env.php, config.php)
├── pub/                      # Public web root
├── var/                      # Logs, cache, generated files
└── vendor/                   # Composer dependencies

bin/                          # Docker helper scripts
env/                          # Environment configuration
compose.yaml                  # Main Docker Compose config
compose.dev.yaml              # Development overrides (adds phpmyadmin)
Makefile                      # Command shortcuts
```

### Caching Strategy
Redis databases: 0=default cache, 1=page cache, 2=sessions

## Custom Module Development

Create modules in `src/app/code/[Vendor]/[Module]/`:
```
Module/
├── registration.php
├── etc/
│   └── module.xml
├── Controller/
├── Model/
├── Block/
└── view/
```

After creating/modifying modules:
```bash
make magento setup:upgrade
make magento setup:di:compile
make magento cache:flush
```

## Environment Configuration

Database credentials and other settings are in `env/` directory:
- `env/db.env` - Database connection (magento/magento)
- `env/phpfpm.env` - PHP-FPM settings
- `env/opensearch.env` - Search configuration
- `env/rabbitmq.env` - Queue configuration

## Access Points

- Storefront: https://magento.test (or configured domain)
- Admin: https://magento.test/admin
- phpMyAdmin: http://localhost:8080
- RabbitMQ UI: http://localhost:15672
- Mailcatcher: http://localhost:1080
