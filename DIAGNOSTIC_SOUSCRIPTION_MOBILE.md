# Diagnostic Complet - Problème de Souscription Mobile

## Résumé du Problème

**Erreur** : "The POST method is not supported for this route. Supported methods: GET, HEAD"

**Origine** : Application mobile Flutter lors de la confirmation d'une commande de parfums

---

## ✅ Vérifications Effectuées

### 1. Code Flutter (CORRECT)

**Fichier** : `tontine_parfums_app/lib/screens/cart/cart_screen.dart:336-343`

```dart
final response = await _apiService.post(
  '/subscriptions',           // ✅ Endpoint correct
  {
    'tontine_id': _selectedTontineId,  // ✅ Données correctes
    'items': items,
  },
  null,
);
```

**URL complète appelée** : `https://emmaluxury.store/api/subscriptions`

### 2. Configuration API Flutter (CORRECT)

**Fichier** : `tontine_parfums_app/lib/config/api_config.dart:9`

```dart
static const String baseUrl = 'https://emmaluxury.store/api';
```

### 3. Headers HTTP Flutter (CORRECT)

**Fichier** : `tontine_parfums_app/lib/services/api_service.dart:46-50`

```dart
final response = await http.post(
  Uri.parse('${ApiConfig.baseUrl}$endpoint'),
  headers: headers,           // ✅ Contient Authorization, Content-Type, Accept
  body: jsonEncode(body),     // ✅ JSON encodé correctement
)
```

### 4. Route Laravel (CORRECTE)

**Fichier** : `routes/api.php:52`

```php
Route::post('/subscriptions', [SubscriptionController::class, 'store']);
```

**Vérification** :
```
php artisan route:list --path=api/subscriptions

POST | api/subscriptions | SubscriptionController@store | api, auth:sanctum
```

### 5. Contrôleur Laravel (CORRECT)

**Fichier** : `app/Http/Controllers/API/SubscriptionController.php:173-267`

La méthode `store()` existe et est bien implémentée.

---

## 🔍 Causes Possibles

### 1. Redirection HTTP → HTTPS (TRÈS PROBABLE)

Si Hostinger redirige automatiquement HTTP vers HTTPS, et que la redirection transforme le POST en GET.

**Solution** : Vérifier la configuration `.htaccess`

```apache
# À VÉRIFIER dans public/.htaccess
# Si cette règle existe, elle peut causer le problème :
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Remplacer par (preserve POST):
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=307]
```

### 2. Problème de Cache de Routes

**Solution** :
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

### 3. Problème de Configuration Serveur (Hostinger)

Le serveur pourrait ne pas accepter les requêtes POST sur certaines routes.

**Solution** : Vérifier la configuration Apache/Nginx

### 4. Middleware Sanctum

L'authentification Sanctum pourrait bloquer ou rediriger.

**Solution** : Vérifier dans les logs si le token est bien envoyé et valide

---

## 🛠️ Solutions Mises en Place

### ✅ 1. Système de Logging Complet

**Fichiers créés/modifiés** :
- `app/Http/Middleware/ApiLogger.php` - Middleware de logging
- `config/logging.php` - Configuration du canal 'api'
- `app/Http/Kernel.php` - Enregistrement du middleware

**Logs générés dans** : `storage/logs/api-YYYY-MM-DD.log`

**Contenu des logs** :
- URL exacte appelée
- Méthode HTTP (GET/POST/etc.)
- Headers complets (dont Authorization)
- Body de la requête
- Réponse complète du serveur
- Status code HTTP

### ✅ 2. Documentation Créée

- `API_LOGGING_GUIDE.md` - Guide complet d'utilisation des logs
- `TEST_API_MOBILE.md` - Guide de test et débogage
- `DIAGNOSTIC_SOUSCRIPTION_MOBILE.md` - Ce fichier

---

## 📋 Prochaines Étapes

### Étape 1 : Vérifier le fichier .htaccess

**Fichier** : `public/.htaccess`

Cherchez cette ligne et modifiez-la :

```apache
# AVANT (problématique)
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# APRÈS (correct)
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=307]
```

**Explication** :
- `R=301` : Redirection permanente (transforme POST en GET)
- `R=307` : Redirection temporaire (préserve la méthode POST)

### Étape 2 : Vider les caches

```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

### Étape 3 : Activer les logs et tester

1. **Redémarrez le serveur** (si local) ou **rechargez la configuration** (si Hostinger)

2. **Lancez l'application mobile** et tentez une souscription

3. **Consultez les logs** :
   ```bash
   # Local
   type storage\logs\api-*.log

   # Ou via FTP/SSH sur Hostinger
   cat storage/logs/api-*.log
   ```

4. **Analysez les informations** :
   - URL exacte : doit être `https://emmaluxury.store/api/subscriptions`
   - Méthode : doit être `POST`
   - Headers : doit contenir `Authorization: Bearer ...`
   - Status code : doit être `201` (success) ou `4xx/5xx` (erreur)

### Étape 4 : Cas spécifiques

