FROM ghcr.io/maymeow/php-ci-cd/php-ci-cd:8.0.12-cs-git

# arguments in docker-compose file
ARG user=vscode
ARG uid=1000

RUN echo "memory_limit=1024M" >> /usr/local/etc/php/conf.d/docker-php-memory-limit.ini

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

USER $user

EXPOSE 8765
