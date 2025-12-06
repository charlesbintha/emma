# 📱 API Tontine Parfums - Documentation

Documentation complète de l'API REST pour l'application mobile Tontine Parfums.

## 🔐 Authentification

L'API utilise **Laravel Sanctum** pour l'authentification par token. Après connexion/inscription, vous recevrez un token d'accès à inclure dans l'en-tête `Authorization` de toutes les requêtes protégées.

**Format:** `Authorization: Bearer {token}`

---

## 📋 Base URL

```
http://localhost:8000/api
```

---

## 🔓 Endpoints Publics (Sans authentification)

### 1. Inscription

**POST** `/register`

Créer un nouveau compte utilisateur.

**Body:**
```json
{
  "name": "Jean Dupont",
  "email": "jean@example.com",
  "phone": "+221771234567",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Réponse (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "email": "jean@example.com",
      "phone": "+221771234567",
      "role": "client",
      "is_admin": false,
      "created_at": "2025-12-04 10:30:00",
      "updated_at": "2025-12-04 10:30:00"
    },
    "token": "1|abcdef123456..."
  }
}
```

### 2. Connexion

**POST** `/login`

Se connecter avec un compte existant.

**Body:**
```json
{
  "email": "jean@example.com",
  "password": "password123"
}
```

**Réponse (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "Jean Dupont",
      "email": "jean@example.com",
      "phone": "+221771234567",
      "role": "client",
      "is_admin": false,
      "created_at": "2025-12-04 10:30:00",
      "updated_at": "2025-12-04 10:30:00"
    },
    "token": "2|xyz789..."
  }
}
```

---

## 🔒 Endpoints Protégés (Authentification requise)

**Tous les endpoints suivants nécessitent le header:**
```
Authorization: Bearer {token}
```

---

### 👤 Profil Utilisateur

#### Obtenir le profil

**GET** `/user`

**Réponse (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Jean Dupont",
    "email": "jean@example.com",
    "phone": "+221771234567",
    "role": "client",
    "is_admin": false,
    "created_at": "2025-12-04 10:30:00",
    "updated_at": "2025-12-04 10:30:00"
  }
}
```

#### Mettre à jour le profil

**PUT** `/user/profile`

**Body (tous les champs sont optionnels):**
```json
{
  "name": "Jean Martin",
  "phone": "+221779876543",
  "email": "jean.martin@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

**Réponse (200):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "id": 1,
    "name": "Jean Martin",
    "email": "jean.martin@example.com",
    "phone": "+221779876543",
    "role": "client",
    "is_admin": false,
    "created_at": "2025-12-04 10:30:00",
    "updated_at": "2025-12-04 11:00:00"
  }
}
```

#### Déconnexion

**POST** `/logout`

Révoque le token actuel.

**Réponse (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

---

### 🎁 Parfums

#### Liste des parfums

**GET** `/perfumes`

**Query params (optionnels):**
- `search` - Rechercher par nom ou marque
- `supplier_id` - Filtrer par fournisseur
- `available` - Filtrer les disponibles (true/false)

**Exemple:** `/perfumes?search=chanel&available=true`

**Réponse (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Chanel N°5",
      "brand": "Chanel",
      "description": "Un classique intemporel...",
      "price": 50000,
      "prix_achat": 30000,
      "stock_quantity": 25,
      "is_available": true,
      "image_url": "http://localhost:8000/storage/perfumes/chanel5.jpg",
      "supplier": {
        "id": 1,
        "name": "Parfums de Luxe SARL"
      },
      "created_at": "2025-12-01 08:00:00"
    }
  ]
}
```

#### Détails d'un parfum

**GET** `/perfumes/{id}`

**Réponse (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Chanel N°5",
    "brand": "Chanel",
    "description": "Description complète du parfum...",
    "price": 50000,
    "prix_achat": 30000,
    "stock_quantity": 25,
    "is_available": true,
    "image_url": "http://localhost:8000/storage/perfumes/chanel5.jpg",
    "supplier": {
      "id": 1,
      "name": "Parfums de Luxe SARL",
      "contact": "+221771234567"
    },
    "created_at": "2025-12-01 08:00:00",
    "updated_at": "2025-12-03 14:00:00"
  }
}
```

---

### 💼 Tontines

#### Liste des tontines

**GET** `/tontines`

