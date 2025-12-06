# 📱 Code Complet Flutter - Tontine Parfums

Ce document contient TOUT le code nécessaire pour l'application Flutter.
Copiez chaque fichier dans votre projet Flutter.

---

## 📦 MODÈLES (lib/models/)

### user_model.dart

```dart
class User {
  final int id;
  final String name;
  final String email;
  final String phone;
  final String role;
  final bool isAdmin;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  User({
    required this.id,
    required this.name,
    required this.email,
    required this.phone,
    required this.role,
    required this.isAdmin,
    this.createdAt,
    this.updatedAt,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      phone: json['phone'],
      role: json['role'],
      isAdmin: json['is_admin'] ?? false,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'phone': phone,
      'role': role,
      'is_admin': isAdmin,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }
}
```

### supplier_model.dart

```dart
class Supplier {
  final int id;
  final String name;
  final String? contact;
  final String? address;

  Supplier({
    required this.id,
    required this.name,
    this.contact,
    this.address,
  });

  factory Supplier.fromJson(Map<String, dynamic> json) {
    return Supplier(
      id: json['id'],
      name: json['name'],
      contact: json['contact'],
      address: json['address'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'contact': contact,
      'address': address,
    };
  }
}
```

### perfume_model.dart

```dart
import 'supplier_model.dart';

class Perfume {
  final int id;
  final String name;
  final String brand;
  final String? description;
  final double price;
  final double? prixAchat;
  final int stockQuantity;
  final bool isAvailable;
  final String? imageUrl;
  final Supplier? supplier;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Perfume({
    required this.id,
    required this.name,
    required this.brand,
    this.description,
    required this.price,
    this.prixAchat,
    required this.stockQuantity,
    required this.isAvailable,
    this.imageUrl,
    this.supplier,
    this.createdAt,
    this.updatedAt,
  });

  factory Perfume.fromJson(Map<String, dynamic> json) {
    return Perfume(
      id: json['id'],
      name: json['name'],
      brand: json['brand'],
      description: json['description'],
      price: (json['price'] as num).toDouble(),
      prixAchat:
          json['prix_achat'] != null ? (json['prix_achat'] as num).toDouble() : null,
      stockQuantity: json['stock_quantity'],
      isAvailable: json['is_available'] ?? false,
      imageUrl: json['image_url'],
      supplier:
          json['supplier'] != null ? Supplier.fromJson(json['supplier']) : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'brand': brand,
      'description': description,
      'price': price,
      'prix_achat': prixAchat,
      'stock_quantity': stockQuantity,
      'is_available': isAvailable,
      'image_url': imageUrl,
      'supplier': supplier?.toJson(),
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }
}
```

### tontine_model.dart

```dart
class Tontine {
  final int id;
  final String name;
  final String? description;
  final DateTime? startDate;
  final DateTime? endDate;
  final int durationDays;
  final String status;
  final int participantsCount;
  final List<PaymentSchedule>? paymentSchedule;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Tontine({
    required this.id,
    required this.name,
    this.description,
    this.startDate,
    this.endDate,
    required this.durationDays,
    required this.status,
    required this.participantsCount,
    this.paymentSchedule,
    this.createdAt,
    this.updatedAt,
  });

  factory Tontine.fromJson(Map<String, dynamic> json) {
    return Tontine(
      id: json['id'],
      name: json['name'],
      description: json['description'],
      startDate:
          json['start_date'] != null ? DateTime.parse(json['start_date']) : null,
      endDate: json['end_date'] != null ? DateTime.parse(json['end_date']) : null,
      durationDays: json['duration_days'] ?? 45,
      status: json['status'],
      participantsCount: json['participants_count'] ?? 0,
      paymentSchedule: json['payment_schedule'] != null
          ? (json['payment_schedule'] as List)
              .map((e) => PaymentSchedule.fromJson(e))
              .toList()
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  String get statusText {
    switch (status.toLowerCase()) {
      case 'active':
        return 'Active';
      case 'pending':
        return 'En attente';
      case 'completed':
        return 'Terminée';
      case 'cancelled':
        return 'Annulée';
      default:
        return status;
    }
  }
}

class PaymentSchedule {
  final int number;
  final DateTime dueDate;
  final String? description;

  PaymentSchedule({
    required this.number,
    required this.dueDate,
    this.description,
  });

  factory PaymentSchedule.fromJson(Map<String, dynamic> json) {
    return PaymentSchedule(
      number: json['number'],
      dueDate: DateTime.parse(json['due_date']),
      description: json['description'],
    );
  }
}
```