#### Si les logs montrent "method": "GET" au lieu de "POST"
→ Problème de redirection HTTP → HTTPS (voir Étape 1)

#### Si les logs montrent "user_id": null
→ Problème d'authentification Sanctum
→ Vérifier que le token est valide

#### Si les logs montrent une URL différente
→ Problème de configuration dans l'app mobile
→ Vérifier `api_config.dart`

#### Si aucun log n'apparaît
→ Le middleware n'est pas activé
→ Relancer le serveur après avoir vidé les caches

---

## 🧪 Test Manuel de la Route

### Test avec cURL (depuis le serveur)

```bash
# 1. Obtenir un token
curl -X POST https://emmaluxury.store/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"client@example.com","password":"password"}'

# 2. Tester la souscription (remplacer YOUR_TOKEN)
curl -X POST https://emmaluxury.store/api/subscriptions \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"tontine_id":1,"items":[{"perfume_id":1,"quantity":2}]}'
```

### Résultats Attendus

**Succès (201 Created)** :
```json
{
  "success": true,
  "message": "Subscription created successfully",
  "data": {
    "id": 1,
    "tontine_id": 1,
    "user_id": 1,
    "status": "active",
    "items": [...],
    "payments": [...]
  }
}
```

**Erreur (405 Method Not Allowed)** :
```json
{
  "message": "The POST method is not supported for this route. Supported methods: GET, HEAD."
}
```

Si cURL retourne 405, c'est un problème serveur (redirection ou configuration).
Si cURL fonctionne mais pas l'app mobile, c'est un problème d'app.

---

## 📝 Checklist Complète

### Configuration Serveur
- [ ] Vérifier `.htaccess` (R=307 au lieu de R=301)
- [ ] Vider tous les caches Laravel
- [ ] Vérifier les permissions des dossiers (storage/logs)
- [ ] Vérifier la configuration Apache/Nginx

### Configuration Laravel
- [x] Route POST `/api/subscriptions` existe
- [x] Contrôleur `SubscriptionController@store` existe
- [x] Middleware `auth:sanctum` configuré
- [x] Système de logging activé

### Configuration App Mobile
- [x] URL de base correcte : `https://emmaluxury.store/api`
- [x] Endpoint correct : `/subscriptions`
- [x] Méthode HTTP : POST
- [x] Headers : Content-Type, Accept, Authorization
- [x] Body : JSON encodé avec tontine_id et items

### Tests
- [ ] Test cURL depuis le serveur
- [ ] Test depuis l'app mobile avec logs activés
- [ ] Vérification des logs générés
- [ ] Correction basée sur les logs

---

## 🎯 Résolution Rapide (Most Likely Fix)

**Le problème est PROBABLEMENT lié à la redirection HTTP → HTTPS sur Hostinger.**

### Solution Immédiate

**Modifiez `public/.htaccess`** :

Cherchez cette section :
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect to HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]  # ← PROBLÈME ICI
```

Remplacez par :
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect to HTTPS (preserve POST method)
    RewriteCond %{HTTPS} off
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=307]  # ← SOLUTION
```

Ou mieux encore (pour production) :
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Redirect to HTTPS (preserve POST method, permanent)
    RewriteCond %{HTTPS} off
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=308]  # ← MEILLEURE SOLUTION
```

**Différences** :
- `R=301` : Redirection permanente, transforme POST en GET (❌ cause le problème)
- `R=307` : Redirection temporaire, préserve POST (✅ fonctionne)
- `R=308` : Redirection permanente, préserve POST (✅ meilleur pour production)

---

## 📊 Informations Capturées par les Logs

Après activation, chaque requête génère ces informations :

```json
{
  "API Request": {
    "timestamp": "2025-12-25 15:30:45",
    "method": "POST",                              // ← Vérifier si POST ou GET
    "url": "https://emmaluxury.store/api/subscriptions",  // ← URL exacte
    "path": "api/subscriptions",
    "ip": "197.149.x.x",
    "user_agent": "Dart/2.19 (dart:io)",
    "headers": {
      "authorization": ["Bearer eyJ0eXAi..."],    // ← Token présent ?
      "content-type": ["application/json"],
      "accept": ["application/json"]
    },
    "body": {
      "tontine_id": 1,
      "items": [{"perfume_id": 1, "quantity": 2}]
    },
    "user_id": 5                                  // ← null = pas authentifié
  },
  "API Response": {
    "timestamp": "2025-12-25 15:30:46",
    "status_code": 405,                           // ← Code d'erreur
    "content": "{\"message\":\"The POST method is not supported...\"}"
  }
}
```

---

## 🆘 Support

Si le problème persiste après ces étapes :

1. **Partagez les logs complets** de `storage/logs/api-YYYY-MM-DD.log`
2. **Partagez le contenu** de `public/.htaccess`
3. **Partagez le résultat** du test cURL
4. **Indiquez** si le test cURL fonctionne ou pas

Cela permettra d'identifier exactement où se situe le problème.
