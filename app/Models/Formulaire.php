<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Champ;

class Formulaire extends Model
{
    use HasFactory;
    protected $fillable = ['label', 'created_by'];
    protected $table = 'formulaires';

    public function champs()
    {
        return $this->hasMany(Champ::class, 'formulaire_id', 'id');
    }
}
