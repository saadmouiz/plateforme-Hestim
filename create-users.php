<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Vérification et création des utilisateurs...\n\n";

// Vérifier si les utilisateurs existent déjà
$admin = User::where('email', 'admin@hestim.ma')->first();
$enseignant = User::where('email', 'enseignant@hestim.ma')->first();
$etudiant = User::where('email', 'etudiant@hestim.ma')->first();

if ($admin) {
    echo "✓ Admin existe déjà\n";
} else {
    User::create([
        'name' => 'Administrateur',
        'email' => 'admin@hestim.ma',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
    echo "✓ Admin créé\n";
}

if ($enseignant) {
    echo "✓ Enseignant existe déjà\n";
} else {
    User::create([
        'name' => 'Enseignant Test',
        'email' => 'enseignant@hestim.ma',
        'password' => Hash::make('password'),
        'role' => 'enseignant',
    ]);
    echo "✓ Enseignant créé\n";
}

if ($etudiant) {
    echo "✓ Étudiant existe déjà\n";
} else {
    User::create([
        'name' => 'Étudiant Test',
        'email' => 'etudiant@hestim.ma',
        'password' => Hash::make('password'),
        'role' => 'etudiant',
    ]);
    echo "✓ Étudiant créé\n";
}

echo "\nComptes disponibles:\n";
echo "- Admin: admin@hestim.ma / password\n";
echo "- Enseignant: enseignant@hestim.ma / password\n";
echo "- Étudiant: etudiant@hestim.ma / password\n";

