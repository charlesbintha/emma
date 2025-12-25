# Test API Mobile - Souscription Tontine

## Problème Actuel

**Erreur** : "The POST method is not supported for this route. Supported methods: GET, HEAD"

## Vérifications Effectuées

### ✅ Route existe bien
```
POST api/subscriptions
Action: App\Http\Controllers\API\SubscriptionController@store
Middleware: api, App\Http\Middleware\Authenticate:sanctum
```

### ✅ Contrôleur existe
Le contrôleur `SubscriptionController` a bien une méthode `store()` qui :
- Valide les données (`tontine_id`, `items`)
- Crée la souscription
- Crée les items de souscription
- Génère les 4 paiements
- Retourne une `TontineSubscriptionResource`

### ✅ Logging activé
Tous les logs API sont maintenant sauvegardés dans `storage/logs/api-YYYY-MM-DD.log`

## Prochaines Étapes de Débogage

### 1. Vérifier l'URL exacte utilisée par l'app mobile

L'erreur "The POST method is not supported" suggère que l'application mobile pourrait appeler :
- ❌ `GET /api/subscriptions` au lieu de `POST /api/subscriptions`
- ❌ Une URL différente qui n'accepte que GET

**Action** : Vérifiez dans le code Flutter l'URL exacte et la méthode HTTP

### 2. Vérifier le code Flutter

Cherchez dans `tontine_parfums_app` ou `flutter_app` :

```dart
// Le code devrait ressembler à ça :
http.post(
  Uri.parse('$baseUrl/api/subscriptions'),  // ← Vérifiez cette URL
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    'Authorization': 'Bearer $token',
  },
  body: jsonEncode({
    'tontine_id': tontineId,
    'items': items,
  }),
);
```

**Erreurs possibles :**
```dart
// ❌ MAUVAIS - Utilise GET au lieu de POST
http.get(Uri.parse('$baseUrl/api/subscriptions'));

// ❌ MAUVAIS - URL incorrecte
http.post(Uri.parse('$baseUrl/api/subscription')); // manque le 's'

// ❌ MAUVAIS - URL différente
http.post(Uri.parse('$baseUrl/api/tontines/$tontineId/subscribe'));
```

### 3. Lancer le serveur et tester

```bash
# Terminal 1 : Lancer le serveur
php artisan serve

# Terminal 2 : Surveiller les logs en temps réel
powershell -Command "Get-Content storage\logs\api-*.log -Wait -Tail 50"
```

### 4. Faire une tentative depuis l'app mobile

Puis consultez immédiatement les logs pour voir :
- L'URL exacte appelée
- La méthode HTTP utilisée
- Le body de la requête
- La réponse du serveur

## Test Manuel avec cURL

Pour vérifier que la route fonctionne côté serveur :

### Étape 1 : Obtenir un token
```bash
curl -X POST http://localhost:8000/api/login ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -d "{\"email\":\"client@example.com\",\"password\":\"password\"}"
```

Copiez le token retourné.

### Étape 2 : Tester la souscription
```bash
curl -X POST http://localhost:8000/api/subscriptions ^
  -H "Content-Type: application/json" ^
  -H "Accept: application/json" ^
  -H "Authorization: Bearer VOTRE_TOKEN_ICI" ^
  -d "{\"tontine_id\":1,\"items\":[{\"perfume_id\":1,\"quantity\":2}]}"
```

Si cURL fonctionne ✅, le problème est dans l'app mobile Flutter.
Si cURL échoue ❌, le problème est côté serveur Laravel.

## Checklist de Vérification App Mobile

Dans le code Flutter, vérifiez :

- [ ] L'URL de base est correcte (ex: `http://10.0.2.2:8000` pour émulateur Android)
- [ ] La route est `/api/subscriptions` (avec le 's')
- [ ] La méthode est `http.post()` et non `http.get()`
- [ ] Le header `Content-Type: application/json` est présent
- [ ] Le header `Accept: application/json` est présent
- [ ] Le header `Authorization: Bearer $token` est présent avec un token valide
- [ ] Le body est encodé en JSON avec `jsonEncode()`
- [ ] La structure du body correspond à ce qui est attendu :
  ```json
  {
    "tontine_id": 1,
    "items": [
      {"perfume_id": 1, "quantity": 2}
    ]
  }
  ```

## Exemple de Code Flutter Correct

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

Future<Map<String, dynamic>> createSubscription({
  required int tontineId,
  required List<Map<String, int>> items,
  required String token,
}) async {
  final url = Uri.parse('http://10.0.2.2:8000/api/subscriptions');

  final response = await http.post(
    url,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': 'Bearer $token',
    },
    body: jsonEncode({
      'tontine_id': tontineId,
      'items': items,
    }),
  );

  if (response.statusCode == 201) {
    return jsonDecode(response.body);
  } else {
    throw Exception('Erreur: ${response.body}');
  }
}

// Utilisation :
final result = await createSubscription(
  tontineId: 1,
  items: [
    {'perfume_id': 1, 'quantity': 2},
    {'perfume_id': 3, 'quantity': 1},
  ],
  token: 'votre_token_ici',
);
```

## Que Faire Maintenant ?

1. **Relancez le serveur Laravel** pour activer le logging :
   ```bash
   php artisan serve
   ```

2. **Tentez une souscription depuis l'app mobile**

3. **Consultez les logs** :
   ```bash
   type storage\logs\api-*.log
   ```

4. **Analysez les logs** pour voir :
   - L'URL exacte appelée
   - La méthode HTTP utilisée
   - Si l'authentification fonctionne

5. **Partagez les logs** si vous avez besoin d'aide

Les logs vont maintenant capturer TOUTES les informations nécessaires pour identifier le problème exact !
