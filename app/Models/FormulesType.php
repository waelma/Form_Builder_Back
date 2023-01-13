<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormulesType extends Model
{
    use HasFactory;
    protected $fillable = ['label'];

    protected $table = 'formules_types';
}
