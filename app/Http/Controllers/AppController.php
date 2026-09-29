<?php

namespace App\Http\Controllers;
use App\Models\Depot;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AppController extends Controller
{
    //
    public function index(){
        // return view('index');
        return view('test');
    }
    
    public function testuser(){// fonction qui teste le role d'un utilisateur et le retourne vers son tableau de bord
        /* Auth::check(): Cette méthode retourne true si un utilisateur est authentifié, false sinon.

        if (Auth::check()) {
            // L'utilisateur est connecté
        } else {
            // L'utilisateur n'est pas connecté
        }*/
        //  Request $request
        // $query = $request->input('search'); 
        // Récupérer les documents avec pagination et recherche
    //  $documents = Document::when($query, function($queryBuilder) use ($query) {
    //     return $queryBuilder->where('name', 'like', "%{$query}%")
    //                          ->orWhere('description', 'like', "%{$query}%");
    // })->paginate(9);
    $pageTitle = 'Tableau de Bord';
    $breadcrumb = ['Tableau de Bord'];
    $user = auth()->user();
        // Récupérer les statistiques
    $totalDocuments = Document::count();
    $totalRetrouve = Document::where('status', 'en_attente_de_retrait')->count();
    $totalPersoRetrouve = Document::where('status', 'en_attente_de_retrait')->where('user_id', $user->id)->count();
    $totalPerdu = Document::where('status', 'perdu')->count();
    $totalPersoPerdu = Document::where('status', 'perdu')->where('user_id', $user->id)->count();
    $totalRendu = Document::where('status', 'restitue')->count();
    $totalPersoRendu = Document::where('status', 'restitue')->where('user_id', $user->id)->count();
    $totalSignale = Document::where('user_id', $user->id)->count();
    $totalDepot=Depot::count();
    $totalGerant=User::where('role','gerant')->count();
    $totalDepotActif=Depot::where('statut','actif')->count();
    $totalDepotInactif=Depot::where('statut','inactif')->count();
        if ($user) {
            // L'utilisateur est connecté, vous pouvez utiliser $user->name, $user->email, etc.
            if($user->role=="utilisateur"){
                return view("user.dashboard", compact('totalDocuments','totalPersoRetrouve', 'totalRetrouve','totalPersoPerdu', 'totalPerdu','totalPersoRendu', 'totalRendu','totalSignale','pageTitle','breadcrumb'));
            }elseif($user->role=="gerant"){
                return view("gerant.dashboard", compact('totalDocuments','totalPersoRetrouve', 'totalRetrouve','totalPersoPerdu', 'totalPerdu','totalPersoRendu', 'totalRendu','totalSignale','pageTitle','breadcrumb')); 
            }else{
                return view("admin.dashboard",compact('totalDocuments','totalPersoRetrouve', 'totalRetrouve','totalPersoPerdu', 'totalPerdu','totalPersoRendu', 'totalRendu','totalSignale','pageTitle','breadcrumb','totalDepot','totalGerant','totalDepotActif','totalDepotInactif'));
            }
            
        } else {
            return redirect()->route('login');
        }
    }

    public function signaler(){
    $pageTitle = 'Signaler Document(Egaré)';
    $breadcrumb = ['Signaler Document(Egaré)'];
        return view("user.documentperdu",compact('pageTitle','breadcrumb'));
    }

    public function store_lost_document(Request $request){
    $user = auth()->user();
    $pageTitle = 'Tableau de Bord';
    $breadcrumb = ['Tableau de Bord'];
    // $user = auth()->user();
        // Récupérer les statistiques
    $totalDocuments = Document::count();
    $totalRetrouve = Document::where('status', 'en_attente_de_retrait')->count();
    $totalPersoRetrouve = Document::where('status', 'en_attente_de_retrait')->where('user_id', $user->id)->count();
    $totalPerdu = Document::where('status', 'perdu')->count();
    $totalPersoPerdu = Document::where('status', 'perdu')->where('user_id', $user->id)->count();
    $totalRendu = Document::where('status', 'restitue')->count();
    $totalPersoRendu = Document::where('status', 'restitue')->where('user_id', $user->id)->count();
    $totalSignale = Document::where('user_id', $user->id)->count();
    $photos='';
       if ($request->hasFile('photos')) {
        $images = $request->file('photos');
        $count = count($images);
        // dd($count);
        foreach ($images as $image) {
            $name=$image->getClientOriginalName();
            $tmp_name = $image->getPathname();
            $folder="assets/images/documentspictures/";
            $destination="assets/images/documentspictures/".$name;
                if(move_uploaded_file($tmp_name,$destination)){
                    if(empty($photos)){
                        $photos=$name;
                    }else{
                        $photos=$photos.",".$name;
                        // dd($photos);
                    }    
                            }else{
                            dd("error")  ; 
                            }
                    
                        }
        }
        if($request->numero_du_document==NULL){
            $numero="aucun numéro";
        }else{
        $numero=$request->numero_du_document;
        }
        Document::create([
            'type_document'=>$request->type_document,
             'nom_present_sur_le_document'=>$request->nom_present_sur_le_document, 
             'status'=>"perdu",
             'lieu_de_perte'=>$request->lieu_de_perte,
             'date_de_perte'=>$request->date_de_perte,
             'user_id'=>$user->id,
             'photos'=>$photos,
                'additional_info'=>$request->additional_info,
                'numero_du_document'=>$numero,
                'contact_info'=>$request->contact_info
           ]);
           session()->flash('lostdoc_store', 'Votre document a été signalé perdu avec succès !');
           return view("user.dashboard", compact('totalDocuments','totalPersoRetrouve', 'totalRetrouve','totalPersoPerdu', 'totalPerdu','totalPersoRendu', 'totalRendu','totalSignale','pageTitle','breadcrumb'));
    }

    public function docs_perdu(){
        $pageTitle = 'Documents Perdus';
        $breadcrumb = ['Documents Perdus'];
        $documents=Document::where('status','perdu')->paginate(3);
        $types = Document::select('type_document')->where('status','perdu')->distinct()->pluck('type_document');
        return view('document.all_lost_documents',compact('documents', 'types','pageTitle','breadcrumb'));
    }
    public function docs_trouver(){
        $pageTitle = 'Documents Trouvés';
        $breadcrumb = ['Documents Trouvés'];
        $documents=Document::where('status','en_attente_de_retrait')->paginate(3);
        $types = Document::select('type_document')->where('status','en_attente_de_retrait')->distinct()->pluck('type_document');
        return view('document.all_found_documents',compact('documents','types','pageTitle','breadcrumb'));
    }
   public function docs_signaler(){
    $pageTitle = 'Documents Signalés';
    $breadcrumb = ['Documents Signalés'];
    $documents = Document::where('user_id', Auth::id())->paginate(3);

    return view('user.documentsignale', compact('documents','pageTitle','breadcrumb'));
   }

   public function index1(Request $request)
   {
       $pageTitle = 'Documents Perdus';
       $breadcrumb = ['Documents Perdus'];
       // Récupérer tous les types de documents (vous devez avoir un modèle ou une méthode pour cela)
       $types = Document::select('type_document')->where('status','perdu')->distinct()->pluck('type_document');

       // Filtrer les documents par type si un type est sélectionné
       $documents = Document::when($request->input('type'), function ($query) use ($request) {
           return $query->where('type_document', $request->input('type'));
       })->where('status','perdu')->paginate(3);

       return view('document.all_lost_documents', compact('documents', 'types','pageTitle','breadcrumb'));
   }

   public function index_found(Request $request)
   {
        $pageTitle = 'Documents Trouvés';
        $breadcrumb = ['Documents Trouvés'];
       // Récupérer tous les types de documents (vous devez avoir un modèle ou une méthode pour cela)
       $types = Document::select('type_document')->where('status','en_attente_de_retrait')->distinct()->pluck('type_document');

       // Filtrer les documents par type si un type est sélectionné
       $documents = Document::when($request->input('type'), function ($query) use ($request) {
           return $query->where('type_document', $request->input('type'));
       })->where('status','en_attente_de_retrait')->paginate(3);

       return view('document.all_found_documents', compact('documents', 'types','pageTitle','breadcrumb'));
   }

   public function supprimer_doc($id)
   {
    $pageTitle = 'Documents Signalés';
    $breadcrumb = ['Documents Signalés'];
       // Trouver le document par ID
       $document = Document::findOrFail($id);

       // Supprimer le document
       $document->delete();
       $documents = Document::where('user_id', Auth::id())->paginate(3);
       // Rediriger avec un message de succès
       session()->flash('succes_supp_doc', 'Cette signalisation a été supprimé avec succès.');
       return view('user.documentsignale', compact('documents','pageTitle','breadcrumb'));
   }
    }

