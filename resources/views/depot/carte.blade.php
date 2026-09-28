Coordonnées de villes au Cameroun
Yaoundé (Capitale) :

Latitude : 3.8480

Longitude : 11.5021

Douala (Plus grande ville) :

Latitude : 4.0511

Longitude : 9.7679

Bafoussam :

Latitude : 5.4667

Longitude : 10.4167

Bamenda :

Latitude : 5.9333

Longitude : 10.1667

Garoua :

Latitude : 9.3000

Longitude : 13.4000

Maroua :

Latitude : 10.5956

Longitude : 14.3247

Ngaoundéré :

Latitude : 7.3167

Longitude : 13.5833

Kumba :

Latitude : 4.6333

Longitude : 9.4500

Limbe :

Latitude : 4.0167

Longitude : 9.2167

Ebolowa :

Latitude : 2.9000

Longitude : 11.1500

Coordonnées de points d'intérêt au Cameroun
Mont Cameroun :

Latitude : 4.2167

Longitude : 9.1667

Parc National de Waza :

Latitude : 11.3667

Longitude : 14.6167

Chutes de la Lobé :

Latitude : 2.9000

Longitude : 9.9167

Lac Nyos :

Latitude : 6.4333

Longitude : 10.3000

Palais Royal de Foumban :

Latitude : 5.7167

Longitude : 10.9167

Exemple d'utilisation avec Leaflet
Voici comment utiliser ces coordonnées pour afficher un marqueur sur une carte :

html
Copy
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte du Cameroun avec Leaflet</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 500px; } /* Hauteur de la carte */
    </style>
</head>
<body>
    <div id="map"></div>

    <!-- Leaflet JavaScript -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Coordonnées de Yaoundé
        const yaounde = [3.8480, 11.5021];

        // Initialiser la carte
        const map = L.map('map').setView(yaounde, 7); // Zoom level 7 pour voir tout le Cameroun

        // Ajouter une couche de tuiles OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Ajouter des marqueurs pour chaque ville
        const villes = [
            { nom: "Yaoundé", coords: [3.8480, 11.5021] },
            { nom: "Douala", coords: [4.0511, 9.7679] },
            { nom: "Bafoussam", coords: [5.4667, 10.4167] },
            { nom: "Bamenda", coords: [5.9333, 10.1667] },
            { nom: "Garoua", coords: [9.3000, 13.4000] },
            { nom: "Maroua", coords: [10.5956, 14.3247] },
            { nom: "Ngaoundéré", coords: [7.3167, 13.5833] },
            { nom: "Kumba", coords: [4.6333, 9.4500] },
            { nom: "Limbe", coords: [4.0167, 9.2167] },
            { nom: "Ebolowa", coords: [2.9000, 11.1500] }
        ];

        villes.forEach(ville => {
            L.marker(ville.coords)
                .addTo(map)
                .bindPopup(ville.nom)
                .openPopup();
        });
    </script>
</body>
</html>
https://youtu.be/5bLPxNKJpUg?si=BFoqN1ELOhKmWmTo// LIEN YOUTUBE DE VIDEOS POUR SAVOIR INTEGRER UNE IA SUR UN SITE WEB


### Utilisation des Événements et des Listeners dans Laravel

1. Création de l'Événement
Créer un événement : Vous pouvez créer un événement qui sera déclenché lorsque le dépôt est attribué à un gérant. Utilisez la commande Artisan suivante :
php artisan make:event DepotAssigned
Définir l'événement : Dans le fichier DepotAssigned.php, ajoutez les propriétés nécessaires, comme le gérant et le dépôt.
namespace App\Events;

use App\Models\Depot;
use App\Models\Manager;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DepotAssigned
{
    use Dispatchable, SerializesModels;

    public $manager;
    public $depot;

    public function __construct(Manager $manager, Depot $depot)
    {
        $this->manager = $manager;
        $this->depot = $depot;
    }
}
2. Création du Listener
Créer un listener : Créez un listener qui écoutera l'événement DepotAssigned.
php artisan make:listener UpdateDepotStatus
Définir la logique dans le listener : Dans le fichier UpdateDepotStatus.php, mettez à jour le statut du dépôt.
php
Copy code
namespace App\Listeners;

use App\Events\DepotAssigned;

