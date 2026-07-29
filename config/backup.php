<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Backup destinations
    |--------------------------------------------------------------------------
    |
    | Both paths must remain outside the public document root.
    |
    */

    'root' => env(
        'BACKUP_ROOT',
        storage_path('app/private/backups'),
    ),

    'work_root' => env(
        'BACKUP_WORK_ROOT',
        storage_path('app/private/backup-work'),
    ),

    /*
    |--------------------------------------------------------------------------
    | Backup scope
    |--------------------------------------------------------------------------
    */

    'include_public_storage' => (bool) env(
        'BACKUP_INCLUDE_PUBLIC_STORAGE',
        true,
    ),

    'public_storage_path' => storage_path('app/public'),

    /*
    |--------------------------------------------------------------------------
    | Retention
    |--------------------------------------------------------------------------
    */

    'retention_days' => max(
        1,
        (int) env('BACKUP_RETENTION_DAYS', 14),
    ),

    'minimum_backups' => max(
        1,
        (int) env('BACKUP_MINIMUM_BACKUPS', 3),
    ),

    /*
    |--------------------------------------------------------------------------
    | Executables
    |--------------------------------------------------------------------------
    */

    'mysqldump_binary' => env(
        'MYSQLDUMP_BINARY',
        '/usr/bin/mysqldump',
    ),

    'mysql_binary' => env(
        'MYSQL_BINARY',
        '/usr/bin/mysql',
    ),

    'gzip_binary' => env(
        'GZIP_BINARY',
        '/usr/bin/gzip',
    ),

    'tar_binary' => env(
        'TAR_BINARY',
        '/usr/bin/tar',
    ),
];
