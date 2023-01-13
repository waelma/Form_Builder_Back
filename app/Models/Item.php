<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Champ;
class Item extends Model
{
    use HasFactory;
    protected $fillable = ['champ_id', 'label', 'poids'];
    protected $table = 'items';

    public function champ()
    {
        return $this->belongsTo(Champ::class, 'champ_id', 'id');
    }
}
