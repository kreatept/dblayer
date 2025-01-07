# DBLayer

DBLayer is a lightweight and flexible ORM for PHP that simplifies working with databases.

## Features

- Simple and intuitive query builder
- Advanced SQL support (JOINs, IN, BETWEEN, etc.)
- Find and update workflow
- Persistent database connections for better performance

## Installation

Install via Composer:

```
composer require kreatept/dblayer
```

## Usage

### Basic Usage

```php
require_once 'vendor/autoload.php';

use Kreatept\DBLayer\DataLayer;

// Insert a record
$dataLayer = new DataLayer('users');
$newUserId = $dataLayer->insert([
    'username' => 'john_doe',
    'email' => 'john@example.com',
    'created_at' => date('Y-m-d H:i:s')
]);
echo "New user ID: $newUserId";

// Find and update a record
$user = $dataLayer->find($newUserId);
if ($user) {
    $user->update(['email' => 'updated@example.com']);
    print_r($user->toArray());
}
```

## License

MIT License
