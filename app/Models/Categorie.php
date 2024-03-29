<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['libelle','slug',
       'futured_image','status'
    ];
    public function futured_images() {
        return $this->belongsToMany(Fichier::class,'categories_futured_images');          
    }
    public function contenus() {
        return $this->belongsToMany(Contenu::class,'categories_contenus');          
    }
    public function marche_publics() {
        return $this->belongsToMany(Contenu::class,'marche_publics_categories');          
    }
    public function gestion_rhs() {
        return $this->belongsToMany(Contenu::class,'gestion_rhs_categories');          
    }
    
}
