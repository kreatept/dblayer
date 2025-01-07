<?php

namespace Example\Models;

use Kreatept\DBLayer\DBLayer;

class Company extends DBLayer
{
    public function __construct()
    {
        parent::__construct("companies", ["user_id", "name"]);
    }
}