**Query params (optionnels):**
- `status` - active, pending, completed, cancelled

**Réponse (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Tontine Décembre 2025",
      "description": "Tontine pour les fêtes de fin d'année",
      "start_date": "2025-12-05",
      "end_date": "2026-01-19",
      "duration_days": 45,
      "status": "active",
      "participants_count": 25,
      "created_at": "2025-11-20 10:00:00"
    }
  ]
}
```

#### Détails d'une tontine

**GET** `/tontines/{id}`

**Réponse (200):**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Tontine Décembre 2025",
    "description": "Tontine pour les fêtes de fin d'année",
    "start_date": "2025-12-05",
    "end_date": "2026-01-19",
    "duration_days": 45,
    "status": "active",
    "participants_count": 25,
    "payment_schedule": [
      {"number": 1, "due_date": "2025-12-05", "amount_per_payment": "À calculer selon le panier"},
      {"number": 2, "due_date": "2025-12-20", "amount_per_payment": "À calculer selon le panier"},
      {"number": 3, "due_date": "2026-01-04", "amount_per_payment": "À calculer selon le panier"},
      {"number": 4, "due_date": "2026-01-19", "amount_per_payment": "À calculer selon le panier"}
    ],
    "created_at": "2025-11-20 10:00:00",
    "updated_at": "2025-11-20 10:00:00"
  }
}
```

---

### 🛒 Panier

#### Voir le panier

**GET** `/tontines/{tontineId}/cart`

**Réponse (200):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "perfume_id": 1,
        "perfume": {
          "id": 1,
          "name": "Chanel N°5",
          "brand": "Chanel",
          "price": 50000,
          "image_url": "..."
        },
        "quantity": 2,
        "unit_price": 50000,
        "subtotal": 100000
      }
    ],
    "total": 100000,
    "payment_per_installment": 25000
  }
}
```

#### Ajouter au panier

**POST** `/tontines/{tontineId}/cart/add`

**Body:**
```json
{
  "perfume_id": 1,
  "quantity": 2
}
```

**Réponse (200):**
```json
{
  "success": true,
  "message": "Item added to cart",
  "data": {
    "items": [...],
    "total": 100000
  }
}
```

#### Modifier la quantité

**PUT** `/tontines/{tontineId}/cart/{perfumeId}`

**Body:**
```json
{
  "quantity": 3
}
```

#### Retirer du panier

**DELETE** `/tontines/{tontineId}/cart/{perfumeId}`

#### Vider le panier

**DELETE** `/tontines/{tontineId}/cart`

---

### 📋 Souscriptions

#### S'inscrire à une tontine

**POST** `/tontines/{tontineId}/subscribe`

Confirme la souscription avec les articles du panier.

**Réponse (201):**
```json
{
  "success": true,
  "message": "Subscription created successfully",
  "data": {
    "id": 1,
    "tontine": {
      "id": 1,
      "name": "Tontine Décembre 2025"
    },
    "items": [
      {
        "perfume": {"id": 1, "name": "Chanel N°5"},
        "quantity": 2,
        "unit_price": 50000,
        "subtotal": 100000
      }
    ],
    "total_amount": 100000,
    "status": "active",
    "subscription_date": "2025-12-04",
    "payments": [
      {
        "id": 1,
        "amount": 25000,
        "due_date": "2025-12-05",
        "status": "pending"
      },
      {
        "id": 2,
        "amount": 25000,
        "due_date": "2025-12-20",
        "status": "pending"
      },
      {
        "id": 3,
        "amount": 25000,
        "due_date": "2026-01-04",
        "status": "pending"
      },
      {
        "id": 4,
        "amount": 25000,
        "due_date": "2026-01-19",
        "status": "pending"
      }
    ]
  }
}
```

#### Mes inscriptions

**GET** `/subscriptions`

**Réponse (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "tontine": {
        "id": 1,
        "name": "Tontine Décembre 2025",
        "start_date": "2025-12-05",
        "end_date": "2026-01-19"
      },
      "total_amount": 100000,
      "total_paid": 50000,
      "status": "active",
      "payments_summary": {
        "total": 4,
        "paid": 2,
        "pending": 2,
        "late": 0
      },
      "subscription_date": "2025-12-04"
    }
  ]
}
```

#### Détails d'une inscription

**GET** `/subscriptions/{id}`

