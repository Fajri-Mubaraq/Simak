FROM serversideup/php:8.3-fpm-nginx

# Switch to root to configure scripts and directory permissions
USER root

# Copy application code into the default web directory
COPY --chown=webuser:webuser . /var/www/html

# Run composer install to install production dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Ensure storage and bootstrap cache permissions are set correctly
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure storage and bootstrap cache permissions are set correctly
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R webuser:webuser /var/www/html/storage /var/www/html/bootstrap/cache

# Create a runtime startup script to run migrations automatically when the container boots up
RUN echo '#!/bin/sh' > /etc/entrypoint.d/99-migrate.sh && \
    echo 'echo "Running migrations..."' >> /etc/entrypoint.d/99-migrate.sh && \
    echo 'php artisan migrate --force' >> /etc/entrypoint.d/99-migrate.sh && \
    chmod +x /etc/entrypoint.d/99-migrate.sh

# Switch back to webuser for secure production execution
USER www-data