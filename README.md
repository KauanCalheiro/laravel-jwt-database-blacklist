# Laravel JWT Database Blacklist

[![Latest Version](https://img.shields.io/github/v/release/KauanCalheiro/laravel-jwt-database-blacklist)](https://github.com/KauanCalheiro/laravel-jwt-database-blacklist/releases)
[![PHP Version](https://img.shields.io/badge/php-%5E8.0-blue.svg)](https://php.net/) 
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Downloads](https://img.shields.io/packagist/dt/kamoca/laravel-jwt-database-blacklist)](https://packagist.org/packages/kamoca/laravel-jwt-database-blacklist)

A Laravel package to store JWT blacklisted tokens in the database instead of cache.

Built to work with [tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth).


## ✨ Features

- 🛡️ Blacklist JWT tokens in a dedicated database table
- ⚡ Works with `tymon/jwt-auth`
- 🗄️ No cache needed
- ✅ Ready for production use


## 🚀 Installation

```bash
composer require kamoca/laravel-jwt-database-blacklist
```

## ⚙️ Setup

1. **Publish migration**

```bash
php artisan vendor:publish --tag=jwt-blacklist-migrations
php artisan migrate
```

This will create the `jwt_blacklists` table in your database.

2. **Configure JWT**

    2.1. **Set storage**

    In `config/jwt.php`, set the `storage` option:

    ```php
    'storage' => JwtDatabaseBlacklist\Storage\DatabaseStorage::class,
    ```

    2.2. **Ensure blacklist is enabled**

    In `config/jwt.php`, set the `blacklist_enabled` option:

    ```php
    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),
    ```

    Then in your `.env` file:

    ```env
    JWT_BLACKLIST_ENABLED=true
    ```

Now, when you invalidate a token, it will be stored in the database and blocked from reuse.

## 🔧 Usage

Example logout controller:

```php
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;

public function logout()
{
    // These will store {"valid_until":...} in the `jwt_blacklists` table
    Auth::logout();
    auth()->logout();

    // These will store 'forever' in the `jwt_blacklists` table
    JWTAuth::invalidate(JWTAuth::getToken(), true);
}
```

Any request using the same token after invalidation will fail.

## 📝 License

Este projeto está licenciado sob a **Licença MIT** - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 👨‍💻 Author

**Kauan Morinel Calheiro**

- 📧 Email: [kauan.calheiro@universo.univates.br](mailto:kauan.calheiro@universo.univates.br)
- 🐙 GitHub: [@KauanCalheiro](https://github.com/KauanCalheiro)