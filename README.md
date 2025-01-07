# DBLayer ORM

DBLayer is a lightweight and flexible ORM for PHP that simplifies working with databases.

## Features

- Advanced SQL queries: `JOIN`, `IN`, `NOT IN`, `LIKE`, `BETWEEN`, etc.
- Simplified date filters: `whereDate`, `whereBetweenDates`, `whereLastNDays`.
- Active record support: Automatic `created_at` and `updated_at`.
- Insert single and multiple rows.
- Update with `find`.
- Aggregate functions: `SUM`, `COUNT`, `AVG`, `MIN`, `MAX`.
- Optimized for large datasets.

## Installation

Install via Composer:

```
composer require kreatept/dblayer
```

## Usage Examples

### Insert Single and Multiple Rows

```php
$dataLayer = new DataLayer('users');

// Insert a single user
$newUserId = $dataLayer->insert([
    'username' => 'john_doe',
    'email' => 'john@example.com'
]);

// Insert multiple users
$dataLayer->insertMultiple([
    ['username' => 'jane_doe', 'email' => 'jane@example.com'],
    ['username' => 'mark_smith', 'email' => 'mark@example.com']
]);
```

### Find and Update

```php
$user = $dataLayer->find($newUserId);

if ($user) {
    $user->update(['email' => 'updated_john@example.com']);
}
```

### Delete a Record

```php
$user = $dataLayer->find($newUserId);

if ($user) {
    $user->destroy();
}
```

### Aggregate Functions

```php
$result = $dataLayer
    ->aggregate('SUM', 'orders.total', 'total_sales')
    ->fetch();
print_r($result);

$result = $dataLayer
    ->aggregate('COUNT', '*', 'total_users')
    ->fetch();
print_r($result);
```

## License

MIT License
