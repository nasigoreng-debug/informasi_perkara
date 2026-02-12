<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        // ============================================
        // 26 DATABASE PENGADILAN AGAMA - LARAVEL 10
        // ============================================

        // 1. PA BANDUNG
        'bandung' => [
            'driver' => 'mysql',
            'host' => env('DB_BANDUNG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_BANDUNG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_BANDUNG', 'bandung'),
            'username' => env('DB_BANDUNG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_BANDUNG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
        ],

        // 2. PA INDRAMAYU
        'indramayu' => [
            'driver' => 'mysql',
            'host' => env('DB_INDRAMAYU_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_INDRAMAYU_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_INDRAMAYU', 'indramayu'),
            'username' => env('DB_INDRAMAYU_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_INDRAMAYU_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 3. PA MAJALENGKA
        'majalengka' => [
            'driver' => 'mysql',
            'host' => env('DB_MAJALENGKA_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_MAJALENGKA_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_MAJALENGKA', 'majalengka'),
            'username' => env('DB_MAJALENGKA_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_MAJALENGKA_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 4. PA SUMBER
        'sumber' => [
            'driver' => 'mysql',
            'host' => env('DB_SUMBER_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_SUMBER_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_SUMBER', 'sumber'),
            'username' => env('DB_SUMBER_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_SUMBER_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 5. PA CIAMIS
        'ciamis' => [
            'driver' => 'mysql',
            'host' => env('DB_CIAMIS_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_CIAMIS_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_CIAMIS', 'ciamis'),
            'username' => env('DB_CIAMIS_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_CIAMIS_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 6. PA TASIKMALAYA
        'tasikmalaya' => [
            'driver' => 'mysql',
            'host' => env('DB_TASIKMALAYA_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_TASIKMALAYA_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_TASIKMALAYA', 'tasikmalaya'),
            'username' => env('DB_TASIKMALAYA_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_TASIKMALAYA_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 7. PA KARAWANG
        'karawang' => [
            'driver' => 'mysql',
            'host' => env('DB_KARAWANG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_KARAWANG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_KARAWANG', 'karawang'),
            'username' => env('DB_KARAWANG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_KARAWANG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 8. PA CIMAHI
        'cimahi' => [
            'driver' => 'mysql',
            'host' => env('DB_CIMAHI_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_CIMAHI_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_CIMAHI', 'cimahi'),
            'username' => env('DB_CIMAHI_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_CIMAHI_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 9. PA SUBANG
        'subang' => [
            'driver' => 'mysql',
            'host' => env('DB_SUBANG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_SUBANG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_SUBANG', 'subang'),
            'username' => env('DB_SUBANG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_SUBANG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 10. PA SUMEDANG
        'sumedang' => [
            'driver' => 'mysql',
            'host' => env('DB_SUMEDANG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_SUMEDANG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_SUMEDANG', 'sumedang'),
            'username' => env('DB_SUMEDANG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_SUMEDANG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 11. PA PURWAKARTA
        'purwakarta' => [
            'driver' => 'mysql',
            'host' => env('DB_PURWAKARTA_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_PURWAKARTA_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_PURWAKARTA', 'purwakarta'),
            'username' => env('DB_PURWAKARTA_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_PURWAKARTA_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 12. PA SUKABUMI
        'sukabumi' => [
            'driver' => 'mysql',
            'host' => env('DB_SUKABUMI_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_SUKABUMI_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_SUKABUMI', 'sukabumi'),
            'username' => env('DB_SUKABUMI_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_SUKABUMI_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 13. PA CIANJUR
        'cianjur' => [
            'driver' => 'mysql',
            'host' => env('DB_CIANJUR_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_CIANJUR_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_CIANJUR', 'cianjur'),
            'username' => env('DB_CIANJUR_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_CIANJUR_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 14. PA KUNINGAN
        'kuningan' => [
            'driver' => 'mysql',
            'host' => env('DB_KUNINGAN_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_KUNINGAN_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_KUNINGAN', 'kuningan'),
            'username' => env('DB_KUNINGAN_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_KUNINGAN_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 15. PA CIBADAK
        'cibadak' => [
            'driver' => 'mysql',
            'host' => env('DB_CIBADAK_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_CIBADAK_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_CIBADAK', 'cibadak'),
            'username' => env('DB_CIBADAK_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_CIBADAK_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 16. PA CIREBON
        'cirebon' => [
            'driver' => 'mysql',
            'host' => env('DB_CIREBON_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_CIREBON_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_CIREBON', 'cirebon'),
            'username' => env('DB_CIREBON_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_CIREBON_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 17. PA GARUT
        'garut' => [
            'driver' => 'mysql',
            'host' => env('DB_GARUT_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_GARUT_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_GARUT', 'garut'),
            'username' => env('DB_GARUT_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_GARUT_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 18. PA BOGOR
        'bogor' => [
            'driver' => 'mysql',
            'host' => env('DB_BOGOR_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_BOGOR_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_BOGOR', 'bogor'),
            'username' => env('DB_BOGOR_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_BOGOR_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 19. PA BEKASI
        'bekasi' => [
            'driver' => 'mysql',
            'host' => env('DB_BEKASI_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_BEKASI_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_BEKASI', 'bekasi'),
            'username' => env('DB_BEKASI_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_BEKASI_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 20. PA CIBINONG
        'cibinong' => [
            'driver' => 'mysql',
            'host' => env('DB_CIBINONG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_CIBINONG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_CIBINONG', 'cibinong'),
            'username' => env('DB_CIBINONG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_CIBINONG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 21. PA CIKARANG
        'cikarang' => [
            'driver' => 'mysql',
            'host' => env('DB_CIKARANG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_CIKARANG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_CIKARANG', 'cikarang'),
            'username' => env('DB_CIKARANG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_CIKARANG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 22. PA DEPOK
        'depok' => [
            'driver' => 'mysql',
            'host' => env('DB_DEPOK_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_DEPOK_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_DEPOK', 'depok'),
            'username' => env('DB_DEPOK_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_DEPOK_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 23. PA KOTA TASIKMALAYA
        'tasikkota' => [
            'driver' => 'mysql',
            'host' => env('DB_TASIKKOTA_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_TASIKKOTA_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_TASIKKOTA', 'tasikkota'),
            'username' => env('DB_TASIKKOTA_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_TASIKKOTA_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 24. PA KOTA BANJAR
        'banjar' => [
            'driver' => 'mysql',
            'host' => env('DB_BANJAR_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_BANJAR_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_BANJAR', 'banjar'),
            'username' => env('DB_BANJAR_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_BANJAR_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 25. PA SOREANG
        'soreang' => [
            'driver' => 'mysql',
            'host' => env('DB_SOREANG_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_SOREANG_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_SOREANG', 'soreang'),
            'username' => env('DB_SOREANG_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_SOREANG_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],

        // 26. PA NGAMPRAH
        'ngamprah' => [
            'driver' => 'mysql',
            'host' => env('DB_NGAMPRAH_HOST', env('DB_HOST', '127.0.0.1')),
            'port' => env('DB_NGAMPRAH_PORT', env('DB_PORT', '3306')),
            'database' => env('DB_NGAMPRAH', 'ngamprah'),
            'username' => env('DB_NGAMPRAH_USERNAME', env('DB_USERNAME', 'root')),
            'password' => env('DB_NGAMPRAH_PASSWORD', env('DB_PASSWORD', '')),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
        ],


        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
