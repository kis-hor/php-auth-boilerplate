# PHP Auth Boilerplate

A lightweight, modern PHP boilerplate with built-in authentication, routing, middleware, and MVC architecture. Perfect for starting new projects or learning PHP development best practices.

## 🌟 Features

- **Authentication System**: Secure login/logout with bcrypt password hashing
- **User Management CRUD**: Complete user management with create, read, update, delete operations
- **Role-Based Access Control**: 4 built-in roles - Admin, Manager, Staff, User
- **Routing**: Simple and intuitive route registration with HTTP method support
- **Middleware**: Route protection with customizable middleware and chaining
- **MVC Architecture**: Organized folder structure (Models, Views, Controllers)
- **Layout System**: Reusable layout templates with header/footer partials to eliminate code duplication
- **Database Ready**: PDO-based database abstraction layer with validation
- **Session Management**: Built-in session handling
- **Error Pages**: Beautiful 404 page design with layout integration
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
│   │   ├── DashboardController.php
│   │   └── UserController.php
│   ├── Core/                 # Core framework classes
│   │   ├── App.php
│   │   ├── Auth.php         # Authentication logic
│   │   ├── Controller.php   # Base controller with view helpers
│   │   ├── Database.php     # Database connection
│   │   ├── Debug.php        # Debugging utilities
│   │   ├── Model.php        # Base model
│   │   ├── Router.php       # Routing engine with middleware
│   │   ├── Session.php      # Session management
│   │   └── ViewRenderer.php # Layout/view rendering
│   ├── Middleware/           # Request middleware
│   │   ├── AuthMiddleware.php
│   │   ├── GuestMiddleware.php
│   │   └── RoleMiddleware.php
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
│   │   └── main.php         # Master layout template
│   ├── partials/
│   │   ├── header.php       # Navigation header
│   │   └── footer.php       # Footer
│   ├── users/               # User management views
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── dashboard.php
│   └── notfound.php         # 404 page
├── .gitignore               # Git ignore rules
├── composer.json            # PHP dependencies
├── seed.php                 # Database seeder for test data
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

5. **Seed test users** (optional, run after table creation)
   ```bash
   php seed.php
   ```
   
   This creates test users:
   - Username: `admin` | Password: `password` | Role: `admin` | Status: `active`
   - Username: `manager` | Password: `password` | Role: `manager` | Status: `active`
   - Username: `staff` | Password: `password` | Role: `staff` | Status: `active`
   
   Or manually insert admin user:
   ```sql
   INSERT INTO users (username, email, password_hash, role, status) 
   VALUES ('admin', 'admin@example.com', '$2y$12$LEx0TCTm1JTpBT0Zq.Nd6O8WIER65Vu8lfuWFyf8er8gxwUHxbOXK', 'admin', 'active');
   ```

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

// Protected route with single middleware
$router->get('/dashboard', [DashboardController::class, 'index'])
    ->middleware([AuthMiddleware::class]);

// Protected route with multiple middleware (chaining)
$router->get('/users', [UserController::class, 'index'])
    ->middleware([AuthMiddleware::class, RoleMiddleware::class]);

// POST route
$router->post('/login', [AuthController::class, 'login']);

// Dynamic route parameters
$router->get('/users/edit/{id}', [UserController::class, 'edit'])
    ->middleware([AuthMiddleware::class]);

$router->post('/users/update/{id}', [UserController::class, 'update'])
    ->middleware([AuthMiddleware::class]);
```

### Route Parameters
Routes are normalized automatically (trailing slashes removed). Dynamic parameters in routes are extracted and passed to controller methods:

```php
public function edit($id) {
    $user = User::findById($id);
    $this->view('users/edit', ['user' => $user]);
}

## 🛡️ Middleware

### Built-in Middleware

**AuthMiddleware** - Protects routes by requiring authentication. Redirects to login if not authenticated.
```php
$router->get('/dashboard', [DashboardController::class, 'index'])
    ->middleware([AuthMiddleware::class]);
```

**GuestMiddleware** - Prevents authenticated users from accessing auth pages (e.g., login page).
```php
$router->get('/login', [AuthController::class, 'showLogin'])
    ->middleware([GuestMiddleware::class]);
```