### payment_model.dart

```dart
class Payment {
  final int id;
  final int subscriptionId;
  final double amount;
  final DateTime? dueDate;
  final DateTime? paymentDate;
  final String status;
  final String? paymentMethod;
  final String? paymentReference;
  final DateTime? createdAt;

  Payment({
    required this.id,
    required this.subscriptionId,
    required this.amount,
    this.dueDate,
    this.paymentDate,
    required this.status,
    this.paymentMethod,
    this.paymentReference,
    this.createdAt,
  });

  factory Payment.fromJson(Map<String, dynamic> json) {
    return Payment(
      id: json['id'],
      subscriptionId: json['subscription_id'],
      amount: (json['amount'] as num).toDouble(),
      dueDate: json['due_date'] != null ? DateTime.parse(json['due_date']) : null,
      paymentDate: json['payment_date'] != null
          ? DateTime.parse(json['payment_date'])
          : null,
      status: json['status'],
      paymentMethod: json['payment_method'],
      paymentReference: json['payment_reference'],
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
    );
  }

  String get statusText {
    switch (status.toLowerCase()) {
      case 'paid':
        return 'Payé';
      case 'pending':
        return 'En attente';
      case 'late':
        return 'En retard';
      case 'cancelled':
        return 'Annulé';
      default:
        return status;
    }
  }

  bool get isPaid => status.toLowerCase() == 'paid';
  bool get isPending => status.toLowerCase() == 'pending';
  bool get isLate => status.toLowerCase() == 'late';
}
```

### subscription_model.dart

```dart
import 'tontine_model.dart';
import 'payment_model.dart';
import 'perfume_model.dart';

class Subscription {
  final int id;
  final Tontine? tontine;
  final List<SubscriptionItem>? items;
  final double totalAmount;
  final double totalPaid;
  final String status;
  final DateTime? subscriptionDate;
  final List<Payment>? payments;
  final DateTime? createdAt;

  Subscription({
    required this.id,
    this.tontine,
    this.items,
    required this.totalAmount,
    required this.totalPaid,
    required this.status,
    this.subscriptionDate,
    this.payments,
    this.createdAt,
  });

  factory Subscription.fromJson(Map<String, dynamic> json) {
    return Subscription(
      id: json['id'],
      tontine: json['tontine'] != null ? Tontine.fromJson(json['tontine']) : null,
      items: json['items'] != null
          ? (json['items'] as List).map((e) => SubscriptionItem.fromJson(e)).toList()
          : null,
      totalAmount: (json['total_amount'] as num).toDouble(),
      totalPaid: (json['total_paid'] as num).toDouble(),
      status: json['status'],
      subscriptionDate: json['subscription_date'] != null
          ? DateTime.parse(json['subscription_date'])
          : null,
      payments: json['payments'] != null
          ? (json['payments'] as List).map((e) => Payment.fromJson(e)).toList()
          : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
    );
  }

  double get remainingAmount => totalAmount - totalPaid;
  double get progressPercentage =>
      totalAmount > 0 ? (totalPaid / totalAmount) * 100 : 0;

  String get statusText {
    switch (status.toLowerCase()) {
      case 'active':
        return 'Active';
      case 'completed':
        return 'Complétée';
      case 'cancelled':
        return 'Annulée';
      default:
        return status;
    }
  }
}

class SubscriptionItem {
  final int id;
  final Perfume? perfume;
  final int quantity;
  final double unitPrice;
  final double subtotal;

  SubscriptionItem({
    required this.id,
    this.perfume,
    required this.quantity,
    required this.unitPrice,
    required this.subtotal,
  });

  factory SubscriptionItem.fromJson(Map<String, dynamic> json) {
    return SubscriptionItem(
      id: json['id'],
      perfume: json['perfume'] != null ? Perfume.fromJson(json['perfume']) : null,
      quantity: json['quantity'],
      unitPrice: (json['unit_price'] as num).toDouble(),
      subtotal: (json['subtotal'] as num).toDouble(),
    );
  }
}
```

