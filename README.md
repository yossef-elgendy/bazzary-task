# Bazzary Magento 2 Project

A Magento 2.4.8-p3 development environment using [Mark Shust's Docker configuration](https://github.com/markshust/docker-magento) with the default Luma theme.

## Quick Start

### Prerequisites

- **Docker Desktop** (Mac/Windows) or **Docker Engine** (Linux)
- **Git** for version control
- **Composer** (optional, for local development)

### Initial Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd bazzary-task
   ```

2. **Create environment files**
   ```bash
   # Copy environment templates
   cp env/db.env.example env/db.env
   cp env/magento.env.example env/magento.env
   cp env/phpfpm.env.example env/phpfpm.env
   cp env/redis.env.example env/redis.env
   cp env/rabbitmq.env.example env/rabbitmq.env
   cp env/opensearch.env.example env/opensearch.env

   # Edit the files with your own values
   # IMPORTANT: Change passwords in db.env, magento.env, and rabbitmq.env
   ```

3. **Configure Composer authentication for Magento Marketplace**
   ```bash
   # Set up composer authentication with your Magento Marketplace credentials
   bin/composer config --global http-basic.repo.magento.com <public_key> <private_key>
   ```
   > **Note**: Replace `<public_key>` and `<private_key>` with your actual Magento Marketplace credentials from [Magento Marketplace](https://marketplace.magento.com/customer/accessKeys/).

4. **Start the Docker environment**
   ```bash
   docker compose up --build --force-recreate -d
   ```

5. **Copy files to container and install dependencies**
   ```bash
   bin/copytocontainer --all
   bin/composer install
   ```

6. **Create required directories in the container**
   ```bash
   # Create media directories for custom modules
   bin/cli mkdir -p /var/www/html/pub/media/specialoffers/tmp
   bin/cli chmod -R 777 /var/www/html/pub/media/specialoffers
   ```

7. **Install Magento**
   ```bash
   bin/setup-install magento.test
   bin/magento setup:upgrade
   bin/magento setup:di:compile
   bin/magento setup:static-content:deploy -f
   bin/magento cache:flush
   ```

8. **Copy nginx configuration**
   ```bash
   docker cp src/nginx.conf.sample bazzary-task-app-1:/var/www/html/nginx.conf
   docker exec bazzary-task-app-1 nginx -s reload
   ```

9. **Add domain to /etc/hosts**
   ```bash
   sudo nano /etc/hosts

   # Add the following entry:
   127.0.0.1 magento.test
   ```

10. **Setup SSL certificate**
    ```bash
    bin/setup-ssl magento.test
    bin/restart
    ```

11. **Access your site**
    - **Storefront**: `https://magento.test`
    - **Admin Panel**: `https://magento.test/admin`
    - **Admin credentials**: As configured in `env/magento.env`

## Complete Setup Checklist for New Developers

- [ ] Docker Desktop installed and running
- [ ] Git repository cloned
- [ ] Environment files created from `.example` templates
- [ ] Environment files edited with secure passwords
- [ ] Composer authentication configured for Magento Marketplace
- [ ] Containers started (`docker compose up -d`)
- [ ] Files copied to container (`bin/copytocontainer --all`)
- [ ] Composer dependencies installed (`bin/composer install`)
- [ ] Media directories created in container (see step 6)
- [ ] Magento setup completed (`bin/setup-install`)
- [ ] Nginx configuration copied to container
- [ ] Domain added to `/etc/hosts`
- [ ] SSL certificate generated (`bin/setup-ssl`)
- [ ] Site accessible at `https://magento.test`

## Common Issues

### 403 Forbidden Error
If you get a 403 Forbidden error when accessing the site:
```bash
docker cp src/nginx.conf.sample bazzary-task-app-1:/var/www/html/nginx.conf
docker exec bazzary-task-app-1 nginx -s reload
```

### Permission Issues
```bash
bin/fixowns
bin/fixperms
```

### Module Files Not Syncing
After creating or modifying module files:
```bash
bin/copytocontainer app/code
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

## Development Commands

### Essential Commands

```bash
# Start/stop containers
bin/start
bin/stop
bin/restart

# Access container shell
bin/bash

# Run Magento CLI commands
bin/magento <command>

# Clear cache
bin/magento cache:flush

# View logs
bin/log
```

### Database Operations

```bash
# Access MySQL
bin/mysql

# Backup database
bin/mysqldump > backup.sql

# Restore database
bin/mysql < backup.sql
```

### Development Tools

```bash
# Code quality checks
bin/phpcs
bin/phpcbf

# Static analysis
bin/analyse

# Run tests
bin/test

# Enable/disable Xdebug
bin/xdebug debug
bin/xdebug off
```

## Project Structure

```
bazzary-task/
├── bin/                    # Docker helper scripts
├── env/                    # Environment configuration
│   ├── *.env.example      # Template files (committed)
│   └── *.env              # Actual config (gitignored)
├── src/                    # Magento source code
│   └── app/code/          # Custom modules (committed)
├── compose.yaml           # Main Docker Compose configuration
├── compose.dev.yaml       # Development overrides
├── Makefile              # Convenient command shortcuts
├── CLAUDE.md             # AI assistant guidance
└── README.md             # This file
```

## Custom Modules

### Vendor_SpecialOffers

This project includes a custom module for managing special offers:

- **Admin Panel**: Content > Special Offers > Manage Offers
- **Features**:
  - Add/edit/delete special offers
  - Image upload support
  - Active/inactive status
  - Frontend slider widget on homepage

#### Required Container Directories
```bash
bin/cli mkdir -p /var/www/html/pub/media/specialoffers/tmp
bin/cli chmod -R 777 /var/www/html/pub/media/specialoffers
```

## Docker Services

| Service | Image | Ports |
|---------|-------|-------|
| app (nginx) | markoshust/magento-nginx:1.24-0 | 80, 443 |
| phpfpm | markoshust/magento-php:8.3-fpm-4 | - |
| db (mariadb) | mariadb:11.4 | 3306 |
| redis | valkey:8.1-alpine | 6379 |
| opensearch | markoshust/magento-opensearch:2.12-0 | 9200, 9300 |
| rabbitmq | markoshust/magento-rabbitmq:4.1-0 | 5672, 15672 |
| mailcatcher | sj26/mailcatcher:v0.10.0 | 1080 |
| phpmyadmin | linuxserver/phpmyadmin | 8080 |

## Frontend Development

### Theme Development

1. **Create a new theme** in `src/app/design/frontend/VendorName/theme-name`
2. **Set up Grunt** for live reload:
   ```bash
   bin/setup-grunt
   bin/grunt watch
   ```

## Monitoring & Debugging

### Logs
```bash
# View all logs
bin/log

# View specific log files
bin/log system.log
bin/log exception.log
```

### Xdebug
```bash
bin/xdebug debug    # Enable
bin/xdebug off      # Disable
bin/xdebug status   # Check status
```

## Security

### SSL Certificates
```bash
bin/setup-ssl magento.test
bin/setup-ssl-ca
```

### Authentication
- Update `env/magento.env` with secure admin credentials
- Change default database passwords in `env/db.env`
- Never commit actual `.env` files (use `.example` templates)

## Deployment

### Production Considerations

1. **Set proper file permissions**:
   ```bash
   bin/fixperms
   ```

2. **Enable production mode**:
   ```bash
   bin/magento deploy:mode:set production
   ```

3. **Compile and deploy**:
   ```bash
   bin/magento setup:di:compile
   bin/magento setup:static-content:deploy -f
   ```

## Additional Resources

- [Mark Shust's Docker Magento Documentation](https://github.com/markshust/docker-magento)
- [Magento 2 Developer Documentation](https://devdocs.magento.com/)
- [Magento 2 Frontend Development Guide](https://devdocs.magento.com/guides/v2.4/frontend-dev-guide/bk-frontend-dev-guide.html)

---

For questions or support, please open an issue in the repository.
