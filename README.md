# PHP Auth Boilerplate

A lightweight, modern PHP boilerplate with built-in authentication, routing, middleware, and MVC architecture. Perfect for starting new projects or learning PHP development best practices.

## 🌟 Features

- **Authentication System**: Secure login/logout with bcrypt password hashing
- **Routing**: Simple and intuitive route registration with HTTP method support
- **Middleware**: Route protection with customizable middleware
- **MVC Architecture**: Organized folder structure (Models, Views, Controllers)
- **Database Ready**: PDO-based database abstraction layer
- **Session Management**: Built-in session handling
- **Debug Tools**: Comprehensive debugging utilities for development
- **Responsive UI**: Modern, styled HTML with CSS included
- **Git Ready**: `.gitignore` configured for production safety

## 📁 Project Structure

```
php-auth-boilerplate/
├── app/
│   ├── Controllers/          # Route handlers
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   └── DashboardController.php
│   ├── Core/                 # Core framework classes
│   │   ├── App.php
│   │   ├── Auth.php         # Authentication logic
│   │   ├── Controller.php   # Base controller
│   │   ├── Database.php     # Database connection
│   │   ├── Debug.php        # Debugging utilities
│   │   ├── Model.php        # Base model
│   │   ├── Router.php       # Routing engine
│   │   └── Session.php      # Session management
│   ├── Middleware/           # Request middleware
│   │   └── AuthMiddleware.php
│   ├── Models/               # Data models
│   │   └── User.php
│   └── Services/             # Business logic
├── config/                   # Configuration files
│   ├── app.php
│   └── database.php
├── public/                   # Web-accessible folder
│   ├── index.php            # Entry point
│   └── assets/
│       └── css/
│           ├── style.css    # Login styles
│           └── dashboard.css
├── routes/
│   └── web.php              # Route definitions
├── storage/
│   └── logs/                # Application logs
├── views/                   # HTML templates
│   ├── auth/
│   │   └── login.php
│   ├── layouts/
│   └── dashboard.php
├── .gitignore               # Git ignore rules
├── composer.json            # PHP dependencies
└── README.md               # This file
```

## 🚀 Installation

### Requirements
- PHP 8.0+
- MySQL/MariaDB
- Composer (optional but recommended)

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/php-auth-boilerplate.git
   cd php-auth-boilerplate
   ```

2. **Configure your web server**
   - Set DocumentRoot to the `public/` folder
   - For MAMP: Point to `/Applications/MAMP/htdocs/php-auth-boilerplate/public`

3. **Configure database** (edit `config/database.php`)
   ```php
   'host' => 'localhost',
   'database' => 'your_database',
   'user' => 'root',
   'password' => 'your_password'
   ```

4. **Create database table**
   ```sql
   CREATE TABLE users (
       id INT PRIMARY KEY AUTO_INCREMENT,
       username VARCHAR(50) UNIQUE NOT NULL,
       email VARCHAR(100) UNIQUE NOT NULL,
       password_hash VARCHAR(255) NOT NULL,
       role VARCHAR(20) DEFAULT 'user',
       status VARCHAR(20) DEFAULT 'active',
       created_by INT,
       created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
       updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       last_login_at TIMESTAMP NULL,
       login_attempts INT DEFAULT 0,
       locked_until TIMESTAMP NULL
   );
   ```

5. **Create test user** (use the hash provided below)
   ```sql
   INSERT INTO users (username, email, password_hash, role, status) 
   VALUES ('admin', 'admin@example.com', '$2y$12$LEx0TCTm1JTpBT0Zq.Nd6O8WIER65Vu8lfuWFyf8er8gxwUHxbOXK', 'admin', 'active');
   ```
   - Username: `admin`
   - Password: `password`

## 🔐 Authentication

### Login System
The authentication system uses bcrypt hashing for passwords and session management for user state.

**Generate password hashes:**
```bash
php -r "echo password_hash('your_password', PASSWORD_BCRYPT);"
```

**Update user password:**
```php
$hash = password_hash('newpassword', PASSWORD_BCRYPT);
// Use in database UPDATE query
```

### Session Management
Sessions are automatically created upon successful login and cleared on logout.

**Access user data:**
```php
$user = Auth::user();  // Get current user array
if (Auth::check()) {
    echo $user['name'];  // Logged in user
}
```

## 🛣️ Routing

### Basic Routes

**Define routes in `routes/web.php`:**

```php
// Simple route
$router->get('/', [HomeController::class, 'index']);

