FROM php:8.4-cli AS base

FROM oven/bun:latest AS builder
WORKDIR /build

# Install Bun packages
COPY package.json bun.lock ./
RUN bun install

# Copy resource files
COPY ./resources ./resources
COPY vite.config.ts tsconfig.json ./
RUN bun run build

FROM base AS runner
WORKDIR /app

# Install PHP extensions and Composer
RUN docker-php-ext-install pdo pdo_mysql \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy application source
COPY app ./app
COPY config ./config
COPY public ./public
COPY resources/views ./resources/views
COPY routes ./routes
COPY src ./src

# Copy built frontend assets
COPY --from=builder /build/public/build ./public/build

# Create cache directory for views
RUN mkdir -p .cache/views

# Expose port 8000
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
