# Guide de Logging API pour le Débogage Mobile

## Vue d'ensemble

Ce guide explique comment utiliser le système de logging mis en place pour identifier et résoudre les problèmes avec l'application mobile Tontine Parfums.

## Emplacement des Logs

Les logs API sont stockés dans :
```
storage/logs/api-YYYY-MM-DD.log
```

Exemple : `storage/logs/api-2025-12-25.log`

## Format des Logs

Chaque requête API génère deux entrées de log :

### 1. Log de Requête (API Request)
```json
{
  "timestamp": "2025-12-25 10:30:45",
  "method": "POST",
  "url": "http://localhost:8000/api/subscriptions",
  "path": "api/subscriptions",
  "ip": "192.168.1.100",
  "user_agent": "Dart/2.19 (dart:io)",
  "headers": {
    "content-type": ["application/json"],
    "authorization": ["Bearer ..."],
    ...
  },
  "query_params": {},
  "body": {
    "tontine_id": 1,
    "items": [...]
  },
  "user_id": 5
}
```

### 2. Log de Réponse (API Response)
```json
{
  "timestamp": "2025-12-25 10:30:46",
  "status_code": 405,
  "content": "{\"message\":\"The POST method is not supported for this route. Supported methods: GET, HEAD.\"}"
}
```

## Comment Lire les Logs

### Identifier l'Erreur de Route

Si vous voyez une erreur **"The POST method is not supported for this route"**, vérifiez :

1. **La méthode HTTP utilisée** : `"method": "POST"`
2. **L'URL complète** : `"url": "http://localhost:8000/api/subscriptions"`
3. **Le chemin de la route** : `"path": "api/subscriptions"`

### Points à Vérifier

1. **Route existe-t-elle ?**
   - Vérifiez dans `routes/api.php`
   - Lancez `php artisan route:list` pour voir toutes les routes

2. **Méthode HTTP correcte ?**
   - POST pour créer
   - GET pour lire
   - PUT/PATCH pour mettre à jour
   - DELETE pour supprimer

3. **Authentification ?**
   - Vérifiez la présence du header `Authorization`
   - Vérifiez que `user_id` n'est pas null

4. **Format du body ?**
   - JSON valide ?
   - Tous les champs requis présents ?

## Routes API Disponibles pour la Souscription

### Créer une souscription (Recommandé pour l'app mobile)
```
POST /api/subscriptions
```

**Body attendu :**
```json
{
  "tontine_id": 1,
  "items": [
    {
      "perfume_id": 1,
      "quantity": 2
    },
    {
      "perfume_id": 2,
      "quantity": 1
    }
  ]
}
```

**Headers requis :**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

### Alternative : Utiliser le système de panier
```
1. POST /api/tontines/{tontineId}/cart/add
2. POST /api/tontines/{tontineId}/subscribe
```

## Commandes Utiles

### Voir les logs en temps réel
```bash
# Windows PowerShell
Get-Content storage/logs/api-*.log -Wait -Tail 50

# Windows CMD
tail -f storage/logs/api-*.log
```

### Voir toutes les routes API
```bash
php artisan route:list --path=api
```

### Voir seulement les routes de souscription
```bash
php artisan route:list --path=api/subscriptions
```

### Effacer les logs
```bash
# Supprimer tous les logs API
del storage/logs/api-*.log

# Ou garder uniquement les logs d'aujourd'hui
```

## Résolution du Problème Actuel

### Problème : "The POST method is not supported"

**Causes possibles :**

1. **URL incorrecte dans l'app mobile**
   - Vérifiez que l'URL est exactement : `{BASE_URL}/api/subscriptions`
   - PAS : `/api/subscription` (sans 's')
   - PAS : `/api/tontines/subscribe`

2. **Cache de routes**
   ```bash
   php artisan route:clear
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Middleware qui bloque**
   - Vérifiez l'authentification Sanctum
   - Vérifiez les headers

## Tester avec cURL

### Test de la route POST /api/subscriptions
```bash
curl -X POST http://localhost:8000/api/subscriptions \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "tontine_id": 1,
    "items": [
      {
        "perfume_id": 1,
        "quantity": 2
      }
    ]
  }'
```

### Obtenir un token de test
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password"
  }'
```

## Prochaines Étapes

1. **Relancez votre serveur Laravel** pour activer le logging
   ```bash
   php artisan serve
   ```

2. **Faites une tentative de souscription depuis l'app mobile**

3. **Consultez les logs**
   ```bash
   type storage\logs\api-*.log
   ```

4. **Partagez les logs** pour analyse si le problème persiste

## Support

Les logs capturent maintenant :
- Toutes les requêtes entrantes
- Toutes les réponses sortantes
- Les erreurs complètes
- Les détails d'authentification

Cela permettra d'identifier rapidement :
- Les URLs incorrectes
- Les méthodes HTTP incorrectes
- Les problèmes d'authentification
- Les erreurs de validation
- Les erreurs serveur
