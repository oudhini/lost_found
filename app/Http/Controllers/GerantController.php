<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Depot;
use App\Events\DepotAssigned;
use Illuminate\Support\Facades\Auth;

class GerantController extends Controller
{
 // Afficher le formulaire de création d'un gérant
 public function create()
 {
    $pageTitle = 'Nouveau Gerant';
    $breadcrumb = ['Gerants','Nouveau Gerant'];
    $inactiveDepots=Depot::where('statut','inactif')->get();
     return view('gerant.create',compact('pageTitle','breadcrumb','inactiveDepots')); // Créez une vue pour le formulaire
 }

 // Enregistrer un nouveau gérant
 public function store(Request $request)
 { 
     $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:users,email',
         'phone' => 'nullable|string|max:255',
         'password' => 'required|string|min:8|confirmed',
     ]);

     $manager = User::create([
         'name' => $request->name,
         'email' => $request->email,
         'phone' => $request->phone,
         'password' => Hash::make($request->password),
         'role' => 'gerant', // Définir le rôle comme gérant
     ]);
     // Attribution du dépôt inactif
    $depot = Depot::find($request->depot_id);
    if ($depot) {
        $depot->gerant_id=$manager->id;
        $depot->save();

        // Déclencher l'événement
        event(new DepotAssigned($manager, $depot));
    }

     return redirect()->route('gerant.index')->with('success', 'Gérant créé avec succès et dépot assigné.');
 }

 // Afficher la liste des gérants
 public function index()
 {
    $pageTitle = 'Liste Gerants';
    $breadcrumb = ['Gerants','Liste Gerants'];
     $managers = User::where('role', 'gerant')->get();
     return view('gerant.index', compact('managers','pageTitle','breadcrumb'));
 }

 // Afficher les informations d'un gérant
 public function show($id)
 {  $pageTitle = 'Détails';
    $breadcrumb = ['Gerants','Liste Gerants','Détails'];
    $manager = User::findOrFail($id);
     return view('gerant.show', compact('manager','pageTitle','breadcrumb'));
 }

 // Afficher le formulaire d'édition d'un gérant
 public function edit($id)
 {
     $manager = User::findOrFail($id);
     $pageTitle = 'Détails';
    $breadcrumb = ['Gerants','Liste Gerants','Modifier'];
     return view('gerant.edit', compact('manager','pageTitle','breadcrumb'));
 }

 // Mettre à jour les informations d'un gérant
 public function update(Request $request, $id)
 {
     $manager = User::findOrFail($id);

     $request->validate([
         'name' => 'required|string|max:255',
         'email' => 'required|string|email|max:255|unique:users,email,' . $manager->id,
         'phone' => 'nullable|string|max:255',
         'password' => 'nullable|string|min:8|confirmed',
     ]);

     $manager->update([
         'name' => $request->name,
         'email' => $request->email,
         'phone' => $request->phone,
         'password' => $request->password ? Hash::make($request->password) : $manager->password,
     ]);

     return redirect()->route('gerant.index')->with('success', 'Gérant mis à jour avec succès.');
 }

 // Supprimer un gérant
 public function destroy($id)
 {
     $manager = User::findOrFail($id);
     $manager->delete();

     return redirect()->route('gerant.index')->with('success', 'Gérant supprimé avec succès.');
 }
}
