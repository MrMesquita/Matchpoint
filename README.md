
# 🎾 Matchpoint

**Matchpoint** is a web platform developed with Laravel with the aim of connecting people interested in practicing volleyball in groups. The application facilitates the creation and management of matches, promoting interaction between users and volleyball arenas.

## 🚀 Technologies Used

- [Laravel 11](https://laravel.com/)
- [PHP 8.2](https://www.php.net/releases/8.2/)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)
- [Pest 3](https://pestphp.com/)

## 📦 Project Structure

The project follows Laravel's standard structure, with the following main directories:

- `app/`: Contains models, controllers, and other core components of the application.
- `resources/`: Includes Blade views, CSS, and JavaScript files.
- `routes/`: Defines the application's routes.
- `database/`: Contains database migrations and seeders.
- `public/`: Public directory accessible via the web.

## ⚙️ Installation and Configuration

Follow the steps below to set up and run the project locally:

1. **Clone the repository:**

   ```bash
   git clone https://github.com/MrMesquita/Matchpoint.git
   cd Matchpoint
   ```

2. **Install PHP dependencies:**

   ```bash
   composer install
   ```

3. **Configure the environment:**

   - Copy the `.env.example` file to `.env`:

     ```bash
     cp .env.example .env
     ```

   - Generate the application key:

     ```bash
     php artisan key:generate
     ```

   - Configure environment variables in the `.env` file, such as database credentials.

4. **Run database migrations:**

   ```bash
   php artisan migrate
   ```

5. **Start the development server:**

   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`.

## 🧪 Testing

To run automated tests, use the following command:

```bash
php artisan test
```

Ensure that the testing environment is properly configured.

## 📄 License

This project is licensed under the [MIT License](LICENSE).

## 🤝 Contributions

Contributions are welcome! Feel free to open issues or submit pull requests to improve the project.
