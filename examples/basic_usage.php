<?php

require_once '../vendor/autoload.php';

use Kreatept\DBLayer\DataLayer;

// Initialize DataLayer for 'users' table
$dataLayer = new DataLayer('users');

// Insert a single user
$newUserId = $dataLayer->insert([
    'username' => 'john_doe',
    'email' => 'john@example.com'
]);
echo "Inserted user ID: $newUserId\n";

// Find a user and update
$user = $dataLayer->find($newUserId);
if ($user) {
    $user->update(['email' => 'updated_john@example.com']);
    print_r($user->toArray());
}

// Delete the user
if ($user) {
    $user->destroy();
    echo "User deleted!\n";
}
