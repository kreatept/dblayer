# DBLayer

DBLayer is a lightweight and flexible ORM for PHP that simplifies working with databases.

## Features

- Simple and intuitive query builder
- Advanced SQL support (JOINs, IN, BETWEEN, etc.)
- Persistent database connections for better performance

## Installation

Install via Composer:

```
composer require kreatept/dblayer
```

## Usage

```php
require_once 'vendor/autoload.php';

use Kreatept\DBLayer\DataLayer;

// Fetch all users
$dataLayer = new DataLayer('users');
$result = $dataLayer->fetch();
print_r($result);
```

## License

MIT License
