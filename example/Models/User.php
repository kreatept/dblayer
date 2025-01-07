<?php

namespace Example\Models;

use Kreatept\DBLayer\DBLayer;

/**
 * Class User
 * @package Example\Models
 */
class User extends DBLayer
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("users", ["first_name", "last_name"]);
    }

    public function fullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
