<?php

require_once '../vendor/autoload.php';

use Kreatept\DBLayer\DataLayer;

// Initialize DataLayer for 'users' table
$dataLayer = new DataLayer('users');

// Insert multiple users
$dataLayer->insertMultiple([
    ['username' => 'jane_doe', 'email' => 'jane@example.com'],
    ['username' => 'mark_smith', 'email' => 'mark@example.com'],
    ['username' => 'lisa_brown', 'email' => 'lisa@example.com']
]);

echo "Multiple users inserted successfully!\n";
