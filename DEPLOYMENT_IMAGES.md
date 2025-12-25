# Guide de déploiement - Fix des images de parfums

## Problème
Les images de parfums ne se chargent pas en production (Hostinger) mais fonctionnent en local.

## Cause
- Le serveur Hostinger a le document root pointant vers `/` au lieu de `/public`
- Les URLs d'images générées sont `https://emmaluxury.store/storage/...` au lieu de `https://emmaluxury.store/public/storage/...`
- Le lien symbolique `public/storage` → `storage/app/public` n'existe pas sur le serveur

## Solution implémentée

### 1. Modification du PerfumeResource
Le fichier `app/Http/Resources/PerfumeResource.php` a été modifié pour détecter automatiquement l'environnement et générer la bonne URL:

- **En local**: `http://localhost/storage/perfumes/image.jpg`
- **En production**: `https://emmaluxury.store/public/storage/perfumes/image.jpg`

### 2. Étapes de déploiement sur Hostinger

#### Étape 1: Uploader les fichiers modifiés
Uploadez le fichier modifié sur votre serveur:
- `app/Http/Resources/PerfumeResource.php`

#### Étape 2: Connectez-vous en SSH
```bash
ssh votre_username@emmaluxury.store
cd /home/votre_username/public_html
# ou le chemin de votre projet Laravel
```

#### Étape 3: Créer le lien symbolique
```bash
php artisan storage:link
```

Cette commande crée un lien symbolique de `public/storage` vers `storage/app/public`.

#### Étape 4: Vérifier les permissions
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R votre_username:votre_username storage
chown -R votre_username:votre_username bootstrap/cache
```

#### Étape 5: Vérifier que le lien symbolique existe
```bash
ls -la public/storage
```

Vous devriez voir quelque chose comme:
```
lrwxrwxrwx 1 user user 35 Dec 21 storage -> /path/to/storage/app/public
```

#### Étape 6: Tester l'accès aux images
Visitez une URL d'image dans votre navigateur:
```
https://emmaluxury.store/public/storage/perfumes/nom-image.jpg
```

### 3. Configuration .env en production

Assurez-vous que votre fichier `.env` sur le serveur contient:

```env
APP_ENV=production
APP_URL=https://emmaluxury.store
FILESYSTEM_DRIVER=public
```

### 4. Upload d'images

Lorsque vous uploadez des images de parfums via l'interface admin, elles doivent être stockées dans:
```
storage/app/public/perfumes/
```

Le code d'upload devrait ressembler à:
```php
$path = $request->file('image')->store('perfumes', 'public');
// $path sera "perfumes/nom-fichier.jpg"
// À enregistrer dans la colonne image_url
```

### 5. Vérification finale

1. Testez l'API depuis votre navigateur ou Postman:
```
GET https://emmaluxury.store/public/api/perfumes
Header: Authorization: Bearer YOUR_TOKEN
```

2. Vérifiez que les URLs d'images dans la réponse sont correctes:
```json
{
  "image_url": "https://emmaluxury.store/public/storage/perfumes/image.jpg"
}
```

3. Testez dans l'application Flutter mobile

## Alternative: Configurer le document root (Solution à long terme)

Pour une meilleure pratique, configurez le document root de votre domaine pour pointer vers le dossier `/public` au lieu de `/`:

1. Dans le panneau de contrôle Hostinger (hPanel)
2. Allez dans "Domaines" → "Gérer"
3. Modifiez le "Document Root" pour pointer vers `/public_html/public` (ou votre chemin)
4. Après ce changement, vous pourrez utiliser des URLs comme:
   - `https://emmaluxury.store/storage/...` au lieu de
   - `https://emmaluxury.store/public/storage/...`

## Troubleshooting

### Les images ne se chargent toujours pas
1. Vérifiez que le lien symbolique existe: `ls -la public/storage`
2. Vérifiez les permissions: `ls -la storage/app/public/perfumes`
3. Vérifiez que les images existent physiquement sur le serveur
4. Vérifiez les logs Laravel: `tail -f storage/logs/laravel.log`
5. Vérifiez l'URL retournée par l'API

### Erreur 403 Forbidden
Les permissions du dossier storage ne sont pas correctes. Exécutez:
```bash
chmod -R 775 storage
```

### Erreur 404 Not Found
Le lien symbolique n'existe pas. Exécutez:
```bash
php artisan storage:link
```