class UpdateDepotStatus
{
    public function handle(DepotAssigned $event)
    {
        // Changer le statut du dépôt à actif
        $event->depot->status = 'active';
        $event->depot->save();
    }
}
3. Enregistrement de l'Événement et du Listener
Enregistrer l'événement et le listener : Dans le fichier EventServiceProvider.php, enregistrez l'événement et le listener.
php
Copy code
protected $listen = [
    DepotAssigned::class => [
        UpdateDepotStatus::class,
    ],
];
4. Déclenchement de l'Événement
Déclencher l'événement lors de l'attribution du dépôt : Dans votre méthode store du contrôleur de gérant, déclenchez l'événement après avoir attribué le dépôt.
php
Copy code
public function store(Request $request)
{
    // Validation et création du gérant
    $manager = Manager::create($request->all());

    // Attribution du dépôt inactif
    $depot = Depot::find($request->depot_id);
    if ($depot) {
        $manager->depot_id = $depot->id;
        $manager->save();

        // Déclencher l'événement
        event(new DepotAssigned($manager, $depot));
    }

    return redirect()->route('managers.index')->with('success', 'Gérant créé avec succès et dépôt attribué.');
}
Conclusion
En utilisant des événements et des listeners, vous pouvez gérer l'attribution des dépôts de manière efficace et propre. Cela permet de maintenir votre code organisé et de séparer les préoccupations, facilitant ainsi la maintenance et l'évolution de votre application. Si vous avez besoin d'autres précisions ou d'exemples, n'hésitez pas à demander !




Pour créer un champ avec une liste des dépôts inactifs dans Laravel, vous devez d'abord définir une relation entre le modèle Depot et le modèle Manager. Ensuite, vous pouvez récupérer les dépôts inactifs dans votre formulaire de création de gérant, en utilisant une requête pour filtrer les dépôts par leur statut. Cela vous permettra d'afficher une liste déroulante des dépôts inactifs à attribuer au gérant. ### Étapes pour Ajouter une Liste de Dépôts Inactifs et Changer leur Statut

1. Mise à Jour du Formulaire de Création de Gérant
Ajouter un champ de sélection : Dans votre vue de création de gérant, ajoutez un champ de sélection pour les dépôts inactifs.
blade
Copy code
<form action="{{ route('managers.store') }}" method="POST">
    @csrf
    <label for="name">Nom:</label>
    <input type="text" name="name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="depot_id">Sélectionnez un dépôt inactif:</label>
    <select name="depot_id" required>
        @foreach($inactiveDepots as $depot)
            <option value="{{ $depot->id }}">{{ $depot->name }}</option>
        @endforeach
    </select>

    <button type="submit">Créer Gérant</button>
</form>
2. Mise à Jour du Contrôleur de Création de Gérant
Passer la liste des dépôts inactifs à la vue : Dans la méthode create de votre ManagerController, récupérez les dépôts inactifs et passez-les à la vue.
php
Copy code
public function create()
{
    $inactiveDepots = Depot::where('status', 'inactive')->get();
    return view('managers.create', compact('inactiveDepots'));
}
Modifier la méthode store pour changer le statut du dépôt : Après avoir attribué le dépôt au gérant, mettez à jour le statut du dépôt.
php
Copy code
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:managers',
        'depot_id' => 'required|exists:depots,id', // Validation pour le dépôt
    ]);

    // Création du gérant
    $manager = Manager::create($request->all());

    // Attribution du dépôt inactif
    $depot = Depot::find($request->depot_id);
    if ($depot) {
        $manager->depot_id = $depot->id;
        $manager->save();

        // Changer le statut du dépôt à actif
        $depot->status = 'active';
        $depot->save();
    }

    return redirect()->route('managers.index')->with('success', 'Gérant créé avec succès et dépôt attribué.');
}
3. Mise à Jour de la Base de Données
Assurez-vous que le modèle Depot a un champ status : Si ce n'est pas déjà fait, vérifiez que la table depots a un champ pour le statut.
php
Copy code
Schema::table('depots', function (Blueprint $table) {
    $table->string('status')->default('inactive'); // Ajoutez ce champ si nécessaire
});
4. Gestion des Dépôts Inactifs
Vérification des dépôts inactifs : Assurez-vous que la logique pour identifier les dépôts inactifs est correcte et que les dépôts sont bien marqués comme inactifs ou actifs selon les besoins.
Conclusion
Avec ces modifications, le superviseur pourra sélectionner un dépôt inactif à attribuer au gérant lors de sa création. Une fois le gérant créé, le dépôt sera automatiquement mis à jour pour refléter son nouveau statut actif. Si vous avez besoin d'autres ajustements ou d'une fonctionnalité supplémentaire, n'hésitez pas à demander !






Autres Exemples d'Événements et de Listeners
Exemple 1 : Envoi d'un Email de Bienvenue
Créer un Événement : User Registered
php
Copy code
namespace App\Events;

use App\Models\User;

