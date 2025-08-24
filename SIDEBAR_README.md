# Sidebars pour chaque profil - CareWell

Ce projet implémente des sidebars personnalisées pour chaque type de profil utilisateur dans l'application CareWell.

## 📁 Structure des fichiers

### Layouts de base
- `resources/views/layouts/dashboard.blade.php` - Layout principal avec sidebar
- `resources/views/layouts/admin.blade.php` - Layout spécifique administrateur
- `resources/views/layouts/doctor.blade.php` - Layout spécifique médecin
- `resources/views/layouts/patient.blade.php` - Layout spécifique patient
- `resources/views/layouts/secretary.blade.php` - Layout spécifique secrétariat

## 🎯 Utilisation

### Pour l'Administrateur
```php
@extends('layouts.admin')

@section('content')
    <!-- Votre contenu ici -->
@endsection
```

### Pour le Médecin
```php
@extends('layouts.doctor')

@section('content')
    <!-- Votre contenu ici -->
@endsection
```

### Pour le Patient
```php
@extends('layouts.patient')

@section('content')
    <!-- Votre contenu ici -->
@endsection
```

### Pour le Secrétariat
```php
@extends('layouts.secretary')

@section('content')
    <!-- Votre contenu ici -->
@endsection
```

## ✨ Fonctionnalités des sidebars

### 🔧 Caractéristiques communes
- **Responsive** : S'adapte aux écrans mobiles et desktop
- **Collapsible** : Peut être réduite pour économiser l'espace
- **Navigation active** : Met en surbrillance la page active
- **Icônes FontAwesome** : Interface intuitive et moderne
- **Thème cohérent** : Utilise les couleurs de CareWell

### 📱 Responsive Design
- **Desktop** : Sidebar complète (280px)
- **Mobile** : Sidebar rétractable avec bouton toggle
- **Tablet** : Adaptation automatique selon la taille d'écran

### 🎨 Personnalisation
Chaque profil a sa propre sidebar avec :
- **Sections organisées** par catégorie
- **Liens spécifiques** au rôle de l'utilisateur
- **Icônes contextuelles** pour chaque fonction
- **Navigation hiérarchique** claire et logique

## 🚀 Mise en œuvre

### 1. Vérifier les routes
Assurez-vous que toutes les routes référencées dans les sidebars existent dans votre fichier `routes/web.php`.

### 2. Adapter les contrôleurs
Les contrôleurs doivent retourner les bonnes vues avec les bons layouts.

### 3. Tester la responsivité
Vérifiez que les sidebars fonctionnent correctement sur tous les appareils.

## 📋 Routes nécessaires

### Administrateur
- `admin.dashboard`
- `admin.users`
- `admin.services`
- `admin.appointments`
- Etc.

### Médecin
- `doctor.dashboard`
- `doctor.patients`
- `doctor.appointments`
- `doctor.prescriptions`
- Etc.

### Patient
- `patient.dashboard`
- `appointments.*`
- `medical-files.*`
- `prescriptions.*`
- Etc.

### Secrétariat
- `secretary.dashboard`
- `secretary.appointments.*`
- `secretary.patients.*`
- `secretary.doctors.*`
- Etc.

## 🎯 Avantages

1. **Navigation intuitive** : Chaque profil voit uniquement les fonctionnalités pertinentes
2. **Interface cohérente** : Design uniforme à travers tous les dashboards
3. **Expérience utilisateur** : Navigation rapide et efficace
4. **Maintenance simplifiée** : Code centralisé et réutilisable
5. **Responsive** : Fonctionne sur tous les appareils

## 🔧 Personnalisation avancée

### Ajouter de nouvelles sections
```php
<div class="nav-section">
    <div class="nav-section-title">Nouvelle Section</div>
    <div class="nav-item">
        <a href="{{ route('nouvelle.route') }}" class="nav-link">
            <i class="fas fa-nouvelle-icone"></i>
            <span>Nouveau Lien</span>
        </a>
    </div>
</div>
```

### Modifier les couleurs
Les couleurs sont définies dans les variables CSS du layout `dashboard.blade.php` :
```css
:root {
    --primary-color: #2563eb;
    --secondary-color: #1e40af;
    /* ... autres couleurs */
}
```

### Ajouter des fonctionnalités JavaScript
Utilisez la section `@section('scripts')` dans vos vues pour ajouter du JavaScript spécifique.

## 📱 Support mobile

Les sidebars sont entièrement responsives et incluent :
- **Toggle automatique** sur mobile
- **Navigation tactile** optimisée
- **Adaptation automatique** selon la taille d'écran
- **Performance optimisée** sur tous les appareils

## 🎉 Conclusion

Ces sidebars offrent une expérience utilisateur moderne et intuitive pour chaque profil de CareWell, tout en maintenant une cohérence visuelle et fonctionnelle à travers l'application.
