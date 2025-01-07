<?php

namespace Example\Models;

use Kreatept\DBLayer\DBLayer;

/**
 * Class User
 * @package Example\Models
 */
class UserDatabase extends DBLayer
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct("ws_users", ["user_name", "user_lastname"], "user_id", false, DATABASE);
    }
}
