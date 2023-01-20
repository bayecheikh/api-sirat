<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\DepartementController;
use App\Http\Controllers\Api\StructureController;
use App\Http\Controllers\Api\DimensionController;
use App\Http\Controllers\Api\TypeZoneInterventionController;
use App\Http\Controllers\Api\SourceFinancementController;
use App\Http\Controllers\Api\TypeSourceController;
use App\Http\Controllers\Api\AxeController;
use App\Http\Controllers\Api\InvestissementController;
use App\Http\Controllers\Api\RechercheInvestissementController;
use App\Http\Controllers\Api\ExportInvestissementController;
use App\Http\Controllers\Api\LigneFinancementController;
use App\Http\Controllers\Api\ModeFinancementController;
use App\Http\Controllers\Api\PilierController;
use App\Http\Controllers\Api\TypeLigneController;
use App\Http\Controllers\Api\MonnaieController;
use App\Http\Controllers\Api\AnneeController;
use App\Http\Controllers\Api\BailleurController;
use App\Http\Controllers\Api\LigneModeInvestissementController;
use App\Http\Controllers\Api\ProfilController;
use App\Http\Controllers\Api\DemandeController;
use App\Http\Controllers\Api\StatistiqueController;
use App\Http\Controllers\Api\ContenuController;
use App\Http\Controllers\Api\PostePeageController;
use App\Http\Controllers\Api\MarchePublicController;
use App\Http\Controllers\Api\GestionRhController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\SousCategorieController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
 
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forget_password', [AuthController::class, 'forget_password']);

 /**Statistique*/
 Route::get('allcategories', [StatistiqueController::class, 'allcategories']);
 Route::get('allcontenus', [StatistiqueController::class, 'allcontenus']);
 Route::get('allcontenus/{id}', [StatistiqueController::class, 'contenuById']);
 Route::get('allpostepeages', [StatistiqueController::class, 'allpostepeages']);
 Route::get('allpostepeages/{id}', [StatistiqueController::class, 'poste_peageById']);
 Route::get('allmarchepublics', [StatistiqueController::class, 'allmarchepublics']);
 Route::get('allmarchepublics/{id}', [StatistiqueController::class, 'marchepublicById']);
 Route::get('allgestionrhs', [StatistiqueController::class, 'allgestionrhs']);
 Route::get('allgestionrhs/{id}', [StatistiqueController::class, 'gestionrhById']);
 
 Route::get('allStats', [StatistiqueController::class, 'allStats']);
 Route::get('allPiliers', [StatistiqueController::class, 'allPilier']);
 Route::get('allAxes', [StatistiqueController::class, 'allAxe']);
 Route::get('allAnnees', [StatistiqueController::class, 'allAnnee']);
 Route::get('allRegions', [StatistiqueController::class, 'allRegion']);
 Route::get('allMonnaies', [StatistiqueController::class, 'allMonnaie']);
 Route::get('allStructures', [StatistiqueController::class, 'allStructure']);
 Route::get('allDimensions', [StatistiqueController::class, 'allDimension']);
 Route::get('allSources', [StatistiqueController::class, 'allSource']);

 Route::get('investissementByPilier/{idPilier}', [StatistiqueController::class, 'investissementByPilier']);
 Route::get('investissementByAxe/{idAxe}', [StatistiqueController::class, 'investissementByAxe']);
 Route::get('investissementByAnnee/{idAnnee}', [StatistiqueController::class, 'investissementByAnnee']);
 Route::get('investissementByRegion/{idRegion}', [StatistiqueController::class, 'investissementByRegion']);
 Route::get('investissementByMonnaie/{idMonnaie}', [StatistiqueController::class, 'investissementByMonnaie']);
 Route::get('investissementByStructure/{idStructure}', [StatistiqueController::class, 'investissementByStructure']);
 Route::get('investissementByDimension/{idDimension}', [StatistiqueController::class, 'investissementByDimension']);
 Route::get('investissementBySource/{idSource}', [StatistiqueController::class, 'investissementBySource']);
 Route::post('ajoutDemande', [DemandeController::class, 'ajoutDemande']); 

