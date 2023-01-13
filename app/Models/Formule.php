<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Champ;
use App\Models\FormulesType;
class Formule extends Model
{
    use HasFactory;
    protected $fillable = ['champ_id', 'type_id', 'reference', 'poids'];
    protected $table = 'formules';

    public function champ()
    {
        return $this->belongsTo(Champ::class, 'champ_id', 'id');
    }
    public function formules_type()
    {
        return $this->belongsTo(FormulesType::class, 'type_id', 'id');
    }
}
