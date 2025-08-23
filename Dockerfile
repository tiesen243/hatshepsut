# --- Stage 1: Build frontend assets with Bun ---
FROM oven/bun:latest AS builder
WORKDIR /build

# Copy only files needed for dependency installation
COPY package.json bun.lock ./
RUN bun install --frozen-lockfile

# Copy only necessary frontend source files for build
COPY resources ./resources
COPY vite.config.ts vite.config.ts

# Build frontend assets
RUN bun run build

# --- Stage 2: PHP application setup and runtime ---
FROM php:8.4-cli AS runner
WORKDIR /app

# Install system dependencies and PHP extensions in a single layer
RUN apt-get update && \
    apt-get install -y --no-install-recommends git unzip && \
    docker-php-ext-install pdo pdo_mysql && \
    rm -rf /var/lib/apt/lists/*

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy PHP dependency files and install dependencies (no dev)
COPY composer.json ./
RUN composer install --no-interaction --no-dev --prefer-dist --optimize-autoloader

# Copy application source code
COPY . .

# Copy built frontend assets from builder stage
COPY --from=builder /build/public ./public

# Start the application
EXPOSE 8000
CMD ["composer", "serve"]
