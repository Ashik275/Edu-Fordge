<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class Teacher extends  Authenticatable implements AuthenticatableContract
{
    use HasFactory;
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subjects::class);
    }
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }
}
