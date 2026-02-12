<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait MultiDatabaseTrait
{
    /**
     * Set koneksi database secara dinamis
     */
    public function setConnection($name)
    {
        $this->connection = $name;
        return $this;
    }

    /**
     * Get koneksi database saat ini
     */
    public function getConnection()
    {
        return parent::getConnection();
    }

    /**
     * Query builder untuk satker tertentu
     */
    public static function onSatker($connection)
    {
        $instance = new static;
        $instance->setConnection($connection);
        return $instance->newQuery();
    }

    /**
     * Test koneksi database
     */
    public static function testConnection($connection)
    {
        try {
            DB::connection($connection)->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
