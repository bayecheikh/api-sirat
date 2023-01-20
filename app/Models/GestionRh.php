<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionRh extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['reference','objet','secteur','categorie','date_publication','date_limite',
       'futured_image','lien','status'
    ];

    public function categories() {
        return $this->belongsToMany(Categorie::class,'gestion_rhs_categories');          
    }

    public function futured_images() {
        return $this->belongsToMany(Fichier::class,'gestion_rhs_futured_images');          
    }
}
