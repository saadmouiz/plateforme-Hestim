# Résolution de l'erreur "Les identifiants fournis ne correspondent pas"

## Problème

Cette erreur apparaît lorsque les utilisateurs n'existent pas encore dans la base de données.

## Solution

Vous devez exécuter les migrations et créer les utilisateurs de test.

### Option 1 : Script automatique (Windows)

Double-cliquez sur le fichier `setup-database.bat` qui va :
1. Exécuter les migrations
2. Créer les utilisateurs de test

### Option 2 : Commandes manuelles

Ouvrez un terminal dans le dossier du projet et exécutez :

```bash
# 1. Exécuter les migrations
php artisan migrate

# 2. Créer les utilisateurs de test
php artisan db:seed --class=AdminUserSeeder
```

Ou en une seule commande :

```bash
php artisan migrate --seed
```

## Comptes créés

Après l'exécution, vous pouvez vous connecter avec :

- **Admin**: 
  - Email: `admin@hestim.ma`
  - Password: `password`

- **Enseignant**: 
  - Email: `enseignant@hestim.ma`
  - Password: `password`

- **Étudiant**: 
  - Email: `etudiant@hestim.ma`
  - Password: `password`

## Vérification

Si vous avez toujours des problèmes :

1. **Vérifiez que la base de données est configurée** dans le fichier `.env` :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hestim
DB_USERNAME=root
DB_PASSWORD=
```

2. **Vérifiez que la base de données existe** :
   - Créez la base de données `hestim` dans MySQL si elle n'existe pas

3. **Vérifiez les migrations** :
```bash
php artisan migrate:status
```

4. **Réinitialisez la base de données** (ATTENTION: supprime toutes les données) :
```bash
php artisan migrate:fresh --seed
```

