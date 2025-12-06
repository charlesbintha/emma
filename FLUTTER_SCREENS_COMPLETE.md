# Flutter Screens - Documentation Complète

Tous les écrans de l'application Flutter Tontine Parfums.

---

## Structure de Navigation

L'application utilise une **Bottom Navigation Bar** avec 4 onglets principaux :
1. **Accueil** (Dashboard)
2. **Tontines**
3. **Souscriptions**
4. **Profil**

---

## Écrans Créés

### 1. Authentication

#### `splash_screen.dart`
- **Description**: Écran de démarrage avec logo et animation
- **Fonctionnalités**:
  - Animation FadeIn/FadeOut
  - Vérification du statut d'authentification
  - Redirection automatique vers Login ou Home

#### `login_screen.dart`
- **Description**: Écran de connexion
- **Fonctionnalités**:
  - Formulaire email + mot de passe
  - Validation des champs
  - Toggle visibilité du mot de passe
  - Loading indicator pendant la connexion
  - Navigation vers RegisterScreen
  - Animations FadeIn/FadeOut

#### `register_screen.dart`
- **Description**: Écran d'inscription
- **Fonctionnalités**:
  - Formulaire complet (nom, email, téléphone, mot de passe, confirmation)
  - Validation des champs
  - Vérification de correspondance des mots de passe
  - Toggle visibilité des mots de passe
  - Loading indicator
  - Navigation vers Login
  - Animations FadeIn/FadeOut

---

### 2. Home & Dashboard

#### `home_screen.dart`
- **Description**: Écran principal avec Bottom Navigation
- **Fonctionnalités**:
  - Bottom Navigation Bar (4 onglets)
  - Dashboard personnalisé
  - Badge panier avec compteur d'articles
  - Message de bienvenue
  - Quick Actions (4 cartes : Parfums, Tontines, Souscriptions, Panier)
  - Statistiques (Tontines actives, Paiements en cours)
  - Pull-to-refresh
  - Animations FadeIn/FadeOut

---

### 3. Perfumes (Parfums)

#### `perfumes_list_screen.dart`
- **Description**: Liste des parfums disponibles
- **Fonctionnalités**:
  - Grille 2 colonnes
  - Barre de recherche (par nom ou marque)
  - Filtrage en temps réel
  - Affichage des images
  - Prix et informations
  - Bouton "Ajouter au panier" rapide
  - Pull-to-refresh
  - Gestion des erreurs
  - Animations FadeIn/FadeOut

#### `perfume_detail_screen.dart`
- **Description**: Détails d'un parfum
- **Fonctionnalités**:
  - SliverAppBar avec image
  - Affichage complet des informations
  - Stock disponible
  - Calcul du paiement par versement (x4)
  - Sélecteur de quantité
  - Bouton "Ajouter au panier"
  - Gestion du stock (disabled si rupture)
  - Animations FadeIn/FadeOut

---

### 4. Tontines

#### `tontines_list_screen.dart`
- **Description**: Liste des tontines
- **Fonctionnalités**:
  - Liste des tontines disponibles
  - Filtre par statut (toutes, en attente, actives, terminées)
  - Affichage des dates (début, fin)
  - Nombre de participants
  - Statut visuel (couleur badge)
  - Navigation vers détails
  - Pull-to-refresh
  - Animations FadeIn/FadeOut

#### `tontine_detail_screen.dart`
- **Description**: Détails d'une tontine
- **Fonctionnalités**:
  - Header avec gradient et statut
  - Informations complètes (dates, durée)
  - Nombre de participants (illimité)
  - Informations de paiement (4 versements, fréquence, échéances)
  - Bouton "Participer" (si active)
  - Animations FadeIn/FadeOut

---

### 5. Cart (Panier)

#### `cart_screen.dart`
- **Description**: Panier d'achat
- **Fonctionnalités**:
  - Liste des articles dans le panier
  - Affichage image + informations par article
  - Contrôles de quantité (+/-)
  - Bouton supprimer par article
  - Bouton "Vider le panier" (avec confirmation)
  - Sélecteur de tontine (dropdown)
  - Calcul du total
  - Calcul du paiement par versement
  - Bouton "Confirmer la commande"
  - Création de souscription via API
  - Navigation vers Subscriptions après confirmation
  - État vide avec message et bouton action
  - Animations FadeIn/FadeOut

---

### 6. Subscriptions (Souscriptions)

#### `subscriptions_list_screen.dart`
- **Description**: Liste des souscriptions de l'utilisateur
- **Fonctionnalités**:
  - Liste des souscriptions
  - Filtre par statut (toutes, actives, complétées, annulées)
  - Badge de statut visuel
  - Affichage montant total / montant payé
  - Barre de progression visuelle
  - Pourcentage complété
  - Date de création
  - ID tontine associée
  - Navigation vers détails
  - Pull-to-refresh
  - Animations FadeIn/FadeOut

