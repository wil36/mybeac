<?php

return [
    'Acceuil' => [
        'name' => "Tableau de bord",
        'route' => 'dashboard',
        'icon' => 'icon ni ni-dashboard-fill',
        'routes' => ['dashboard'],
        'role'   => 'admin',
    ],
    'Catégorie' => [
        'name' => "Gestion des Catégories",
        'route' => 'users.index',
        'routes' => ['categories.create', 'categories.index', 'categories.edit'],
        'icon' => 'icon ni ni-layout-alt-fill',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Liste des Catégories',
                'role'  => 'agent',
                'route' => 'categories.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajouter une Catégorie',
                'role'  => 'agent',
                'route' => 'categories.create',
                'altRoute' => '',
            ],
        ],
    ],
    'Agent' => [
        'name' => "Gestion des agents",
        'route' => 'users.index',
        'routes' => '',
        'icon' => 'icon ni ni-users-fill',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Tout les agents',
                'role'  => 'admin',
                'route' => 'users.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajouter un agent',
                'role'  => 'admin',
                'route' => 'users.create',
                'altRoute' => '',
            ],
        ],
    ],
    'User' => [
        'name' => "Gestion des utilisateurs",
        'route' => 'users.index',
        'routes' => ['users.index', 'users.create'],
        'icon' => 'icon ni ni-user-fill',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Tout les utilisateurs',
                'role'  => 'admin',
                'route' => 'users.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Nouvel utilisateur',
                'role'  => 'admin',
                'route' => 'users.create',
                'altRoute' => '',
            ],
        ],
    ],
];