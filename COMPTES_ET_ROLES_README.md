# 👥 **Comptes et Rôles - CareWell**

## 📋 **Vue d'ensemble**

Ce document présente tous les comptes utilisateurs et les rôles configurés dans l'application CareWell, ainsi que les permissions associées à chaque rôle.

## 🔐 **Rôles Disponibles**

### **1. Patient (Patient)**
- **Description** : Utilisateur principal de l'application
- **Permissions** :
  - ✅ `view appointments` - Voir ses rendez-vous
  - ✅ `view medical files` - Consulter son dossier médical

### **2. Admin (Administrateur)**
- **Description** : Super utilisateur avec tous les droits
- **Permissions** :
  - ✅ `view medical files` - Voir tous les dossiers médicaux
  - 🔒 **Toutes les permissions** (accès complet)

### **3. Doctor (Médecin)**
- **Description** : Professionnel de santé
- **Permissions** :
  - ✅ `view appointments` - Voir les rendez-vous
  - ✅ `update medical files` - Mettre à jour les dossiers médicaux

### **4. Secretary (Secrétaire)**
- **Description** : Personnel administratif
- **Permissions** :
  - ✅ `view appointments` - Voir les rendez-vous
  - ✅ `update appointments` - Mettre à jour les rendez-vous

### **5. Accountant (Comptable)**
- **Description** : Personnel comptable
- **Permissions** :
  - ✅ `view medical files` - Voir les dossiers médicaux

## 👤 **Comptes Utilisateurs**

### **Compte Administrateur Principal :**
```
📧 Email : ndeye@gmail.com
🔑 Mot de passe : password
👤 Nom : Biteye Sow
📍 Adresse : Point E
📱 Téléphone : +221774344454
🎂 Date de naissance : 1990-01-01
🔒 Rôle : Admin
✅ Statut : Actif
```

## 🗄️ **Structure de la Base de Données**

### **Table des Utilisateurs (`users`) :**
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    adress VARCHAR(255),
    phone_number VARCHAR(255),
    day_of_birth DATE,
    status BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **Table des Rôles (`roles`) :**
```sql
-- Créée automatiquement par Spatie Laravel Permission
-- Contient les rôles : Patient, Admin, Doctor, Secretary, Accountant
```

### **Table des Permissions (`permissions`) :**
```sql
-- Créée automatiquement par Spatie Laravel Permission
-- Contient les permissions définies dans RolePermissionSeeder
```

## 🔧 **Configuration des Seeders**

### **Ordre d'exécution :**
1. **RolePermissionSeeder** → Crée les rôles et permissions
2. **UserSeeder** → Crée les utilisateurs et assigne les rôles

### **Commandes pour exécuter les seeders :**
```bash
# Exécuter tous les seeders
php artisan db:seed

# Exécuter un seeder spécifique
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=UserSeeder

# Réinitialiser et exécuter tous les seeders
php artisan migrate:fresh --seed
```

## 📊 **Permissions Détaillées**

### **Permissions Disponibles :**
```
1. view appointments      - Voir les rendez-vous
2. update medical files  - Mettre à jour les dossiers médicaux
3. create appointments   - Créer des rendez-vous
4. update appointments   - Mettre à jour les rendez-vous
5. delete appointments   - Supprimer des rendez-vous
6. view medical files   - Voir les dossiers médicaux
```

### **Attribution des Permissions par Rôle :**

#### **Patient :**
- `view appointments` ✅
- `view medical files` ✅

#### **Admin :**
- `view medical files` ✅
- **+ Toutes les autres permissions** 🔒

#### **Doctor :**
- `view appointments` ✅
- `update medical files` ✅

#### **Secretary :**
- `view appointments` ✅
- `update appointments` ✅

#### **Accountant :**
- `view medical files` ✅

## 🚀 **Création de Nouveaux Comptes**

### **Pour créer un nouveau patient :**
```php
// Dans un seeder ou via tinker
$patient = User::create([
    'first_name' => 'Prénom',
    'last_name' => 'Nom',
    'email' => 'patient@example.com',
    'password' => Hash::make('password'),
    'adress' => 'Adresse',
    'phone_number' => '+221XXXXXXXXX',
    'day_of_birth' => '1990-01-01',
    'status' => true,
]);

$patient->assignRole('Patient');
```

