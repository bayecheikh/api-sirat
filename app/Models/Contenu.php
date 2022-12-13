<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contenu extends Model
{
    use HasFactory;
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = ['id_categorie','titre','resume','body',
       'futured_image','status'
    ];
              
    public function categories() {
        return $this->belongsToMany(Categorie::class,'contenus_categories');          
    }

    public function futured_images() {
        return $this->belongsToMany(Fichier::class,'contenus_fichiers');          
    }
}
