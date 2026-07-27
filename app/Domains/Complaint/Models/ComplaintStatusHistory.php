<?php

namespace App\Domains\Complaint\Models;

use App\Domains\Brand\Concerns\BelongsToBrand;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintStatusHistory extends Model
{
    use BelongsToBrand;

    protected $fillable = [
        'complaint_id',
        'brand_id',
        'from_status',
        'to_status',
        'actor_id',
        'admin_response',
        'admin_notes',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
