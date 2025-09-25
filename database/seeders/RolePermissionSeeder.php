<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            // Permissions pour les utilisateurs
            'users.view' => ['display_name' => 'Voir les utilisateurs', 'group' => 'Utilisateurs', 'description' => 'Permet de voir la liste des utilisateurs'],
            'users.create' => ['display_name' => 'Créer des utilisateurs', 'group' => 'Utilisateurs', 'description' => 'Permet de créer de nouveaux utilisateurs'],
            'users.edit' => ['display_name' => 'Modifier les utilisateurs', 'group' => 'Utilisateurs', 'description' => 'Permet de modifier les informations des utilisateurs'],
            'users.delete' => ['display_name' => 'Supprimer les utilisateurs', 'group' => 'Utilisateurs', 'description' => 'Permet de supprimer des utilisateurs'],
            'users.roles' => ['display_name' => 'Gérer les rôles des utilisateurs', 'group' => 'Utilisateurs', 'description' => 'Permet de changer les rôles des utilisateurs'],

            // Permissions pour les articles
            'articles.view' => ['display_name' => 'Voir les articles', 'group' => 'Articles', 'description' => 'Permet de voir la liste des articles'],
            'articles.create' => ['display_name' => 'Créer des articles', 'group' => 'Articles', 'description' => 'Permet de créer de nouveaux articles'],
            'articles.edit' => ['display_name' => 'Modifier les articles', 'group' => 'Articles', 'description' => 'Permet de modifier les articles existants'],
            'articles.delete' => ['display_name' => 'Supprimer les articles', 'group' => 'Articles', 'description' => 'Permet de supprimer des articles'],
            'articles.publish' => ['display_name' => 'Publier des articles', 'group' => 'Articles', 'description' => 'Permet de publier des articles'],

            // Permissions pour les services
            'services.view' => ['display_name' => 'Voir les services', 'group' => 'Services', 'description' => 'Permet de voir la liste des services'],
            'services.create' => ['display_name' => 'Créer des services', 'group' => 'Services', 'description' => 'Permet de créer de nouveaux services'],
            'services.edit' => ['display_name' => 'Modifier les services', 'group' => 'Services', 'description' => 'Permet de modifier les services existants'],
            'services.delete' => ['display_name' => 'Supprimer les services', 'group' => 'Services', 'description' => 'Permet de supprimer des services'],

            // Permissions pour les rendez-vous
            'appointments.view' => ['display_name' => 'Voir les rendez-vous', 'group' => 'Rendez-vous', 'description' => 'Permet de voir la liste des rendez-vous'],
            'appointments.create' => ['display_name' => 'Créer des rendez-vous', 'group' => 'Rendez-vous', 'description' => 'Permet de créer de nouveaux rendez-vous'],
            'appointments.edit' => ['display_name' => 'Modifier les rendez-vous', 'group' => 'Rendez-vous', 'description' => 'Permet de modifier les rendez-vous existants'],
            'appointments.delete' => ['display_name' => 'Supprimer les rendez-vous', 'group' => 'Rendez-vous', 'description' => 'Permet de supprimer des rendez-vous'],
            'appointments.confirm' => ['display_name' => 'Confirmer les rendez-vous', 'group' => 'Rendez-vous', 'description' => 'Permet de confirmer les rendez-vous'],

            // Permissions pour les dossiers médicaux
            'medical-files.view' => ['display_name' => 'Voir les dossiers médicaux', 'group' => 'Dossiers médicaux', 'description' => 'Permet de voir les dossiers médicaux'],
            'medical-files.create' => ['display_name' => 'Créer des dossiers médicaux', 'group' => 'Dossiers médicaux', 'description' => 'Permet de créer de nouveaux dossiers médicaux'],
            'medical-files.edit' => ['display_name' => 'Modifier les dossiers médicaux', 'group' => 'Dossiers médicaux', 'description' => 'Permet de modifier les dossiers médicaux'],
            'medical-files.delete' => ['display_name' => 'Supprimer les dossiers médicaux', 'group' => 'Dossiers médicaux', 'description' => 'Permet de supprimer des dossiers médicaux'],

            // Permissions pour les prescriptions
            'prescriptions.view' => ['display_name' => 'Voir les prescriptions', 'group' => 'Prescriptions', 'description' => 'Permet de voir les prescriptions'],
            'prescriptions.create' => ['display_name' => 'Créer des prescriptions', 'group' => 'Prescriptions', 'description' => 'Permet de créer de nouvelles prescriptions'],
            'prescriptions.edit' => ['display_name' => 'Modifier les prescriptions', 'group' => 'Prescriptions', 'description' => 'Permet de modifier les prescriptions'],
            'prescriptions.delete' => ['display_name' => 'Supprimer les prescriptions', 'group' => 'Prescriptions', 'description' => 'Permet de supprimer des prescriptions'],

            // Permissions pour les examens
            'exams.view' => ['display_name' => 'Voir les examens', 'group' => 'Examens', 'description' => 'Permet de voir les examens'],
            'exams.create' => ['display_name' => 'Créer des examens', 'group' => 'Examens', 'description' => 'Permet de créer de nouveaux examens'],
            'exams.edit' => ['display_name' => 'Modifier les examens', 'group' => 'Examens', 'description' => 'Permet de modifier les examens'],
            'exams.delete' => ['display_name' => 'Supprimer les examens', 'group' => 'Examens', 'description' => 'Permet de supprimer des examens'],

            // Permissions système
            'system.dashboard' => ['display_name' => 'Accès au tableau de bord', 'group' => 'Système', 'description' => 'Permet d\'accéder au tableau de bord'],
            'system.settings' => ['display_name' => 'Gérer les paramètres', 'group' => 'Système', 'description' => 'Permet de gérer les paramètres du système'],
            'system.logs' => ['display_name' => 'Voir les logs', 'group' => 'Système', 'description' => 'Permet de voir les logs du système'],
            'system.backup' => ['display_name' => 'Gérer les sauvegardes', 'group' => 'Système', 'description' => 'Permet de gérer les sauvegardes'],
            'roles.view' => ['display_name' => 'Voir les rôles', 'group' => 'Système', 'description' => 'Permet de voir la liste des rôles'],
            'roles.create' => ['display_name' => 'Créer des rôles', 'group' => 'Système', 'description' => 'Permet de créer de nouveaux rôles'],
            'roles.edit' => ['display_name' => 'Modifier les rôles', 'group' => 'Système', 'description' => 'Permet de modifier les rôles existants'],
            'roles.delete' => ['display_name' => 'Supprimer les rôles', 'group' => 'Système', 'description' => 'Permet de supprimer des rôles'],
            'permissions.view' => ['display_name' => 'Voir les permissions', 'group' => 'Système', 'description' => 'Permet de voir la liste des permissions'],
            'permissions.create' => ['display_name' => 'Créer des permissions', 'group' => 'Système', 'description' => 'Permet de créer de nouvelles permissions'],
            'permissions.edit' => ['display_name' => 'Modifier les permissions', 'group' => 'Système', 'description' => 'Permet de modifier les permissions existantes'],
            'permissions.delete' => ['display_name' => 'Supprimer les permissions', 'group' => 'Système', 'description' => 'Permet de supprimer des permissions'],

            // Permissions pour les rapports
            'reports.view' => ['display_name' => 'Voir les rapports', 'group' => 'Rapports', 'description' => 'Permet de voir les rapports'],
            'reports.create' => ['display_name' => 'Créer des rapports', 'group' => 'Rapports', 'description' => 'Permet de créer de nouveaux rapports'],
            'reports.export' => ['display_name' => 'Exporter les rapports', 'group' => 'Rapports', 'description' => 'Permet d\'exporter les rapports'],

            // Permissions pour la facturation
            'billing.view' => ['display_name' => 'Voir la facturation', 'group' => 'Facturation', 'description' => 'Permet de voir les informations de facturation'],
            'billing.create' => ['display_name' => 'Créer des factures', 'group' => 'Facturation', 'description' => 'Permet de créer de nouvelles factures'],
            'billing.edit' => ['display_name' => 'Modifier les factures', 'group' => 'Facturation', 'description' => 'Permet de modifier les factures existantes'],
            'billing.delete' => ['display_name' => 'Supprimer les factures', 'group' => 'Facturation', 'description' => 'Permet de supprimer des factures'],
            'billing.payments' => ['display_name' => 'Gérer les paiements', 'group' => 'Facturation', 'description' => 'Permet de gérer les paiements'],
        ];

        foreach ($permissions as $name => $data) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                array_merge($data, ['guard_name' => 'web'])
            );
        }

        // Créer les rôles
        $roles = [
            'Admin' => [
                'display_name' => 'Administrateur',
                'description' => 'Accès complet à toutes les fonctionnalités du système',
                'permissions' => array_keys($permissions) // Toutes les permissions
            ],
            'Doctor' => [
                'display_name' => 'Médecin',
                'description' => 'Accès aux fonctionnalités médicales et patients',
                'permissions' => [
                    'users.view',
                    'articles.view',
                    'services.view',
                    'appointments.view',
                    'appointments.create',
                    'appointments.edit',
                    'appointments.confirm',
                    'medical-files.view',
                    'medical-files.create',
                    'medical-files.edit',
                    'prescriptions.view',
                    'prescriptions.create',
                    'prescriptions.edit',
                    'exams.view',
                    'exams.create',
                    'exams.edit',
                    'system.dashboard',
                    'reports.view',
                ]
            ],
            'Nurse' => [
                'display_name' => 'Infirmière',
                'description' => 'Soins aux patients et assistance médicale',
                'permissions' => [
                    'users.view',
                    'articles.view',
                    'services.view',
                    'appointments.view',
                    'appointments.create',
                    'appointments.edit',
                    'appointments.confirm',
                    'medical-files.view',
                    'medical-files.edit',
                    'prescriptions.view',
                    'prescriptions.edit',
                    'exams.view',
                    'exams.create',
                    'exams.edit',
                    'system.dashboard',
                    'reports.view',
                ]
            ],
            'Secretary' => [
                'display_name' => 'Secrétaire',
                'description' => 'Gestion des rendez-vous et accueil des patients',
                'permissions' => [
                    'users.view',
                    'users.create',
                    'appointments.view',
                    'appointments.create',
                    'appointments.edit',
                    'appointments.confirm',
                    'system.dashboard',
                ]
            ],
            'Accountant' => [
                'display_name' => 'Comptable',
                'description' => 'Gestion financière et facturation',
                'permissions' => [
                    'users.view',
                    'appointments.view',
                    'medical-files.view',
                    'prescriptions.view',
                    'exams.view',
                    'system.dashboard',
                    'reports.view',
                    'reports.create',
                    'reports.export',
                    'billing.view',
                    'billing.create',
                    'billing.edit',
                    'billing.delete',
                    'billing.payments',
                ]
            ],
            'Patient' => [
                'display_name' => 'Patient',
                'description' => 'Accès limité aux informations personnelles et rendez-vous',
                'permissions' => [
                    'articles.view',
                    'services.view',
                    'appointments.view',
                    'appointments.create',
                    'medical-files.view',
                    'prescriptions.view',
                    'exams.view',
                    'system.dashboard',
                ]
            ]
        ];

        foreach ($roles as $name => $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                [
                    'display_name' => $roleData['display_name'],
                    'description' => $roleData['description'],
                    'guard_name' => 'web'
                ]
            );

            // Assigner les permissions au rôle
            $permissionModels = Permission::whereIn('name', $roleData['permissions'])
                ->where('guard_name', 'web')
                ->get();
            $role->syncPermissions($permissionModels);
        }

        // Assigner le rôle Admin au premier utilisateur s'il existe
        $firstUser = User::first();
        if ($firstUser && !$firstUser->hasRole('Admin')) {
            $firstUser->assignRole('Admin');
        }
    }
}