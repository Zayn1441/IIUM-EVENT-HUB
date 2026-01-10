# IIUM Event Hub - Setup and Run Instructions

This guide provides detailed steps to set up and run the IIUM Event Hub project on your local machine.

## Prerequisites

Ensure you have the following installed on your system:
- **PHP** (8.1 or higher)
- **Composer** (Dependency Manager for PHP)
- **Node.js** & **NPM** (For frontend assets)
- **Database** (MySQL or SQLite - instructions below cover SQLite for simplicity)
- **Git**

## Installation Steps

1.  **Clone the Repository** (if you haven't already):
    ```bash
    git clone <repository_url>
    cd iium-event-hub
    ```

2.  **Install PHP Dependencies**:
    ```bash
    composer install
    ```

3.  **Install Frontend Dependencies**:
    ```bash
    npm install
    ```

4.  **Environment Configuration**:
    Duplicate the example environment file:
    ```bash
    cp .env.example .env
    ```
    *Windows (PowerShell):*
    ```powershell
    copy .env.example .env
    ```

5.  **Generate Application Key**:
    ```bash
    php artisan key:generate
    ```

6.  **Database Setup (SQLite)**:
    By default, this project can use SQLite which requires zero external configuration.
    
    *   Open the `.env` file and verify/update the database section to:
        ```env
        DB_CONNECTION=sqlite
        # DB_HOST=127.0.0.1
        # DB_PORT=3306
        # DB_DATABASE=laravel
        # DB_USERNAME=root
        # DB_PASSWORD=
        ```
    *   Create the database file (if it doesn't exist):
        *Linux/Mac:* `touch database/database.sqlite`
        *Windows (PowerShell):* `New-Item -ItemType File database/database.sqlite`

7.  **Run Migrations and Seeders**:
    This will create the database tables and populate them with test data (admin user, sample events).
    ```bash
    php artisan migrate --seed
    ```

## Running the Application

You need to run two servers simultaneously (in two separate terminal windows):

**Terminal 1: Laravel Backend**
```bash
php artisan serve
```
*This will usually run the app at `http://localhost:8000`*

**Terminal 2: Vite Frontend (Assets)**
```bash
npm run dev
```

## Default Credentials

The `migrate --seed` command creates a default admin user:
- **Email:** `test@example.com`
- **Password:** `password`

## Troubleshooting

-   **"Vite manifest not found"**: Ensure `npm run dev` is running.
-   **"500 Server Error"**: Check `storage/logs/laravel.log` or ensure `.env` exists and `php artisan key:generate` was run.
-   **Database Error**: Ensure you created the `database/database.sqlite` file if using SQLite.

## Project Structure Overview
-   `app/Models`: Database models (Event, User, Registration).
-   `app/Http/Controllers`: Backend logic (EventController, etc.).
-   `resources/views`: Frontend Blade templates.
-   `routes/web.php`: Application routes.