### cart_item.dart

```dart
import 'perfume_model.dart';

class CartItem {
  final Perfume perfume;
  int quantity;

  CartItem({
    required this.perfume,
    required this.quantity,
  });

  double get subtotal => perfume.price * quantity;

  Map<String, dynamic> toJson() {
    return {
      'perfume_id': perfume.id,
      'quantity': quantity,
    };
  }
}
```

---

## 🔧 SERVICES (lib/services/)

### storage_service.dart

```dart
import 'package:shared_preferences.dart';
import 'package:flutter_secure_storage.dart';
import 'dart:convert';

class StorageService {
  static final StorageService _instance = StorageService._internal();
  factory StorageService() => _instance;
  StorageService._internal();

  final _secureStorage = const FlutterSecureStorage();
  SharedPreferences? _prefs;

  Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  // Secure Token Storage
  Future<void> saveToken(String token) async {
    await _secureStorage.write(key: 'auth_token', value: token);
  }

  Future<String?> getToken() async {
    return await _secureStorage.read(key: 'auth_token');
  }

  Future<void> deleteToken() async {
    await _secureStorage.delete(key: 'auth_token');
  }

  // User Data
  Future<void> saveUser(Map<String, dynamic> userData) async {
    await _prefs?.setString('user_data', jsonEncode(userData));
  }

  Map<String, dynamic>? getUser() {
    final userStr = _prefs?.getString('user_data');
    if (userStr != null) {
      return jsonDecode(userStr);
    }
    return null;
  }

  Future<void> deleteUser() async {
    await _prefs?.remove('user_data');
  }

  // Clear all data
  Future<void> clearAll() async {
    await deleteToken();
    await deleteUser();
    await _prefs?.clear();
  }
}
```

### api_service.dart

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../config/api_config.dart';
import '../models/api_response.dart';
import 'storage_service.dart';

class ApiService {
  final StorageService _storage = StorageService();

  Future<String?> _getToken() async {
    return await _storage.getToken();
  }

  Future<Map<String, String>> _getHeaders() async {
    final token = await _getToken();
    return ApiConfig.headers(token);
  }

  Future<ApiResponse<T>> get<T>(
    String endpoint,
    T Function(dynamic)? fromJson,
  ) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .get(
            Uri.parse('${ApiConfig.baseUrl}$endpoint'),
            headers: headers,
          )
          .timeout(ApiConfig.receiveTimeout);

      return _handleResponse<T>(response, fromJson);
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  Future<ApiResponse<T>> post<T>(
    String endpoint,
    Map<String, dynamic> body,
    T Function(dynamic)? fromJson,
  ) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .post(
            Uri.parse('${ApiConfig.baseUrl}$endpoint'),
            headers: headers,
            body: jsonEncode(body),
          )
          .timeout(ApiConfig.receiveTimeout);

      return _handleResponse<T>(response, fromJson);
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  Future<ApiResponse<T>> put<T>(
    String endpoint,
    Map<String, dynamic> body,
    T Function(dynamic)? fromJson,
  ) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .put(
            Uri.parse('${ApiConfig.baseUrl}$endpoint'),
            headers: headers,
            body: jsonEncode(body),
          )
          .timeout(ApiConfig.receiveTimeout);

      return _handleResponse<T>(response, fromJson);
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  Future<ApiResponse<T>> delete<T>(
    String endpoint,
    T Function(dynamic)? fromJson,
  ) async {
    try {
      final headers = await _getHeaders();
      final response = await http
          .delete(
            Uri.parse('${ApiConfig.baseUrl}$endpoint'),
            headers: headers,
          )
          .timeout(ApiConfig.receiveTimeout);

      return _handleResponse<T>(response, fromJson);
    } catch (e) {
      return ApiResponse.error(e.toString());
    }
  }

