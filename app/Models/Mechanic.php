<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mechanic extends Model
{
    use HasFactory;

    protected $table = 'mechanics';
    protected $fillable = [
        'nama', 'alamat', 'no_hp', 'jenis_kelamin', 'gambar', 'user_id', 'kata_sandi', 'mesin', 'ac', 'body', 'interior'
    ];

}
