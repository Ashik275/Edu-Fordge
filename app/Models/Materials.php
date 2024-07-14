<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materials extends Model
{
    use HasFactory;
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subjects::class);
    }
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}
