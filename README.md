# Hatshepsut

<p align="center">
  <a href="https://packagist.org/packages/tiesen243/hatshepsut"><img src="https://img.shields.io/packagist/dt/tiesen243/hatshepsut" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/tiesen243/hatshepsut"><img src="https://img.shields.io/packagist/v/tiesen243/hatshepsut" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/tiesen243/hatshepsut"><img src="https://img.shields.io/packagist/l/tiesen243/hatshepsut" alt="License"></a>
</p>

**Hatshepsut** is a modern PHP web application framework that blends a robust backend with a streamlined frontend build process powered by Vite. The project is designed to help developers rapidly build scalable and maintainable web applications using contemporary PHP best practices and a component-driven frontend workflow.

## Features

- **Custom PHP Framework Core**: Includes core modules for HTTP request handling, routing, database connectivity (`PDO`), environment configuration, and templating.
- **Blade-like Templating Engine**: Supports directives for layouts, sections, includes, asset loading (`@vite`), and conditionals, enabling a clean separation of logic and presentation.
- **Frontend Asset Pipeline**: Uses [Vite](https://vitejs.dev/) for fast, modern asset bundling and Hot Module Replacement (HMR) in development. JS and CSS entrypoints are managed via `vite.config.ts` and built using Bun.
- **Environment Management**: Loads configuration from environment files for seamless local development and production deployment.
- **Dockerized Workflow**: Multi-stage `Dockerfile` for building frontend assets and running the PHP application, making local and cloud deployment easy.
- **Extensible Structure**: Designed for expansion with custom controllers, middleware, and more.

## Getting Started

### Prerequisites

- [Docker](https://www.docker.com/) (for building production)
- [Bun](https://bun.sh/) or [npm](https://www.npmjs.com/) (for local asset builds)
- [Composer](https://getcomposer.org/) (for PHP dependencies)

### Development

1. **Clone the repository:**

   ```sh
   composer create-project tiesen243/hatshepsut my-app
   cd my-app
   ```

2. **Install dependencies:**

   ```sh
   composer install

   npm install
   # or
   yarn install
   # or
   pnpm install
   # or
   bun install
   ```

3. **Set up environment variables:**

   Copy the `.env.example` file to `.env` and configure your environment settings, such as database connection details.

   ```sh
   cp .env.example .env
   ```

4. **Run the application locally:**

   ```sh
   composer dev
   ```

   Open your browser and go to [http://localhost:8000](http://localhost:8000) to view the app.

### Production Build

1. **Preview the production build locally:**

   ```sh
   bun run build
   # or if using npm
   npm run build

   composer serve
   ```

2. **Build and run for deployment (Docker):**

   ```sh
   # Build the Docker image
   docker build -t hatshepsut .

   # Run the Docker container
   docker run -d -p 8000:8000 hatshepsut
   ```

## License

This project is licensed under the MIT License. See the [LICENSE](./LICENSE) file for details.
