<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\GestionRh;
use App\Models\Categorie;
use App\Models\Fichier;
use App\Models\SousCategorie;

use Mail;
 
use App\Mail\NotifyMail;

class GestionRhController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        //$this->middleware('role:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin_dprs')) {
            $gestionRhs = GestionRh::with('categories')->with('futured_images')->orderBy("created_at", "desc")->get();
        }

        return response()->json(["success" => true, "message" => "Liste des gestionRhs", "data" =>$gestionRhs]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function gestionRhMultipleSearch($term)
    {
        if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin_dprs')) {
            $gestionRhs = GestionRh::where('id', 'like', '%'.$term.'%')->orWhere('titre', 'like', '%'.$term.'%')->with('categories')->with('futured_images')->paginate(10);
        }
       
        return response()->json(["success" => true, "message" => "Liste des offres", "data" => $gestionRhs]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activegestionRh($id)
    {
        $gestionRh = GestionRh::find($id);

        $message = '';

        if($gestionRh->status=='actif'){
            $message = 'gestionRh desactivé';
            $gestionRh->update([
                'status' => 'inactif'
            ]); 
        }
        else{
            $message = 'gestionRh activé';
            $gestionRh->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $gestionRh]);   
    }
    /**
     * Store a newly created resource in storagrolee.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['objet' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $gestionRh = GestionRh::create([
            'reference' => $input['reference'],
            'objet' => $input['objet'],
            'secteur' => $input['secteur'],
            'categorie' => $input['categorie'],       
            'date_publication' => $input['date_publication'],
            'date_limite' => $input['date_limite'],
            'futured_image' => $input['futured_image'],
            'lien' => $input['lien']
        ]);

        $array_categories = explode (",", $input['categories']);

        if(!empty($array_categories)){
            foreach($array_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $gestionRh->categories()->attach($categorieObj);
            }
        }

        if ($request->hasFile('futured_image') && $request->file('futured_image')->isValid()) {
            $upload_path = public_path('upload');
            $file = $request->file('futured_image');
            $file_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $url_file = $upload_path . '/' . $file_name;
            $generated_new_name = 'futured_image_' . time() . '.' . $file_extension;
            $file->move($upload_path, $generated_new_name);

            $fichierObj = Fichier::create([
                'name' => $generated_new_name,
                'url' => $url_file,
                'extension' => $file_extension,
                'description' => 'Futured Image'
            ]);
            $gestionRh->futured_images()->attach($fichierObj);
        }

        return response()->json(["success" => true, "message" => "Offre créé avec succès.", "data" => $gestionRh]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $gestionRh = GestionRh::find($id);
        $input = $request->all();
        $validator = Validator::make($input, ['objet' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $gestionRh->reference = $input['reference'];
        $gestionRh->objet = $input['objet'];
        $gestionRh->secteur = $input['secteur'];
        $gestionRh->categorie = $input['categorie'];       
        $gestionRh->date_publication = $input['date_publication'];
        $gestionRh->date_limite = $input['date_limite'];
        $gestionRh->futured_image = $input['futured_image'];
        $gestionRh->lien = $input['lien'];
        $gestionRh->save();

        $array_categories = explode (",", $input['categories']);
        $old_categories = $gestionRh->categories();

        if(!empty($array_categories)){
            foreach($old_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $gestionRh->categories()->detach($categorieObj);
            }
            foreach($array_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $gestionRh->categories()->attach($categorieObj);
            }
        }

        $old_fichiers = $gestionRh->futured_images();

        if ($request->hasFile('futured_image') && $request->file('futured_image')->isValid()) {

            foreach($old_fichiers as $fichier){
                $fichierObj = Fichier::where('id',$fichier)->first();
                $gestionRh->futured_images()->detach($fichierObj);
            }
            
            $upload_path = public_path('upload');
            $file = $request->file('futured_image');
            $file_name = $file->getClientOriginalName();
            $file_extension = $file->getClientOriginalExtension();
            $url_file = $upload_path . '/' . $file_name;
            $generated_new_name = 'futured_image_' . time() . '.' . $file_extension;
            $file->move($upload_path, $generated_new_name);

            $fichierObj = Fichier::create([
                'name' => $generated_new_name,
                'url' => $url_file,
                'extension' => $file_extension,
                'description' => 'Futured Image'
            ]);
            $gestionRh->futured_images()->attach($fichierObj);
        }


        return response()
            ->json(["success" => true, "message" => "Offre modifié avec succès.", "data" => $gestionRh]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GestionRh $gestionRh)
    {
        $gestionRh->delete();
        return response()
            ->json(["success" => true, "message" => "Offre supprimé avec succès.", "data" => $gestionRh]);
    }
}