  ApiResponse<T> _handleResponse<T>(
    http.Response response,
    T Function(dynamic)? fromJson,
  ) {
    final jsonResponse = jsonDecode(response.body);

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return ApiResponse<T>.fromJson(jsonResponse, fromJson);
    } else {
      return ApiResponse.error(
        jsonResponse['message'] ?? 'Une erreur est survenue',
        errors: jsonResponse['errors'],
      );
    }
  }
}
```

**Note:** Les fichiers `auth_service.dart`, `perfume_service.dart`, `tontine_service.dart`, `subscription_service.dart`, et `payment_service.dart` utilisent `ApiService` pour faire les requêtes.

Je vais créer `auth_service.dart` comme exemple complet, et vous pourrez créer les autres de manière similaire.

### auth_service.dart

```dart
import '../models/api_response.dart';
import '../models/user_model.dart';
import '../config/api_config.dart';
import 'api_service.dart';
import 'storage_service.dart';

class AuthService {
  final ApiService _apiService = ApiService();
  final StorageService _storage = StorageService();

  Future<ApiResponse<AuthResponse>> register({
    required String name,
    required String email,
    required String phone,
    required String password,
    required String passwordConfirmation,
  }) async {
    final response = await _apiService.post(
      ApiConfig.register,
      {
        'name': name,
        'email': email,
        'phone': phone,
        'password': password,
        'password_confirmation': passwordConfirmation,
      },
      (data) => AuthResponse.fromJson(data),
    );

    if (response.success && response.data != null) {
      await _storage.saveToken(response.data!.token);
      await _storage.saveUser(response.data!.user.toJson());
    }

    return response;
  }

  Future<ApiResponse<AuthResponse>> login({
    required String email,
    required String password,
  }) async {
    final response = await _apiService.post(
      ApiConfig.login,
      {
        'email': email,
        'password': password,
      },
      (data) => AuthResponse.fromJson(data),
    );

    if (response.success && response.data != null) {
      await _storage.saveToken(response.data!.token);
      await _storage.saveUser(response.data!.user.toJson());
    }

    return response;
  }

  Future<ApiResponse<void>> logout() async {
    final response = await _apiService.post(ApiConfig.logout, {}, null);

    if (response.success) {
      await _storage.clearAll();
    }

    return response;
  }

  Future<ApiResponse<User>> getUser() async {
    return await _apiService.get(
      ApiConfig.user,
      (data) => User.fromJson(data),
    );
  }

  Future<ApiResponse<User>> updateProfile({
    String? name,
    String? phone,
    String? email,
    String? password,
    String? passwordConfirmation,
  }) async {
    final body = <String, dynamic>{};
    if (name != null) body['name'] = name;
    if (phone != null) body['phone'] = phone;
    if (email != null) body['email'] = email;
    if (password != null) {
      body['password'] = password;
      body['password_confirmation'] = passwordConfirmation;
    }

    final response = await _apiService.put(
      ApiConfig.updateProfile,
      body,
      (data) => User.fromJson(data),
    );

    if (response.success && response.data != null) {
      await _storage.saveUser(response.data!.toJson());
    }

    return response;
  }

  Future<bool> isLoggedIn() async {
    final token = await _storage.getToken();
    return token != null && token.isNotEmpty;
  }

  Future<User?> getCurrentUser() async {
    final userData = _storage.getUser();
    if (userData != null) {
      return User.fromJson(userData);
    }
    return null;
  }
}

class AuthResponse {
  final User user;
  final String token;

  AuthResponse({
    required this.user,
    required this.token,
  });

  factory AuthResponse.fromJson(Map<String, dynamic> json) {
    return AuthResponse(
      user: User.fromJson(json['user']),
      token: json['token'],
    );
  }
}
```

---

**Suite dans le prochain document...**

L'application Flutter est maintenant bien structurée ! Je vais créer un second document avec les Providers, Screens et Widgets.

Voulez-vous que je continue avec les Providers et les écrans ? 🚀
