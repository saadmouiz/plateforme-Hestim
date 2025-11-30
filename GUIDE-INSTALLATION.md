# Guide d'Installation Complète

## Étape 1 : Vérifier les prérequis

Assurez-vous d'avoir :
- PHP >= 8.1
- Composer installé
- MySQL/MariaDB installé et démarré
- Node.js et npm (optionnel pour Vite)

## Étape 2 : Configurer la base de données

1. Créez une base de données MySQL nommée `hestim` :
```sql
CREATE DATABASE hestim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Configurez le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hestim
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

## Étape 3 : Exécuter les migrations

```bash
php artisan migrate
```

Cette commande va créer toutes les tables nécessaires.

## Étape 4 : Créer les utilisateurs de test

```bash
php artisan db:seed --class=AdminUserSeeder
```

Ou pour tout faire en une fois :
```bash
php artisan migrate --seed
```

## Étape 5 : Vérifier que tout fonctionne

1. Démarrer le serveur :
```bash
php artisan serve
```

2. Aller sur : http://localhost:8000/login

3. Se connecter avec :
   - **Admin**: admin@hestim.ma / password
   - **Enseignant**: enseignant@hestim.ma / password
   - **Étudiant**: etudiant@hestim.ma / password

## Scripts Windows disponibles

- `setup-database.bat` : Exécute les migrations et crée les utilisateurs
- `create-users.bat` : Crée uniquement les utilisateurs
- `compile-assets.bat` : Compile les assets Vite (optionnel)

## Résolution de problèmes

### Erreur "Les identifiants fournis ne correspondent pas"

**Solution** : Les utilisateurs n'existent pas. Exécutez :
```bash
php artisan db:seed --class=AdminUserSeeder
```

### Erreur de connexion à la base de données

**Vérifiez** :
1. MySQL est démarré
2. La base de données `hestim` existe
3. Les identifiants dans `.env` sont corrects

### Erreur "Vite manifest not found"

**Solution** : L'application utilise Tailwind CSS via CDN par défaut. Pour compiler les assets :
```bash
npm install
npm run build
```

Ou utilisez le script `compile-assets.bat`

## Commandes utiles

```bash
# Vérifier l'état des migrations
php artisan migrate:status

# Réinitialiser complètement la base de données (ATTENTION: supprime tout)
php artisan migrate:fresh --seed

# Créer un nouvel utilisateur manuellement
php artisan tinker
# Puis dans tinker:
User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => Hash::make('password'), 'role' => 'admin']);
```