class UserRegistered
{
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
Créer un Listener : SendWelcomeEmail
php
Copy code
namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(UserRegistered $event)
    {
        Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
    }
}
Déclencher l'Événement : Dans le contrôleur d'inscription :
php
Copy code
event(new UserRegistered($user));

Exemple 2 : Journaliser les Actions
Créer un Événement : ActionLogged
php
Copy code
namespace App\Events;

class ActionLogged
{
    use Dispatchable, SerializesModels;

    public $action;

    public function __construct($action)
    {
        $this->action = $action;
    }
}
Créer un Listener : LogAction
php
Copy code
namespace App\Listeners;

use App\Events\ActionLogged;
use Illuminate\Support\Facades\Log;

class LogAction
{
    public function handle(ActionLogged $event)
    {
        Log::info('Action logged: ' . $event->action);
    }
}
Déclencher l'Événement : Dans le contrôleur où l'action se produit :
event(new ActionLogged('User  updated their profile'));
Conclusion
Les événements et les listeners dans Laravel permettent de gérer des actions de manière asynchrone et de garder votre code propre et organisé. En utilisant ces concepts, vous pouvez facilement ajouter de nouvelles fonctionnalités sans modifier le code existant. Si vous avez d'autres questions ou besoin de précisions supplémentaires, n'hésitez pas à demander !





Pour gérer les rôles et les permissions des utilisateurs dans votre application Laravel, vous pouvez utiliser plusieurs approches. L'une des méthodes les plus courantes consiste à utiliser un package comme Spatie Laravel Permission, qui facilite la gestion des rôles et des permissions. Voici comment vous pouvez l'implémenter :

Étapes pour Implémenter les Rôles et Permissions
1. Installer le Package Spatie Laravel Permission
Exécutez la commande suivante pour installer le package :

bash
Copy code
composer require spatie/laravel-permission
2. Publier la Configuration
Après l'installation, publiez le fichier de configuration et les migrations :

bash
Copy code
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
3. Exécuter les Migrations
Exécutez les migrations pour créer les tables nécessaires :

bash
Copy code
php artisan migrate
4. Ajouter le Trait à votre Modèle User
Ajoutez le trait HasRoles au modèle User  :

php
Copy code
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // Autres propriétés et méthodes
}
5. Créer des Rôles et des Permissions
Vous pouvez créer des rôles et des permissions dans un seeder ou directement dans votre code. Voici un exemple de seeder :

php
Copy code
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Créer des permissions
        Permission::create(['name' => 'create managers']);
        Permission::create(['name' => 'edit managers']);
        Permission::create(['name' => 'delete managers']);
        Permission::create(['name' => 'view managers']);

        // Créer des rôles
        $supervisor = Role::create(['name' => 'supervisor']);
        $manager = Role::create(['name' => 'manager']);
        $user = Role::create(['name' => 'user']);

        // Attribuer des permissions aux rôles
        $supervisor->givePermissionTo(['create managers', 'edit managers', 'delete managers', 'view managers']);
        $manager->givePermissionTo(['view managers']);
    }
}
Exécutez le seeder :

bash
Copy code
php artisan db:seed --class=RoleAndPermissionSeeder
6. Attribuer des Rôles et des Permissions aux Utilisateurs
Vous pouvez attribuer des rôles et des permissions aux utilisateurs dans votre contrôleur ou lors de l'inscription :

php
Copy code
$user = User::find(1);
$user->assignRole('supervisor'); // Assigner un rôle
$user->givePermissionTo('create managers'); // Assigner une permission
7. Vérifier les Permissions dans les Contrôleurs ou les Vues
Vous pouvez vérifier les permissions dans vos contrôleurs ou vos vues :

php
Copy code
if ($user->can('create managers')) {
    // L'utilisateur peut créer des gérants
}

if ($user->hasRole('supervisor')) {
    // L'utilisateur est un superviseur
}
Dans vos vues Blade, vous pouvez également utiliser les directives :

blade
Copy code
@can('create managers')
    <a href="{{ route('managers.create') }}">Créer un gérant</a>
@endcan

@role('supervisor')
    <p>Bienvenue, superviseur !</p>
@endrole
Conclusion
En utilisant le package Spatie Laravel Permission, vous pouvez facilement gérer les rôles et les permissions des utilisateurs dans votre application. Cela vous permet de contrôler les actions que chaque utilisateur peut effectuer en fonction de son rôle, ce qui est essentiel pour la sécurité et la gestion des accès. Si vous avez d'autres questions ou besoin de précisions supplémentaires, n'hésitez pas à demander !

php artisan make:controller GerantController --resource //pour creer un bon squelette de controler pour un crud