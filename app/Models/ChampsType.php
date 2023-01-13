<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChampsType extends Model
{
    use HasFactory;
    protected $fillable = ['label'];

    protected $table = 'champs_types';
}