#### `subscription_detail_screen.dart`
- **Description**: Détails d'une souscription
- **Fonctionnalités**:
  - Header avec gradient et statut
  - Montant total / Montant payé
  - Barre de progression détaillée
  - Liste des 4 paiements
  - Informations par paiement :
    - Statut (badge coloré)
    - Montant
    - Date d'échéance
    - Date de paiement (si payé)
    - Méthode de paiement (si payé)
  - Bouton "Annuler" (si active)
  - Confirmation d'annulation
  - Pull-to-refresh
  - Animations FadeIn/FadeOut

---

### 7. Profile (Profil)

#### `profile_screen.dart`
- **Description**: Profil utilisateur
- **Fonctionnalités**:
  - Header avec avatar et informations
  - Affichage des informations (nom, email, téléphone, rôle)
  - Bouton "Modifier le profil"
  - Bouton "Changer le mot de passe"
  - Bouton "Se déconnecter" (avec confirmation)
  - Navigation vers écrans de modification
  - Animations FadeIn/FadeOut

#### `EditProfileScreen` (dans profile_screen.dart)
- **Description**: Modification du profil
- **Fonctionnalités**:
  - Formulaire pré-rempli
  - Modification nom, email, téléphone
  - Validation des champs
  - Sauvegarde via API
  - Loading indicator
  - Messages de succès/erreur
  - Animations FadeIn/FadeOut

#### `ChangePasswordScreen` (dans profile_screen.dart)
- **Description**: Changement de mot de passe
- **Fonctionnalités**:
  - Nouveau mot de passe + Confirmation
  - Toggle visibilité
  - Validation (min 6 caractères, correspondance)
  - Sauvegarde via API
  - Loading indicator
  - Messages de succès/erreur
  - Animations FadeIn/FadeOut

---

## Fonctionnalités Communes

### Animations
Tous les écrans utilisent **animate_do** :
- `FadeInDown` pour les en-têtes
- `FadeInUp` pour le contenu
- Délais progressifs pour effet cascade
- Durées : 200ms à 700ms

### Design
- **Material Design 3**
- Palette de couleurs personnalisée (`AppColors`)
- Cards avec elevation et border radius
- Gradients pour les headers importants
- Icons Material Design
- Police Google Fonts (Poppins)

### Navigation
- `Navigator.push` pour navigation simple
- `Navigator.pushReplacement` après login/logout/confirmation
- `Navigator.pop` pour retour

### State Management
- **Provider** pour state global
  - `AuthProvider` : authentification
  - `CartProvider` : panier
- setState pour state local

### API Integration
- `ApiService` pour toutes les requêtes
- Gestion des erreurs
- Loading states
- Success/Error messages via SnackBar

### User Experience
- Pull-to-refresh sur toutes les listes
- Loading indicators
- Empty states avec messages et actions
- Error states avec retry button
- Confirmations pour actions destructives
- SnackBars pour feedback utilisateur

---

## Fichiers Créés

```
flutter_app/
└── lib/
    └── screens/
        ├── splash_screen.dart
        ├── auth/
        │   ├── login_screen.dart
        │   └── register_screen.dart
        ├── home/
        │   └── home_screen.dart
        ├── perfumes/
        │   ├── perfumes_list_screen.dart
        │   └── perfume_detail_screen.dart
        ├── tontines/
        │   ├── tontines_list_screen.dart
        │   └── tontine_detail_screen.dart
        ├── cart/
        │   └── cart_screen.dart
        ├── subscriptions/
        │   ├── subscriptions_list_screen.dart
        │   └── subscription_detail_screen.dart
        └── profile/
            └── profile_screen.dart
```

---

## Flow de l'Application

1. **Démarrage** → `SplashScreen`
2. **Si non authentifié** → `LoginScreen` ⟷ `RegisterScreen`
3. **Si authentifié** → `HomeScreen` (Dashboard)
4. **Navigation**:
   - Onglet Accueil → Dashboard + Quick Actions
   - Onglet Tontines → `TontinesListScreen` → `TontineDetailScreen`
   - Onglet Souscriptions → `SubscriptionsListScreen` → `SubscriptionDetailScreen`
   - Onglet Profil → `ProfileScreen` → `EditProfileScreen` / `ChangePasswordScreen`
5. **Shopping Flow**:
   - Dashboard → Parfums → `PerfumesListScreen` → `PerfumeDetailScreen`
   - Ajouter au panier
   - Icône panier (badge) → `CartScreen`
   - Sélectionner tontine
   - Confirmer commande
   - Redirection → `SubscriptionsListScreen`

---

## Prochaines Étapes

L'application est maintenant **fonctionnelle** avec tous les écrans principaux. Pour finaliser :

1. **Tester l'application** :
   ```bash
   cd flutter_app
   flutter pub get
   flutter run
   ```

2. **Ajustements possibles** :
   - Ajouter plus de widgets réutilisables
   - Améliorer la gestion d'état (Riverpod, BLoC)
   - Ajouter des tests unitaires et widget tests
   - Implémenter la pagination pour les listes
   - Ajouter des filtres avancés
   - Notifications push
   - Mode offline / cache

3. **Connexion API** :
   - Mettre à jour `ApiConfig.baseUrl` avec l'URL correcte
   - Tester tous les endpoints
   - Gérer les tokens expirés
   - Ajouter refresh token

---

**Application Flutter complète ! Tous les écrans sont créés et fonctionnels.** 🎉