Route::middleware('auth:api')->group(function () {
    Route::resource('products', ProductController::class);

    /**Gestion des authentification */
    Route::get('get-user', [AuthController::class, 'userInfo']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update_password', [AuthController::class, 'update_password']);

    /**Gestion des utilisateurs */
    Route::resource('users', UserController::class);
    Route::get('user-multiple-search/{term}', [UserController::class, 'userMultipleSearch']);
    Route::get('active_user/{id}', [UserController::class, 'activeUser']);

    /**Gestion des roles */
    Route::resource('roles', RoleController::class);

    /**Gestion des permissions */
    Route::resource('permissions', PermissionController::class);

    /**Gestion des contenus */
    Route::resource('contenus', ContenuController::class);
    Route::get('contenu-multiple-search/{term}', [ContenuController::class, 'contenuMultipleSearch']);
    Route::get('active_contenu/{id}', [ContenuController::class, 'activeContenu']);

    /**Gestion des marchés publics */
    Route::resource('marchepublics', MarchePublicController::class);
    Route::get('marchepublic-multiple-search/{term}', [MarchePublicController::class, 'marchePublicMultipleSearch']);
    Route::get('active_marchepublic/{id}', [MarchePublicController::class, 'activeMarchePublic']);

    /**Gestion des contenus */
    Route::resource('gestionrhs',GestionRhController::class);
    Route::get('gestionrh-multiple-search/{term}', [GestionRhController::class, 'gestionRhMultipleSearch']);
    Route::get('active_gestionrh/{id}', [GestionRhController::class, 'activeGestionRh']);

    /**Gestion des poste de péages */
    Route::resource('postepeages', PostePeageController::class);
    Route::get('postepeage-multiple-search/{term}', [PostePeageController::class, 'postepeageMultipleSearch']);
    Route::get('active_postepeage/{id}', [PostePeageController::class, 'activePostepeage']);

    /**Gestion des catégories */
    Route::resource('categories', CategorieController::class);

    /**Gestion des sous catégorie */
    Route::resource('sous_categorie', SousCategorieController::class);

    /**Gestion des regions */
    Route::resource('regions', RegionController::class);

    /**Gestion des departements */
    Route::resource('departements', DepartementController::class);

    /**Gestion des structures */
    Route::resource('structures', StructureController::class);
    Route::get('structure-multiple-search/{term}', [StructureController::class, 'structureMultipleSearch']);
    Route::get('selectstructures', [StructureController::class, 'selectstructure']);

    /**Gestion des dimensions */
    Route::resource('dimensions', DimensionController::class);

    /**Gestion des types de zone */
    Route::resource('type_zones', TypeZoneInterventionController::class);

    /**Gestion des sources de financement */
    Route::resource('source_financements', SourceFinancementController::class);

    /**Gestion des types de source */
    Route::resource('type_sources', TypeSourceController::class);

    /**Gestion des axes */
    Route::resource('axes', AxeController::class);

    /**Gestion des investissements */
    Route::resource('investissements', InvestissementController::class);
    Route::post('validation_investissement', [InvestissementController::class, 'validation_investissement']);
    Route::post('rejet_investissement', [InvestissementController::class, 'rejet_investissement']);

    /**Recherche avancée sur les investissements */
    Route::resource('recherche_avances', InvestissementController::class);
    Route::post('recherche_avance_investissements', [RechercheInvestissementController::class, 'recherche']);

    /**Gestion des lignes de financement */
    Route::resource('ligne_financements', LigneFinancementController::class);
    Route::post('recherche_ligne_financements', [LigneFinancementController::class, 'recherche']);
    Route::post('export_csv_ligne_financements', [ExportInvestissementController::class, 'exportCSV']);
    Route::post('export_pdf_ligne_financements', [ExportInvestissementController::class, 'exportPDF']);

    /**Gestion des modes de financement */
    Route::resource('mode_financements', ModeFinancementController::class);

    /**Gestion des piliers */
    Route::resource('piliers', PilierController::class);

    /**Gestion des types de ligne */
    Route::resource('type_lignes', TypeLigneController::class);

    /**Gestion des monnaies */
    Route::resource('monnaies', MonnaieController::class);

    /**Gestion des annees */
    Route::resource('annees', AnneeController::class);

    /**Gestion des bailleurs */
    Route::resource('bailleurs', BailleurController::class);

    /**Gestion des lignes mode investissements */
    Route::resource('ligne_mode_investissements', LigneModeInvestissementController::class);

    /**Gestion des demandes */
    Route::resource('demandes', DemandeController::class);
    Route::get('demande-multiple-search/{term}', [DemandeController::class, 'demandeMultipleSearch']);
    Route::get('active_demande/{id}', [DemandeController::class, 'activeDemande']);

    /**Gestion des profils */
    Route::resource('profils', ProfilController::class);
    /**Statistique*/
    Route::resource('statistiques', LigneModeInvestissementController::class);
});
