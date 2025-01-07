<?php

namespace Example\Models;

use Kreatept\DBLayer\DBLayer;

/**
 * Class Address
 * @package Example\Models
 */
class Address extends DBLayer
{
    /**
     * Address constructor.
     */
    public function __construct()
    {
        parent::__construct("adresses", ["user_id"]);
    }

    /**
     * @return $this
     */
    public function getUser(): Address
    {
        $this->user = (new User())->findById($this->user_id)->data();
        return $this;
    }
}
