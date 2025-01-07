<?php

require_once '../vendor/autoload.php';

use Kreatept\DBLayer\DataLayer;

// Initialize DataLayer for 'orders' table
$dataLayer = new DataLayer('orders');

// Count total orders
$result = $dataLayer
    ->aggregate('COUNT', '*', 'total_orders')
    ->fetch();
print_r($result);

// Average order total
$result = $dataLayer
    ->aggregate('AVG', 'orders.total', 'average_total')
    ->fetch();
print_r($result);
