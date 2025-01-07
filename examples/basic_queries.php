<?php

require_once '../src/DataLayer.php';

use Kreatept\DBLayer\DataLayer;

$dataLayer = new DataLayer('users');

// Fetch all users
$result = $dataLayer->insert([
    'username' => 'test_user',
    'email' => 'test@example.com',
    'created_at' => date('Y-m-d H:i:s')
]);
print_r($result);

// Find and update
$user = $dataLayer->find(1);
if ($user) {
    $user->update(['email' => 'updated@example.com']);
    print_r($user->toArray());
}
