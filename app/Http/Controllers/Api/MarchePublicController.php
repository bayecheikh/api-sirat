<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\MarchePublic;
use App\Models\Categorie;
use App\Models\Fichier;
use App\Models\SousCategorie;

use Mail;
 
use App\Mail\NotifyMail;

class marchePublicController extends Controller
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
            $marchePublics = MarchePublic::with('categories')->with('futured_images')->orderBy("created_at", "desc")->get();
        }

        return response()->json(["success" => true, "message" => "Liste des marchePublics", "data" =>$marchePublics]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function marchePublicMultipleSearch($term)
    {
        if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin_dprs')) {
            $marchePublics = MarchePublic::where('id', 'like', '%'.$term.'%')->orWhere('titre', 'like', '%'.$term.'%')->with('categories')->with('futured_images')->paginate(10);
        }
       
        return response()->json(["success" => true, "message" => "Liste des marchés", "data" => $marchePublics]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activemarchePublic($id)
    {
        $marchePublic = MarchePublic::find($id);

        $message = '';

        if($marchePublic->status=='actif'){
            $message = 'marchePublic desactivé';
            $marchePublic->update([
                'status' => 'inactif'
            ]); 
        }
        else{
            $message = 'marchePublic activé';
            $marchePublic->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $marchePublic]);   
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

        $marchePublic = MarchePublic::create([
            'reference' => $input['reference'],
            'objet' => $input['objet'],
            'type_marche' => $input['type_marche'],
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
                $marchePublic->categories()->attach($categorieObj);
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
            $marchePublic->futured_images()->attach($fichierObj);
        }

        return response()->json(["success" => true, "message" => "marchePublic créé avec succès.", "data" => $marchePublic]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $marchePublic = MarchePublic::find($id);
        $input = $request->all();
        $validator = Validator::make($input, ['objet' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $marchePublic->reference = $input['reference'];
        $marchePublic->objet = $input['objet'];
        $marchePublic->type_marche = $input['type_marche'];
        $marchePublic->categorie = $input['categorie'];       
        $marchePublic->date_publication = $input['date_publication'];
        $marchePublic->date_limite = $input['date_limite'];
        $marchePublic->futured_image = $input['futured_image'];
        $marchePublic->lien = $input['lien'];
        $marchePublic->save();

        $array_categories = explode (",", $input['categories']);
        $old_categories = $marchePublic->categories();

        if(!empty($array_categories)){
            foreach($old_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $marchePublic->categories()->detach($categorieObj);
            }
            foreach($array_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $marchePublic->categories()->attach($categorieObj);
            }
        }

        $old_fichiers = $marchePublic->futured_images();

        if ($request->hasFile('futured_image') && $request->file('futured_image')->isValid()) {

            foreach($old_fichiers as $fichier){
                $fichierObj = Fichier::where('id',$fichier)->first();
                $marchePublic->futured_images()->detach($fichierObj);
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
            $marchePublic->futured_images()->attach($fichierObj);
        }


        return response()
            ->json(["success" => true, "message" => "marchePublic modifié avec succès.", "data" => $marchePublic]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MarchePublic $marchePublic)
    {
        $marchePublic->delete();
        return response()
            ->json(["success" => true, "message" => "marchePublic supprimé avec succès.", "data" => $marchePublic]);
    }
}
