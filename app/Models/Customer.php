<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $fillable = [
        'nama', 'alamat', 'no_hp', 'jenis_kelamin', 'email', 'user_id', 'kata_sandi'
    ];

}
