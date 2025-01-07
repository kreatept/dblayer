<?php

require_once '../src/DataLayer.php';

use Kreatept\DBLayer\DataLayer;

$dataLayer = new DataLayer('users');

// Fetch all users
$result = $dataLayer->fetch();
print_r($result);

// Fetch specific user
$result = $dataLayer
    ->where('username', 'john_doe')
    ->fetch();
print_r($result);
