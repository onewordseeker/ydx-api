<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallets extends Model
{
    use HasFactory;
    protected $fillable = [
        'mnemonics',
        'wallet_address',
        'private_key',
        'chain',
        'user_id',
        'created_at',
        'updated_at'
    ];

}
