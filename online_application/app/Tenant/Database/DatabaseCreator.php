<?php

namespace App\Tenant\Database;

use App\School;
use App\Tenant\Models\Tenant;
use Illuminate\Support\Facades\DB;

class DatabaseCreator
{
    public function create(Tenant $tenant)
    {
        $db = env('DATABASE_PREFIX') . $tenant->id;
        $userName = env('DB_USERNAME');
        return DB::statement("
            CREATE DATABASE IF NOT EXISTS $db;
            GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, INDEX, DROP, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES ON $db.* TO '$userName';");
    }

    public function delete(School $school)
    {
        $db = env('DATABASE_PREFIX') . $school->id;

        return DB::statement("DROP DATABASE IF EXISTS $db;");
    }
}
