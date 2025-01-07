<?php

require_once '../src/DataLayer.php';

use Kreatept\DBLayer\DataLayer;

$dataLayer = new DataLayer('orders');

// Join Example
$result = $dataLayer
    ->join('users', 'orders.user_id = users.id', 'INNER')
    ->where('orders.status', 'completed')
    ->fetch();
print_r($result);

// Aggregate Example
$result = $dataLayer
    ->aggregate('SUM', 'orders.total', 'total_sales')
    ->fetch();
print_r($result);

// Date Filtering
$result = $dataLayer
    ->whereDate('created_at', '>=', '2025-01-01')
    ->fetch();
print_r($result);
