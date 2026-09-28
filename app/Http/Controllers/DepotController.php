<?php

namespace App\Http\Controllers;
use App\Models\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepotController extends Controller
{
     // Afficher la liste des dépôts
    public function index(Request $request)
    {
        $user = auth()->user();
        $pageTitle = 'Liste Point de dépot';
        $breadcrumb = ['Points de Dépot','Liste Point de dépot'];
       // $depots=Depot::where('statut','inactif')->orwhere('statut','actif')->paginate(4);
        // $depots = Depot::all(); // Récupérer tous les dépôts
        $statuts=Depot::select('statut')->distinct()->pluck('statut');
        $statut= $request->input('statut', '');
        $perPage = $request->input('perPage', 3); // 10 est la valeur par défaut
        if($statut==''){
            $depots = Depot::paginate($perPage);  
        }else{
            $depots = Depot::where('statut', $statut)->paginate($perPage);   
        }
        
        if($user->role=="superviseur"){
            return view('depot.index', compact('depots','pageTitle','breadcrumb','perPage','statuts','statut'));
        }elseif ($user->role=="gerant") {
            return view('gerant.listedepot', compact('depots','pageTitle','breadcrumb','perPage','statuts','statut'));
        }else{
            return view('user.listdepot', compact('depots','pageTitle','breadcrumb','perPage','statuts','statut')); 
        }
    }

    // Afficher le formulaire de création d'un nouveau dépôt
    public function create()
    {
        $pageTitle = 'Nouveau Point de dépot';
        $breadcrumb = ['Points de dépot','Nouveau Point'];
        return view('depot.create',compact('pageTitle','breadcrumb')); // Vue pour créer un nouveau dépôt
    }

    // Enregistrer un nouveau dépôt
    public function store(Request $request)
    {
        // dd($request->name);
      // Validation des données
    $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'opening_hours' => 'nullable|string|max:255',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'contact' => 'nullable|string|max:255',
    ]);

    // Création du dépôt avec le statut inactif
    Depot::create([
        'name' => $request->name,
        'address' => $request->address,
        'opening_hours' => $request->opening_hours,
        'latitude' => $request->latitude,
        'longitude' => $request->longitude,
        'contact' => $request->contact,
        'gerant_id' => null, // Pas de gérant à la création
        'statut' => 'inactif', // Statut par défaut
    ]);

    // Redirection avec message de succès
    return redirect()->route('depot.index')->with('success', 'Dépôt créé avec succès.');
    }

    // Afficher les détails d'un dépôt spécifique
    public function show($id)
    {
        $depot = Depot::findOrFail($id);
        $pageTitle = 'Detail dépot';
        $breadcrumb = ['Detail dépot'];
        return view('depot.show', compact('depot','pageTitle','breadcrumb')); // Vue pour afficher les détails d'un dépôt
    }

    // Afficher le formulaire d'édition d'un dépôt
    public function edit($id)
    {
        $depot = Depot::findOrFail($id);
        $pageTitle = 'Modification dépot';
        $breadcrumb = ['Liste Depot','Modification Depot'];
        return view('depot.edit', compact('depot','pageTitle','breadcrumb')); // Vue pour éditer un dépôt
    }

    // Mettre à jour un dépôt existant
    public function update(Request $request, $id)
    {

         // Valider les données du formulaire
    $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'contact' => 'required|string|max:255',
        'opening_hours' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
    ]);

    // Récupérer le dépôt par son ID
    $depot = Depot::findOrFail($id);

    // Mettre à jour les données
    $depot->update($request->all());

    // Rediriger vers la page de détails avec un message de succès
    return redirect()->route('depot.show', $depot->id)->with('success', 'Dépôt mis à jour avec succès.');
    }

    // Supprimer un dépôt
    public function destroy($id)
    {
        $depot = Depot::findOrFail($id);
        $depot->delete(); // Supprimer le dépôt
        return redirect()->route('depot.index')->with('success', 'Dépôt supprimé avec succès.');
    }
}
