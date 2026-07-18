<?php

namespace App\Core\System\Models;

use Illuminate\Database\Eloquent\Model;

class SystemStatus extends Model
{
    protected $fillable = [
        'key',
        'status',
        'payload',
        'last_success_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'last_success_at' => 'datetime',
        ];
    }
}
