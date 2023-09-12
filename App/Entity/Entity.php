<?php

namespace App\Entity;

use App\Db\Db;

abstract class Entity
{
    protected $db = null;

    public function __construct()
    {
        $this->db = Db::get_instance();
    }

    public function get_pk()
    {
        return (int)$this->pk;
    }
}