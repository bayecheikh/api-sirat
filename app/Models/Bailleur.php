<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bailleur extends Model
{
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'libelle','status'
    ];

    public function investissements() {
        return $this->belongsToMany(Investissement::class,'investissements_bailleurs');          
    }
    public function ligne_financements() {
        return $this->belongsToMany(LigneFinancement::class,'bailleurs_ligne_financements');          
    }
}
