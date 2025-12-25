# Fix Images sur Hostinger - Lien Symbolique

## Problème
- Les images sont stockées dans: `storage/app/public/perfumes/`
- L'URL accessible est: `https://emmaluxury.store/storage/app/public/perfumes/...` ❌
- L'URL attendue devrait être: `https://emmaluxury.store/storage/perfumes/...` ✅

## Cause
Le lien symbolique `public/storage` → `storage/app/public` n'existe pas sur le serveur.

## Solution: Créer le lien symbolique

### Méthode 1: Via SSH (Recommandé)

```bash
# 1. Connectez-vous en SSH
ssh votre_username@emmaluxury.store

# 2. Allez dans le dossier racine de Laravel
cd /home/votre_username/public_html
# OU selon votre configuration Hostinger
cd /home/votre_username/domains/emmaluxury.store/public_html

# 3. Créez le lien symbolique avec artisan
php artisan storage:link
```

Vous devriez voir le message:
```
The [public/storage] link has been connected to [storage/app/public].
The links have been created.
```

### Méthode 2: Si php artisan ne fonctionne pas

Si la commande artisan ne fonctionne pas, créez le lien manuellement:

```bash
# Allez dans le dossier public
cd public

# Créez le lien symbolique
ln -s ../storage/app/public storage

# Vérifiez que le lien existe
ls -la storage
```

Vous devriez voir:
```
lrwxrwxrwx 1 user user storage -> ../storage/app/public
```

### Méthode 3: Via le code Laravel (Si SSH n'est pas disponible)

Si vous n'avez pas accès SSH, créez un fichier temporaire `create-link.php` à la racine:

```php
<?php
// create-link.php
// SUPPRIMEZ CE FICHIER APRÈS UTILISATION !

$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

if (file_exists($link)) {
    echo "Le lien existe déjà!";
} else {
    if (symlink($target, $link)) {
        echo "Lien symbolique créé avec succès!";
    } else {
        echo "Erreur lors de la création du lien.";
    }
}
?>
```

Uploadez ce fichier à la racine de votre projet et visitez:
```
https://emmaluxury.store/create-link.php
```

**⚠️ IMPORTANT**: Supprimez ce fichier immédiatement après utilisation!

## Vérification

### 1. Vérifiez que le lien symbolique existe

Via SSH:
```bash
ls -la public/storage
```

Vous devriez voir:
```
storage -> ../storage/app/public
```

### 2. Testez l'accès à une image

Visitez dans votre navigateur:
```
https://emmaluxury.store/storage/perfumes/nom-de-votre-image.jpg
```

L'image devrait s'afficher!

### 3. Testez depuis l'API

Faites une requête à votre API:
```bash
curl -H "Authorization: Bearer VOTRE_TOKEN" \
     https://emmaluxury.store/public/api/perfumes
```

Les URLs d'images devraient être:
```json
{
  "image_url": "https://emmaluxury.store/storage/perfumes/xxxxx.jpg"
}
```

### 4. Testez dans l'app Flutter

Relancez votre application Flutter mobile et vérifiez que les images se chargent correctement.

## Permissions

Si les images ne s'affichent toujours pas, vérifiez les permissions:

```bash
# Donnez les bonnes permissions
chmod -R 755 storage
chmod -R 755 public/storage

# Si nécessaire, changez le propriétaire
chown -R votre_username:votre_username storage
```

## Troubleshooting

### Erreur: "symlink(): Protocol error"
Votre hébergement ne supporte peut-être pas les liens symboliques. Contactez le support Hostinger.

### Erreur: "File exists"
Le lien existe déjà. Supprimez-le et recréez-le:
```bash
rm public/storage
php artisan storage:link
```

### Les images ne s'affichent toujours pas
1. Vérifiez que le fichier existe: `ls -la storage/app/public/perfumes/`
2. Vérifiez les permissions: `ls -la public/storage`
3. Vérifiez les logs Apache/Nginx
4. Contactez le support Hostinger

## Configuration Hostinger spécifique

Sur Hostinger, assurez-vous que:
- Le document root pointe vers `/public_html/public` (ou votre dossier public)
- Le fichier `.htaccess` dans `/public` existe et est correct
- Les permissions sont correctes (755 pour les dossiers, 644 pour les fichiers)
