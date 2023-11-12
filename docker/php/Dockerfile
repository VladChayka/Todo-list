FROM php:8.2.7-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    git \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libonig-dev \
    libxml2-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
   libpq-dev \
    zlib1g-dev \
    libzip-dev \
    libz-dev \
    supervisor \
    sudo


# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

# Install PHP extensions
RUN docker-php-ext-install intl pdo mysqli pdo_mysql pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip gettext
# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
#RUN useradd -G www-data,root -u $uid -d /home/$user $user
#RUN mkdir -p /home/$user/.composer &&
#    chown -R $user:$user /home/$user

ARG CURRENT_UID
# Add user for laravel application
RUN groupadd -g ${CURRENT_UID} backend
RUN useradd -u ${CURRENT_UID} -ms /bin/bash -g backend backend

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=${CURRENT_UID}:${CURRENT_UID} . /var/www

# Change current user to www
USER backend
# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

