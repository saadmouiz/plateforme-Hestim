# Instructions pour compiler les assets Vite

## Problème résolu temporairement

J'ai modifié le layout pour utiliser Tailwind CSS via CDN en fallback. L'application fonctionne maintenant **sans avoir besoin de compiler les assets**.

## Pour utiliser Vite (optionnel, pour la production)

Si vous voulez compiler les assets avec Vite plus tard :

### Option 1 : Compilation unique (Production)
```bash
npm install
npm run build
```

### Option 2 : Mode développement (avec recompilation automatique)
Ouvrez **2 terminaux** :

**Terminal 1** :
```bash
php artisan serve
```

**Terminal 2** :
```bash
npm run dev
```

Le terminal `npm run dev` doit rester ouvert et surveille les changements automatiquement.

## Fichier batch Windows

J'ai créé un fichier `compile-assets.bat` que vous pouvez double-cliquer pour compiler les assets automatiquement.

## Note importante

L'application fonctionne actuellement avec Tailwind CSS via CDN. C'est parfait pour le développement. Pour la production, vous devriez compiler les assets avec Vite pour de meilleures performances.

