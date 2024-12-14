<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendee extends Model
{
    protected $guarded = [];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

}
