# Emma Luxury - Tontine Parfums

Application web de gestion de tontines pour l'achat de parfums de luxe. Les clients peuvent s'inscrire à des tontines, commander des parfums et payer en 4 tranches sur 45 jours.

## Fonctionnalités

### Interface Admin
- Gestion des fournisseurs et parfums
- Création et gestion des tontines
- Suivi des inscriptions et paiements
- Tableau de bord avec statistiques

### Interface Client
- Inscription aux tontines actives
- Sélection de parfums (panier)
- Suivi des paiements (4 tranches)
- Historique des commandes

## Calendrier des Paiements

Chaque tontine dure **45 jours** (1 mois et 15 jours) avec 4 versements :

| Versement | Jour | Exemple (début le 5) |
|-----------|------|----------------------|
| 1er | J+0 | 5 janvier |
| 2ème | J+15 | 20 janvier |
| 3ème | J+30 | 5 février |
| 4ème | J+45 | 20 février |

## Installation

```bash
# Cloner le projet
git clone <repository-url>
cd tontine-parfums

# Installer les dépendances
composer install
npm install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Configurer la base de données dans .env
# DB_DATABASE=tontine_parfums
# DB_USERNAME=root
# DB_PASSWORD=

# Exécuter les migrations et seeders
php artisan migrate:fresh --seed

# Compiler les assets
npm run dev

# Lancer le serveur
php artisan serve
```

## Comptes de Test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Admin | admin@tontine.com | password |
| Client | jean@example.com | password |
| Client | marie@example.com | password |

## Stack Technique

- **Backend** : Laravel 8.83
- **Frontend** : Blade, Tailwind CSS, Alpine.js
- **Base de données** : MySQL
- **Template** : Cuba Admin (Bootstrap 5)

---

## Roadmap - Projets Futurs

### Application Mobile Flutter (Client)

Application mobile pour les clients avec les fonctionnalités suivantes :

#### Fonctionnalités prévues
- [ ] Authentification (connexion/inscription)
- [ ] Liste des tontines disponibles
- [ ] Inscription à une tontine (sélection parfums, panier)
- [ ] Consultation des souscriptions
- [ ] Suivi des paiements (4 tranches sur 45 jours)
- [ ] Consultation des factures
- [ ] Notifications push (rappels de paiement)

#### Prérequis techniques
1. **API REST Laravel** à créer :
   - Authentification avec Laravel Sanctum
   - Endpoints : `/api/tontines`, `/api/subscriptions`, `/api/payments`, `/api/profile`
   - Documentation API (Swagger/OpenAPI)

2. **Intégrations Mobile Money** :
   - Orange Money
   - MTN Mobile Money
   - Autres opérateurs locaux

3. **Architecture Flutter** :
   - State management (Provider/Riverpod/Bloc)
   - Stockage local (Hive/SharedPreferences)
   - Firebase pour notifications push

#### Structure de l'app envisagée
```
lib/
├── core/
│   ├── api/
│   ├── models/
│   └── services/
├── features/
│   ├── auth/
│   ├── tontines/
│   ├── subscriptions/
│   ├── payments/
│   └── profile/
└── main.dart
```

---

## Documentation

Voir le fichier `CLAUDE.md` pour les détails techniques du projet.

## Licence

Projet privé - Emma Luxury
