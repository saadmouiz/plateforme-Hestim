# Guide de Configuration Rapide

## Résolution de l'erreur "Vite manifest not found"

Cette erreur apparaît lorsque les assets Vite n'ont pas été compilés. Voici comment la résoudre :

### Solution 1 : Mode Développement (Recommandé)

Ouvrez **deux terminaux** :

**Terminal 1** - Serveur Laravel :
```bash
php artisan serve
```

**Terminal 2** - Compilateur Vite (laissez-le actif) :
```bash
npm run dev
```

Le terminal Vite doit rester ouvert pendant le développement. Il surveille les changements et recompile automatiquement.

### Solution 2 : Compilation Unique

Pour compiler les assets une seule fois (production) :
```bash
npm run build
```

### Si les dépendances ne sont pas installées

```bash
# Installer les dépendances npm
npm install

# Puis compiler
npm run build
# OU lancer en mode dev
npm run dev
```

## Commandes Utiles

```bash
# Installer les dépendances PHP
composer install

# Installer les dépendances Node.js
npm install

# Créer le fichier .env
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Exécuter les migrations
php artisan migrate --seed

# Compiler les assets (production)
npm run build

# Lancer Vite en mode développement
npm run dev

# Démarrer le serveur Laravel
php artisan serve
```

## Structure des Terminaux

Pour le développement, vous devez avoir **2 terminaux ouverts** :

1. **Terminal 1** : `php artisan serve` (serveur Laravel)
2. **Terminal 2** : `npm run dev` (compilateur Vite)

Les deux doivent rester actifs pendant le développement.

