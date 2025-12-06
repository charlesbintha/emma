# 📱 Guide Complet Flutter - Tontine Parfums

## 🚀 Création du projet

```bash
flutter create tontine_parfums_app
cd tontine_parfums_app
```

## 📦 Configuration `pubspec.yaml`

Remplacez le contenu de `pubspec.yaml` par :

```yaml
name: tontine_parfums_app
description: Application mobile pour Tontine Parfums
publish_to: 'none'
version: 1.0.0+1

environment:
  sdk: '>=3.0.0 <4.0.0'

dependencies:
  flutter:
    sdk: flutter

  # UI
  cupertino_icons: ^1.0.2
  google_fonts: ^6.1.0
  cached_network_image: ^3.3.0
  shimmer: ^3.0.0
  flutter_svg: ^2.0.9

  # State Management
  provider: ^6.1.1

  # HTTP & API
  http: ^1.1.2
  dio: ^5.4.0

  # Storage
  shared_preferences: ^2.2.2
  flutter_secure_storage: ^9.0.0

  # Utils
  intl: ^0.19.0
  logger: ^2.0.2

  # Animations
  animate_do: ^3.1.2
  lottie: ^2.7.0

dev_dependencies:
  flutter_test:
    sdk: flutter
  flutter_launcher_icons: ^0.13.1
  flutter_native_splash: ^2.3.8

flutter:
  uses-material-design: true

  assets:
    - assets/images/
    - assets/animations/

  fonts:
    - family: Poppins
      fonts:
        - asset: fonts/Poppins-Regular.ttf
        - asset: fonts/Poppins-Medium.ttf
          weight: 500
        - asset: fonts/Poppins-SemiBold.ttf
          weight: 600
        - asset: fonts/Poppins-Bold.ttf
          weight: 700
```

## 📁 Structure du projet

```
lib/
├── main.dart
├── config/
│   ├── api_config.dart
│   ├── app_colors.dart
│   └── app_constants.dart
├── models/
│   ├── user_model.dart
│   ├── perfume_model.dart
│   ├── supplier_model.dart
│   ├── tontine_model.dart
│   ├── subscription_model.dart
│   ├── payment_model.dart
│   └── api_response.dart
├── services/
│   ├── api_service.dart
│   ├── auth_service.dart
│   ├── perfume_service.dart
│   ├── tontine_service.dart
│   ├── subscription_service.dart
│   ├── payment_service.dart
│   └── storage_service.dart
├── providers/
│   ├── auth_provider.dart
│   ├── cart_provider.dart
│   └── theme_provider.dart
├── screens/
│   ├── splash_screen.dart
│   ├── auth/
│   │   ├── login_screen.dart
│   │   └── register_screen.dart
│   ├── home/
│   │   ├── home_screen.dart
│   │   └── dashboard_screen.dart
│   ├── perfumes/
│   │   ├── perfumes_screen.dart
│   │   └── perfume_detail_screen.dart
│   ├── tontines/
│   │   ├── tontines_screen.dart
│   │   └── tontine_detail_screen.dart
│   ├── cart/
│   │   └── cart_screen.dart
│   ├── subscriptions/
│   │   ├── subscriptions_screen.dart
│   │   └── subscription_detail_screen.dart
│   ├── payments/
│   │   ├── payments_screen.dart
│   │   └── payment_detail_screen.dart
│   └── profile/
│       └── profile_screen.dart
└── widgets/
    ├── custom_button.dart
    ├── custom_text_field.dart
    ├── loading_widget.dart
    ├── error_widget.dart
    ├── perfume_card.dart
    ├── tontine_card.dart
    ├── payment_card.dart
    └── cart_item_card.dart
```

## ⚙️ Installation des dépendances

```bash
flutter pub get
```

## 🔧 Configuration Android (AndroidManifest.xml)

Dans `android/app/src/main/AndroidManifest.xml`, ajoutez les permissions Internet :

```xml
<manifest xmlns:android="http://schemas.android.com/apk/res/android">
    <!-- Ajoutez ces permissions -->
    <uses-permission android:name="android.permission.INTERNET"/>
    <uses-permission android:name="android.permission.ACCESS_NETWORK_STATE"/>

    <application
        android:label="Tontine Parfums"
        android:name="${applicationName}"
        android:icon="@mipmap/ic_launcher">
        <!-- ... -->
    </application>
</manifest>
```

## 🔧 Configuration iOS (Info.plist)

Dans `ios/Runner/Info.plist`, permettez les requêtes HTTP :

```xml
<key>NSAppTransportSecurity</key>
<dict>
    <key>NSAllowsArbitraryLoads</key>
    <true/>
</dict>
```

## 🎨 Thème de couleurs

Palette de couleurs pour l'application :

- **Primaire** : `#6C63FF` (Violet moderne)
- **Secondaire** : `#FF6584` (Rose)
- **Accent** : `#FFC107` (Doré)
- **Success** : `#4CAF50` (Vert)
- **Error** : `#F44336` (Rouge)
- **Background** : `#F8F9FA` (Gris clair)
- **Card** : `#FFFFFF` (Blanc)
- **Text** : `#2D3436` (Gris foncé)

## 🚀 Lancement

```bash
# Android
flutter run

# iOS
flutter run

# Web
flutter run -d chrome
```

## 📱 Fonctionnalités principales

### 1. Authentification
- Écran de connexion avec animations
- Écran d'inscription
- Gestion automatique du token
- Écran splash avec animation Lottie

### 2. Dashboard
- Statistiques utilisateur
- Paiements à venir
- Souscriptions actives
- Navigation fluide

### 3. Catalogue Parfums
- Liste avec recherche et filtres
- Images en cache
- Animations de transition
- Détails complets

### 4. Tontines
- Liste des tontines actives
- Calendrier de paiement
- Participants
- Statut en temps réel

### 5. Panier
- Ajout/suppression d'articles
- Calcul automatique
- Confirmation visuelle

### 6. Souscriptions
- Historique complet
- Détails avec items
- Progression des paiements
- Annulation

### 7. Paiements
- Liste filtrée
- Statuts visuels
- Paiement avec référence
- Historique

## 🎨 Animations utilisées

- **Fade In/Out** pour les transitions
- **Slide** pour les écrans
- **Scale** pour les boutons
- **Shimmer** pour le chargement
- **Hero** pour les images
- **Lottie** pour le splash

## 📝 Notes importantes

1. **API URL** : Modifiez `api_config.dart` avec votre URL d'API
2. **Token** : Stocké de manière sécurisée avec `flutter_secure_storage`
3. **Cache** : Les images sont mises en cache automatiquement
4. **Offline** : Gestion basique du mode hors ligne

## 🐛 Debugging

```bash
# Logs détaillés
flutter run --verbose

# Analyser le code
flutter analyze

# Tests
flutter test
```

## 📦 Build Production

### Android APK
```bash
flutter build apk --release
```

### Android App Bundle
```bash
flutter build appbundle --release
```

### iOS
```bash
flutter build ios --release
```

---

**Suivez les fichiers de code fournis pour implémenter chaque composant.**
