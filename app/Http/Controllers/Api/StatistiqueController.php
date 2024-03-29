<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TypeZoneIntervention;
use App\Models\TypeSource;
use App\Models\SourceFinancement;
use Validator;

use App\Models\Role;
use App\Models\Permission;

use App\Models\Investissement;
use App\Models\User;
use App\Models\Fichier;
use App\Models\Structure;
use App\Models\Annee;
use App\Models\Monnaie;
use App\Models\LigneFinancement;
use App\Models\ModeFinancement;
use App\Models\LigneModeInvestissement;
use App\Models\Dimension;
use App\Models\Region;
use App\Models\Departement;
use App\Models\Pilier;
use App\Models\Axe;
use App\Models\Categorie;
use App\Models\Contenu;
use App\Models\PostePeage;
use App\Models\MarchePublic;
use App\Models\GestionRh;

class StatistiqueController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
       return '';    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allcategories()
    {
        $categories = Categorie::get();
        return response()->json(["success" => true, "message" => "Liste des catégories", "data" => $categories]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allcontenus()
    {
        $contenus = Contenu::with('categories')->with('futured_images')->get();
        return response()->json(["success" => true, "message" => "Liste des contenus", "data" => $contenus]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contenuById($id){
        $contenu = Contenu::with('categories')->with('futured_images')->get()->find($id);
        if (is_null($contenu))
        {
        /* return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Contenu introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Contenu trouvé avec succès.", "data" => $contenu]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allmarchepublics()
    {
        $marchePublics = MarchePublic::with('categories')->with('futured_images')->get();
        return response()->json(["success" => true, "message" => "Liste des marchePublics", "data" => $marchePublics]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function marchePublicById($id){
        $marchePublic = MarchePublic::with('categories')->with('futured_images')->get()->find($id);
        if (is_null($marchePublic))
        {
        /* return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "marchePublic introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "marchePublic trouvé avec succès.", "data" => $marchePublic]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allgestionrhs()
    {
        $gestionRhs = GestionRh::with('categories')->with('futured_images')->get();
        return response()->json(["success" => true, "message" => "Liste des offres", "data" => $gestionRhs]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestionRhById($id){
        $gestionRh = GestionRh::with('categories')->with('futured_images')->get()->find($id);
        if (is_null($gestionRh))
        {
        /* return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "Offre introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "Offre trouvé avec succès.", "data" => $gestionRh]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allpostepeages()
    {
        $post_peages = PostePeage::get();
        return response()->json(["success" => true, "message" => "Liste des post_peages", "data" =>  $post_peages]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function poste_peageById($id){
        $contenu = PostePeage::get()->find($id);
        if (is_null($post_peage))
        {
        /* return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "post_peage introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "post_peage trouvé avec succès.", "data" => $contenu]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allPilier()
    {
        $piliers = Pilier::with('axes')->get();
        return response()->json(["success" => true, "message" => "Liste des piliers", "data" => $piliers]);
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function investissementByPilier($idPilier){
        $investissements = Investissement::with('region')
            ->with('annee')
            ->with('monnaie')
            ->with('structure')
            ->with('dimension')
            ->with('piliers')
            ->with('axes')
            ->with('mode_financements')
            ->with('ligne_financements')
            ->with('fichiers')
            
            ->whereHas('piliers', function($q) use ($idPilier){
            $q->where('id', $idPilier);
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par pilier", "data" =>$investissements,"total" =>$total]);
    }
    public function investissementByAxe($idAxe){
        $investissements = Investissement::with('region')
            ->with('annee')
            ->with('monnaie')
            ->with('structure')
            ->with('dimension')
            ->with('piliers')
            ->with('axes')
            ->with('mode_financements')
            ->with('ligne_financements')
            ->with('fichiers')
            
            ->whereHas('axes', function($q) use ($idAxe){
            $q->where('id', $idAxe);
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par axe", "data" =>$investissements,"total" =>$total]);
    }
    public function investissementByAnnee($idAnnee){
        $investissements = Investissement::with('region')
        ->with('annee')
        ->with('monnaie')
        ->with('structure')
        ->with('dimension')
        ->with('piliers')
        ->with('axes')
        ->with('mode_financements')
        ->with('ligne_financements')
        ->with('fichiers')
        
        ->whereHas('annee', function($q) use ($idAnnee){
        $q->where('id', $idAnnee);
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par annee", "data" =>$investissements,"total" =>$total]);  
    }
    public function investissementByRegion($idRegion){
        $investissements = Investissement::with('region')
        ->with('annee')
        ->with('monnaie')
        ->with('structure')
        ->with('dimension')
        ->with('piliers')
        ->with('axes')
        ->with('mode_financements')
        ->with('ligne_financements')
        ->with('fichiers')
        
        ->whereHas('region', function($q) use ($idRegion){
        $q->where('id', $idRegion);
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par region", "data" =>$investissements,"total" =>$total]);
    }
    public function investissementByMonnaie($idMonnaie){
        $investissements = Investissement::with('region')
        ->with('annee')
        ->with('monnaie')
        ->with('structure')
        ->with('dimension')
        ->with('piliers')
        ->with('axes')
        ->with('mode_financements')
        ->with('ligne_financements')
        ->with('fichiers')
        
        ->whereHas('monnaie', function($q) use ($idMonnaie){
        $q->where('id', $idMonnaie);
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par monnaie", "data" =>$investissements,"total" =>$total]);
        
    }
    public function investissementByStructure($idStructure){
        $investissements = Investissement::with('region')
        ->with('annee')
        ->with('monnaie')
        ->with('structure')
        ->with('dimension')
        ->with('piliers')
        ->with('axes')
        ->with('mode_financements')
        ->with('ligne_financements')
        ->with('fichiers')
        
        ->whereHas('structure', function($q) use ($idStructure){
        $q->where('id', $idStructure);
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par structure", "data" =>$investissements,"total" =>$total]);  
    }
    public function investissementByDimension($idDimension){
        $investissements = Investissement::with('region')
        ->with('annee')
        ->with('monnaie')
        ->with('structure')
        ->with('dimension')
        ->with('piliers')
        ->with('axes')
        ->with('mode_financements')
        ->with('ligne_financements')
        ->with('fichiers')
        
        ->whereHas('dimension', function($q) use ($idDimension){
        $q->where('id', $idDimension);
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par dimension", "data" =>$investissements,"total" =>$total]);
    }
    public function investissementBySource($idSource){
        $investissements = Investissement::with('region')
        ->with('annee')
        ->with('monnaie')
        ->with('structure')
        ->with('dimension')
        ->with('piliers')
        ->with('axes')
        ->with('mode_financements')
        ->with('ligne_financements')
        ->with('fichiers')
        
        ->whereHas('structure', function($q) use ($idSource){
        $q->whereHas('source_financements', function($q) use ($idSource){
            $q->where('id', $idSource);
            });
        })->paginate(0);
        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des investissements par structure", "data" =>$investissements,"total" =>$total]); 
    }

    public function allStats(){
        $status = 'publie';
        $investissements = LigneFinancement::with('investissement')
        ->with('pilier')
        ->with('axe')
        ->with('structure_source')
        ->with('type_structure_source')
        ->with('structure_beneficiaire')
        ->with('region')
        ->with('structure')
        ->with('annee')
        ->with('monnaie')
        ->with('dimension')
        ->whereHas('investissement', function($q) use ($status){
            $q->where('status', 'like', '%publie%');
        })->paginate(0); 
        $investissements -> load('investissement.mode_financements');

        $total = $investissements->total();
        return response()->json(["success" => true, "message" => "Liste des lignes financements", "data" =>$investissements,"total" =>$total]); 
    }

    
    
}
