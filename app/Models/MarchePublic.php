<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarchePublic extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['reference','objet','type_marche','categorie','date_publication','date_limite',
       'futured_image','lien','status'
    ];

    public function categories() {
        return $this->belongsToMany(Categorie::class,'marche_publics_categories');          
    }

    public function futured_images() {
        return $this->belongsToMany(Fichier::class,'marche_publics_futured_images');          
    }
}
