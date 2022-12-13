<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Categorie;

class CategorieController extends Controller
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
    public function index()
    {
 
        $categories = Categorie::with('contenus')->with('futured_images')->orderBy("libelle", "asc")->get();
        return response()->json(["success" => true, "message" => "Liste des categories", "data" => $categories]);

        
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $categorie = Categorie::create($input);

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
            $categorie->futured_images()->attach($fichierObj);
        }

        return response()->json(["success" => true, "message" => "Categorie créée avec succès.", "data" => $categorie]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $categorie = Categorie::with('contenus')->with('futured_images')->find($id);
        if (is_null($categorie))
        {
   /*          return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "categorie introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "categorie retrouvée avec succès.", "data" => $categorie]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categorie $categorie)
    {
        $input = $request->all();
        $validator = Validator::make($input, ['libelle' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }
        $categorie->libelle = $input['libelle'];

        $old_fichiers = $categorie->futured_images();

        if ($request->hasFile('futured_image') && $request->file('futured_image')->isValid()) {

            foreach($old_fichiers as $fichier){
                $fichierObj = Fichier::where('id',$fichier)->first();
                $categorie->fichiers()->detach($fichierObj);
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
            $categorie->futured_images()->attach($fichierObj);
        }

        $categorie->save();
        return response()
            ->json(["success" => true, "message" => "categorie modifiée avec succès.", "data" => $categorie]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categorie $categorie)
    {
        $categorie->delete();
        return response()
            ->json(["success" => true, "message" => "categorie supprimée avec succès.", "data" => $categorie]);
    }
}
