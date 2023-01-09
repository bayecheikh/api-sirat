<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use App\Models\PostePeage;
use App\Models\Categorie;
use App\Models\Fichier;
use App\Models\SousCategorie;

use Mail;
 
use App\Mail\NotifyMail;

class PostePeageController extends Controller
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
            $poste_peages = PostePeage::orderBy("created_at", "desc")->get();
        }

        return response()->json(["success" => true, "message" => "Liste des poste_peages", "data" =>$poste_peages]);   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function postepeageMultipleSearch($term)
    {
        if ($request->user()->hasRole('super_admin') || $request->user()->hasRole('admin_dprs')) {
            $poste_peages = PostePeage::where('id', 'like', '%'.$term.'%')->orWhere('titre', 'like', '%'.$term.'%')->paginate(10);
        }
       
        return response()->json(["success" => true, "message" => "Liste des postes de péage", "data" => $poste_peages]);   
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activePostepeage($id)
    {
        $poste_peage = PostePeage::find($id);

        $message = '';

        if($poste_peage->status=='actif'){
            $message = 'poste_peage desactivé';
            $poste_peage->update([
                'status' => 'inactif'
            ]); 
        }
        else{
            $message = 'poste_peage activé';
            $poste_peage->update([
                'status' => 'actif'
            ]);
        }

        return response()->json(["success" => true, "message" => $message, "data" => $poste_peage]);   
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
        $validator = Validator::make($input, ['titre' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }

        $poste_peage = PostePeage::create([
            'titre' => $input['titre'],
            'slug' => $input['slug'],
            'latitude' => $input['latitude'],       
            'longitude' => $input['longitude']
        ]);

        return response()->json(["success" => true, "message" => "poste_peage créé avec succès.", "data" => $poste_peage]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $poste_peage = PostePeage::get()->find($id);
        if (is_null($poste_peage))
        {
        /* return $this->sendError('Product not found.'); */
            return response()
            ->json(["success" => true, "message" => "poste_peage introuvable."]);
        }
        return response()
            ->json(["success" => true, "message" => "poste_peage trouvé avec succès.", "data" => $poste_peage]);
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
        $poste_peage = PostePeage::find($id);
        $input = $request->all();
        $validator = Validator::make($input, ['titre' => 'required']);
        if ($validator->fails())
        {
            //return $this->sendError('Validation Error.', $validator->errors());
            return response()
            ->json($validator->errors());
        }


        $poste_peage->titre = $input['titre'];
        $poste_peage->slug = $input['slug'];
        $poste_peage->latitude = $input['latitude'];
        $poste_peage->longitude = $input['longitude'];
        $poste_peage->save();

        return response()
            ->json(["success" => true, "message" => "poste_peage modifié avec succès.", "data" => $poste_peage]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostePeage $poste_peage)
    {
        $poste_peage->delete();
        return response()
            ->json(["success" => true, "message" => "poste_peage supprimé avec succès.", "data" => $poste_peage]);
    }
}
