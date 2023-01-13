<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Formulaire;
use App\Models\ChampsType;
use App\Models\Item;
class Champ extends Model
{
    use HasFactory;
    protected $fillable = [
        'formulaire_id',
        'type_id',
        'label',
        'poids',
        'required',
    ];
    protected $table = 'champs';

    public function formulaire()
    {
        return $this->belongsTo(Formulaire::class, 'formulaire_id', 'id');
    }
    public function champs_type()
    {
        return $this->belongsTo(ChampsType::class, 'type_id', 'id');
    }
    public function items()
    {
        return $this->hasMany(Item::class, 'champ_id', 'id');
    }
    public function formules()
    {
        return $this->hasMany(Formule::class, 'champ_id', 'id');
    }
}
