FROM php:8.3-apache

# =================================================
# APACHE
# =================================================

RUN a2enmod rewrite

# =================================================
# SYSTEM PACKAGES
# =================================================

RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    zip \
    ca-certificates \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# =================================================
# PHP EXTENSIONS
# =================================================

RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql

# =================================================
# COMPOSER
# =================================================

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# =================================================
# NODE.JS 22 + NPM
# =================================================

RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key \
    | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main" \
    > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# =================================================
# WORKDIR
# =================================================

WORKDIR /var/www/html