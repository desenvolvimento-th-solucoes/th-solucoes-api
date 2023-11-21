<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Device extends Model
{
    protected $table = "low_caution_password";

    protected $fillable = [
        "user_id", "device", "observations", "password"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    use HasFactory;
}
