# Hatshepsut

A modern PHP web application framework that blends a robust backend with a streamlined frontend build process powered by Vite.

## Features

- **Robust Backend:** Built with PHP in an MVC architecture for scalable and maintainable server-side logic.
- **Streamlined Frontend:** Uses Vite for rapid development and hot module replacement, supporting React and Vue integration.
- **Modern Tooling:** Ships with Docker configuration for easy local development and deployment.
- **Styling:** Includes Tailwind CSS for utility-first styling.
- **Database:** MySQL support out of the box.

## Getting Started

### Prerequisites

- Docker & Docker Compose
- PHP (if running outside Docker)
- Node.js & npm (bun recommended)
- MySQL

### Installation

1. Clone the repository:

```bash
git clone https://github.com/tiesen243/hatshepsut.git
cd hatshepsut
```

2. Install dependencies:

```bash
composer install
bun install
```

3. Set up environment variables:

   Copy the `.env.example` file to `.env` and configure your environment settings, such as:
   - `DB_HOST`: The hostname of your database server (e.g., `127.0.0.1`)
   - `DB_PORT`: The port number your database server is running on (usually `3306` for MySQL)
   - `DB_DATABASE`: The name of your database (e.g., `my_app_db`)
   - `DB_USERNAME`: Your database user (e.g., `root`)
   - `DB_PASSWORD`: Your database password (if applicable)

4. Start the application:

   ```bash
   docker-compose up db -d
   bun dev
   ```

5. Access the application:
   Open your browser and navigate to `http://localhost:8000`.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the [MIT License](LICENSE).
