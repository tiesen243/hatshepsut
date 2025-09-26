FROM php:8.4-cli AS base

FROM oven/bun:latest AS builder
WORKDIR /build

# Install Bun packages
COPY package.json bun.lock ./
RUN bun install

# Copy resource files
COPY ./resources ./resources
COPY vite.config.ts ./
COPY tsconfig.json ./
RUN bun run build

FROM base AS runner
WORKDIR /app

# Install Mysql extension
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Copy composer files and install dependencies
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy application files
COPY app ./app
COPY config ./config
COPY public ./public
COPY resources/views ./resources/views
COPY routes ./routes
COPY src ./src

# Copy built files from builder stage
COPY --from=builder /build/public/build ./public/build

# Create cache directory for views
RUN mkdir -p .cache/views

# Expose port 8000
EXPOSE 8000
ENV APP_ENV=production
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
