<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Contenu;
use App\Models\Categorie;
use App\Models\SousCategorie;

use Mail;
 
use App\Mail\NotifyMail;

class ContenuController extends Controller
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
            $contenus = Contenu::with('categories')->with('futured_images')->orderBy("created_at", "desc")->get();
        }

        return response()->json(["success" => true, "message" => "Liste des contenus", "data" =>$contenus]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contenuMultipleSearch($term)
    {
        if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin_dprs')) {
            $contenus = Contenu::where('id', 'like', '%'.$term.'%')->orWhere('titre', 'like', '%'.$term.'%')->with('categories')->with('futured_images')->paginate(10);
        }
       
        return response()->json(["success" => true, "message" => "Liste des utilisateurs", "data" => $contenus]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeContenu($id)
    {
        $contenu = Contenu::find($id);

        $message = '';

        if($contenu->status=='actif'){
            $message = 'Contenu desactivé';
            $contenu->update([
                'status' => 'inactif'
            ]); 
        }
        else{
            $message = 'Contenu activé';
            $contenu->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $contenu]);   
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
        $validator = Validator::make($input, ['titre' => 'required','resume' => 'required', 'body' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $contenu = Contenu::create([
            'titre' => $input['titre'],
            'resume' => $input['resume'],
            'body' => $input['body']
        ]);


        $array_categories = $request->categories;

        if(!empty($array_categories)){
            foreach($array_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $contenu->categories()->attach($categorieObj);
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
            $contenu->futured_images()->attach($fichierObj);
        }

        return response()->json(["success" => true, "message" => "Contenu créé avec succès.", "data" => $contenu]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contenu $contenu)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['titre' => 'required','resume' => 'required', 'body' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $contenu->id_categorie = $request->categories[0];
        $contenu->titre = $input['titre'];
        $contenu->resume = $input['resume'];
        $contenu->resume = $input['resume'];
        $contenu->body = $input['body'];
        $contenu->save();

        $array_categories = $request->categories;
        $old_categories = $contenu->categories();

        if(!empty($array_categories)){
            foreach($old_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $contenu->categories()->detach($categorieObj);
            }
            foreach($array_categories as $categorie){
                $categorieObj = Categorie::where('id',$categorie)->first();
                $contenu->categories()->attach($categorieObj);
            }
        }

        $old_fichiers = $contenu->futured_images();

        if ($request->hasFile('futured_image') && $request->file('futured_image')->isValid()) {

            foreach($old_fichiers as $fichier){
                $fichierObj = Fichier::where('id',$fichier)->first();
                $contenu->fichiers()->detach($fichierObj);
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
            $contenu->fichiers()->attach($fichierObj);
        }


        return response()
            ->json(["success" => true, "message" => "Contenu modifié avec succès.", "data" => $contenu]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(contenu $contenu)
    {
        $contenu->delete();
        return response()
            ->json(["success" => true, "message" => "Contenu supprimé avec succès.", "data" => $contenu]);
    }
}