**RoleMiddleware** - Restricts access based on user role (admin, manager, staff, user).
```php
$router->get('/users', [UserController::class, 'index'])
    ->middleware([AuthMiddleware::class, RoleMiddleware::class]);
```

### Middleware Chaining
Multiple middleware can be chained together. They execute in order:

```php
$router->get('/admin/panel', [AdminController::class, 'index'])
    ->middleware([AuthMiddleware::class, RoleMiddleware::class]);
```

If any middleware returns `false`, the request is denied.

### Custom Middleware
Create custom middleware by extending the pattern:

```php
namespace App\Middleware;

class CustomMiddleware
{
    public function handle(): bool
    {
        // Your logic here
        if (condition) {
            return true;   // Allow request
        }
        return false;      // Deny request
    }
}
```

## 🎨 Views & Controllers

### Layout System
This boilerplate uses a master layout system to eliminate code duplication. All views are rendered with a consistent header and footer.

**Main Layout** (`views/layouts/main.php`):
```html
<!-- Auto-includes header and footer -->
<?php $this->partial('header', $data); ?>
    <main class="content">
        <?php echo $content; ?>
    </main>
<?php $this->partial('footer', $data); ?>
```

**Rendering with layout:**
```php
$this->view('users/index', $data);  // Uses main.php layout by default
```

**Rendering without layout:**
```php
$this->viewPlain('auth/login', $data);  // No layout wrapper
```

### Controller Example
```php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::getAllUsers();
        $data = ['users' => $users, 'title' => 'User Management'];
        $this->view('users/index', $data);  // Renders with layout
    }

    public function edit($id)
    {
        $user = User::findById($id);
        $this->view('users/edit', ['user' => $user]);
    }
}
```

### View Example
```php
<!-- views/users/index.php -->
<h1><?= htmlspecialchars($title) ?></h1>

<table>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td><a href="/users/edit/<?= $user['id'] ?>">Edit</a></td>
        </tr>
    <?php endforeach; ?>
</table>
```

## 💾 Database

### Users Table Schema
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',           -- admin, manager, staff, user
    status VARCHAR(20) DEFAULT 'active',       -- active, inactive
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login_at TIMESTAMP NULL,
    login_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL
);
```

### User Management CRUD

**Create User:**
```php
User::create([
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'password_hash' => password_hash('password', PASSWORD_BCRYPT),
    'role' => 'staff',
    'status' => 'active',
    'created_by' => Auth::user()['id']
]);
```

**Read Users:**
```php
$user = User::findById(1);
$user = User::findByUsername('john_doe');
$allUsers = User::getAllUsers();
```

**Update User:**
```php
User::update(1, [
    'email' => 'newemail@example.com',
    'role' => 'manager',
    'status' => 'active'
]);
```

**Delete User (Soft Delete):**
```php
User::delete(1);  // Sets status to 'inactive'
```

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
        $user = new self();
        $results = $user->query('SELECT * FROM users WHERE username = ?', [$username]);
        return $results[0] ?? null;
    }
}
```

### Database Queries
```php
// Query (SELECT)
$results = $this->query('SELECT * FROM users WHERE status = ?', ['active']);

// Execute (INSERT, UPDATE, DELETE)
$this->execute('UPDATE users SET status = ? WHERE id = ?', ['active', 1]);

// Find by ID
$user = $this->find(1);

// Get all records
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

## 🚨 Error Handling

### 404 Page
A beautiful, responsive 404 page is automatically rendered when a route is not found. The page includes:
- Large error code display with animation
- Clear messaging
- Display of the requested path
- Quick navigation buttons to dashboard and users

The 404 page is rendered through the layout system for consistency:
```php
// Router.php automatically renders when route not found
http_response_code(404);
$renderer = new ViewRenderer();
echo $renderer->render('notfound', ['requestedPath' => $uri], 'main');
```

## 🚨 Security Best Practices

1. **Never commit `.env` or sensitive config** - Use `.gitignore`
2. **Always use `htmlspecialchars()` in views** - Prevent XSS
3. **Use `password_hash()` for passwords** - Never store plaintext
4. **Validate & sanitize input** - Always escape user data
5. **Keep dependencies updated** - Run `composer update`
6. **Set `debug = false` in production** - Hide system info
7. **Validate form inputs server-side** - Check role and status against allowed values
8. **Use middleware for protection** - Protect sensitive routes with AuthMiddleware

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