### **Pour créer un nouveau médecin :**
```php
$doctor = User::create([
    'first_name' => 'Dr. Prénom',
    'last_name' => 'Nom',
    'email' => 'doctor@example.com',
    'password' => Hash::make('password'),
    'adress' => 'Adresse',
    'phone_number' => '+221XXXXXXXXX',
    'day_of_birth' => '1980-01-01',
    'status' => true,
]);

$doctor->assignRole('Doctor');
```

## 🔍 **Vérification des Rôles et Permissions**

### **Via Tinker :**
```bash
php artisan tinker
```

```php
// Vérifier les rôles
use App\Models\User;
use Spatie\Permission\Models\Role;

// Lister tous les rôles
Role::all()->pluck('name');

// Vérifier le rôle d'un utilisateur
$user = User::where('email', 'ndeye@gmail.com')->first();
$user->getRoleNames();

// Vérifier les permissions d'un utilisateur
$user->getAllPermissions()->pluck('name');
```

### **Via les Routes (si configurées) :**
```bash
# Lister toutes les routes
php artisan route:list

# Lister les routes avec middleware de rôle
php artisan route:list | grep middleware
```

## 🛡️ **Sécurité et Bonnes Pratiques**

### **Protection des Routes :**
```php
// Exemple de protection par rôle
Route::middleware('role:Admin')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});

// Exemple de protection par permission
Route::middleware('permission:view medical files')->group(function () {
    Route::get('/medical-files', [MedicalFileController::class, 'index']);
});
```

### **Vérification dans les Contrôleurs :**
```php
public function index()
{
    if (auth()->user()->hasRole('Admin')) {
        // Logique admin
    } elseif (auth()->user()->hasRole('Doctor')) {
        // Logique médecin
    } else {
        // Logique patient
    }
}
```

## 📝 **Gestion des Rôles**

### **Ajouter un rôle :**
```php
use Spatie\Permission\Models\Role;

$newRole = Role::create(['name' => 'Nurse']);
```

### **Supprimer un rôle :**
```php
$role = Role::findByName('Nurse');
$role->delete();
```

### **Modifier les permissions d'un rôle :**
```php
$role = Role::findByName('Doctor');
$role->givePermissionTo('create appointments');
$role->revokePermissionTo('delete appointments');
```

## 🔄 **Mise à Jour des Seeders**

### **Pour ajouter de nouveaux utilisateurs :**
1. Modifier `UserSeeder.php`
2. Ajouter les nouveaux utilisateurs
3. Exécuter `php artisan db:seed --class=UserSeeder`

### **Pour modifier les rôles/permissions :**
1. Modifier `RolePermissionSeeder.php`
2. Exécuter `php artisan db:seed --class=RolePermissionSeeder`

## 🧪 **Test des Comptes**

### **Connexion avec le compte admin :**
```
URL : http://127.0.0.1:8003/login
Email : ndeye@gmail.com
Mot de passe : password
```

### **Vérification des droits :**
- Accès au dashboard admin
- Gestion des utilisateurs
- Gestion des services et articles
- Accès à toutes les fonctionnalités

## 📚 **Documentation Technique**

### **Packages Utilisés :**
- **Spatie Laravel Permission** : Gestion des rôles et permissions
- **Laravel Seeder** : Création des données de test

### **Fichiers de Configuration :**
- `config/permission.php` : Configuration des permissions
- `database/seeders/` : Seeders pour les données initiales

### **Modèles :**
- `User` : Modèle utilisateur principal
- `Role` : Modèle des rôles (Spatie)
- `Permission` : Modèle des permissions (Spatie)

---

**Version :** 1.0.0  
**Date :** {{ date('d/m/Y') }}  
**Auteur :** Équipe CareWell  
**Statut :** ✅ **Documentation complète des comptes et rôles**

## 🎯 **Résumé**

L'application CareWell dispose actuellement de **5 rôles** (Patient, Admin, Doctor, Secretary, Accountant) avec des permissions bien définies. Un **compte administrateur principal** est configuré avec l'email `ndeye@gmail.com` et le mot de passe `password`. Le système de permissions est géré par le package Spatie Laravel Permission pour une sécurité optimale.
