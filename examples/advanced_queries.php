<?php

require_once '../vendor/autoload.php';

use Kreatept\DBLayer\DataLayer;

// Initialize DataLayer for 'orders' table
$dataLayer = new DataLayer('orders');

// Join Example
$result = $dataLayer
    ->join('users', 'orders.user_id = users.id', 'INNER')
    ->where('orders.status', 'completed')
    ->fetch();
print_r($result);

// Aggregate Example: Calculate total sales
$result = $dataLayer
    ->aggregate('SUM', 'orders.total', 'total_sales')
    ->fetch();
print_r($result);

// Date Filtering: Fetch records after a certain date
$result = $dataLayer
    ->whereDate('created_at', '>=', '2025-01-01')
    ->fetch();
print_r($result);