// Protected route with middleware
$router->get('/', [HomeController::class, 'index'])
    ->middleware([AuthMiddleware::class]);

// POST route
$router->post('/login', [AuthController::class, 'login']);
```

### Route Parameters
Routes are normalized automatically (trailing slashes removed).

## 🛡️ Middleware

### AuthMiddleware
Protects routes by requiring authentication. Redirects to login if not authenticated.

**Usage:**
```php
$router->get('/dashboard', [DashboardController::class, 'index'])
    ->middleware([AuthMiddleware::class]);
```

**Create custom middleware:**
```php
namespace App\Middleware;

class CustomMiddleware
{
    public function handle(): bool
    {
        // Your logic here
        return true;  // Allow request
        // return false; // Deny request
    }
}
```

## 🎨 Views & Controllers

### Controller Example
```php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $data = ['title' => 'Home'];
        $this->view('home', $data);  // Renders views/home.php
    }
}
```

### View Example
```php
<!-- views/home.php -->
<h1><?= htmlspecialchars($title) ?></h1>
<p>Welcome <?= htmlspecialchars($user['name'] ?? 'Guest') ?></p>
```

## 💾 Database

### Models
Extend the base `Model` class to create database models.

```php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public static function findByUsername(string $username): ?array
    {
        // Custom query logic
    }
}
```

### Database Queries
```php
// Query
$results = $this->query('SELECT * FROM users WHERE status = ?', ['active']);

// Execute (INSERT, UPDATE, DELETE)
$this->execute('UPDATE users SET status = ? WHERE id = ?', ['active', 1]);

// Find by ID
$user = $this->find(1);

// Get all
$users = $this->all();
```

## 🐛 Debugging

The `Debug` class provides helpful utilities for development.

**Display all superglobals:**
```php
use App\Core\Debug;

Debug::showAll();      // Shows $_SERVER, $_POST, $_GET, $_SESSION, $_COOKIE
Debug::showPost();     // Show only POST data
Debug::showSession();  // Show session data
```

**Display data:**
```php
Debug::showData($user, "User from Database");
```

**Log messages:**
```php
Debug::logSuccess("Operation completed", ['id' => 123]);
Debug::logError("Something went wrong", $error);
Debug::logInfo("FYI message", $data);
```

## 📝 Static Assets

Static files (CSS, JS, images) go in `public/assets/`:

```
public/assets/
├── css/
│   ├── style.css
│   └── dashboard.css
├── js/
└── images/
```

**Reference in views:**
```html
<link rel="stylesheet" href="/assets/css/style.css">
<script src="/assets/js/app.js"></script>
```

## 🔄 Reusing This Boilerplate

### For New Projects
1. Clone the repository
2. Rename the project
3. Modify routes in `routes/web.php`
4. Create new controllers and views
5. Update configuration in `config/` folder

### Sharing With Others
- Document your custom features
- Update this README with project-specific info
- Include database migration files
- Add `.env.example` for environment variables

## 📦 Installation via Composer (Optional)

If using composer, update `composer.json` with your autoloading:

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
```

Then run:
```bash
composer dump-autoload
```

## 🛠️ Configuration

### Database (`config/database.php`)
```php
return [
    'driver' => 'mysql',
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'your_db',
    'user' => 'root',
    'password' => 'password'
];
```

### App (`config/app.php`)
```php
return [
    'name' => 'PHP Auth Boilerplate',
    'debug' => true  // Set to false in production
];
```

## 🚨 Security Best Practices

1. **Never commit `.env` or sensitive config** - Use `.gitignore`
2. **Always use `htmlspecialchars()` in views** - Prevent XSS
3. **Use `password_hash()` for passwords** - Never store plaintext
4. **Validate & sanitize input** - Always escape user data
5. **Keep dependencies updated** - Run `composer update`
6. **Set `debug = false` in production** - Hide system info

## 📄 License

MIT License - Feel free to use this boilerplate for personal and commercial projects.

## 🤝 Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 💡 Tips & Tricks

- Use the `Debug` class during development to inspect data
- Keep controllers thin - move logic to models and services
- Use middleware for cross-cutting concerns (auth, logging, etc.)
- Organize routes by feature for larger projects
- Always validate user input on both client and server side

## 📞 Support

For issues or questions:
- Check existing issues on GitHub
- Create a new issue with detailed description
- Include error logs and steps to reproduce

---

**Happy coding! 🚀**