#### Annuler une inscription

**POST** `/subscriptions/{id}/cancel`

---

### 💳 Paiements

#### Mes paiements

**GET** `/payments`

**Query params (optionnels):**
- `status` - pending, paid, late, cancelled

**Réponse (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "subscription_id": 1,
      "amount": 25000,
      "due_date": "2025-12-05",
      "payment_date": "2025-12-05",
      "status": "paid",
      "payment_method": "mobile_money",
      "payment_reference": "MM123456789"
    },
    {
      "id": 2,
      "subscription_id": 1,
      "amount": 25000,
      "due_date": "2025-12-20",
      "payment_date": null,
      "status": "pending",
      "payment_method": null,
      "payment_reference": null
    }
  ]
}
```

#### Paiements d'une souscription

**GET** `/subscriptions/{subscriptionId}/payments`

#### Détails d'un paiement

**GET** `/payments/{id}`

#### Effectuer un paiement

**POST** `/payments/{id}/pay`

**Body:**
```json
{
  "payment_method": "mobile_money",
  "payment_reference": "MM123456789"
}
```

**Réponse (200):**
```json
{
  "success": true,
  "message": "Payment recorded successfully",
  "data": {
    "id": 2,
    "amount": 25000,
    "status": "paid",
    "payment_date": "2025-12-20",
    "payment_method": "mobile_money",
    "payment_reference": "MM123456789"
  }
}
```

---

### 📊 Dashboard

#### Statistiques utilisateur

**GET** `/dashboard`

**Réponse (200):**
```json
{
  "success": true,
  "data": {
    "active_subscriptions": 2,
    "completed_subscriptions": 5,
    "pending_payments": 3,
    "total_paid": 250000,
    "total_due": 75000,
    "upcoming_payment": {
      "id": 5,
      "amount": 25000,
      "due_date": "2025-12-20",
      "subscription": {
        "id": 1,
        "tontine_name": "Tontine Décembre 2025"
      }
    }
  }
}
```

---

## ⚠️ Codes d'erreur

| Code | Description |
|------|-------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 401 | Non authentifié |
| 403 | Accès refusé |
| 404 | Ressource non trouvée |
| 422 | Erreur de validation |
| 500 | Erreur serveur |

## 📝 Format des erreurs

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```

---

## 🔧 Configuration

### CORS

Pour autoriser l'application mobile à accéder à l'API, configurez CORS dans `config/cors.php` :

```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'], // À restreindre en production
'allowed_headers' => ['*'],
```

### Sanctum

Configuration dans `config/sanctum.php` déjà effectuée.

---

## 🚀 Tests avec Postman/Insomnia

1. **Inscription/Connexion** : Obtenez un token
2. **Ajoutez le token** dans Authorization > Bearer Token
3. **Testez les endpoints protégés**

---

## 📱 Intégration Mobile

### Exemple avec Axios (React Native / Expo)

```javascript
import axios from 'axios';

const API_URL = 'http://localhost:8000/api';

// Configuration axios avec token
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  }
});

// Intercepteur pour ajouter le token
api.interceptors.request.use(
  async (config) => {
    const token = await AsyncStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Exemple de connexion
export const login = async (email, password) => {
  try {
    const response = await api.post('/login', { email, password });
    const { token, user } = response.data.data;
    await AsyncStorage.setItem('token', token);
    return { success: true, user };
  } catch (error) {
    return { success: false, message: error.response?.data?.message };
  }
};

// Exemple de récupération des parfums
export const getPerfumes = async () => {
  try {
    const response = await api.get('/perfumes');
    return response.data.data;
  } catch (error) {
    console.error(error);
    return [];
  }
};
```

---

## ✅ Statut d'implémentation

### Complètement implémenté
- ✅ Authentification (Register, Login, Logout, Profile)
- ✅ Routes API configurées
- ✅ Resources API créées
- ✅ Sanctum configuré

### À implémenter (squelettes créés)
- ⏳ Contrôleurs Perfumes, Tontines, Subscriptions, Payments, Dashboard
- ⏳ Validation des données
- ⏳ Gestion des erreurs avancée

Les squelettes des contrôleurs sont en place. Il suffit d'implémenter la logique métier dans chaque méthode.

---

**Version:** 1.0
**Dernière mise à jour:** 2025-12-04
