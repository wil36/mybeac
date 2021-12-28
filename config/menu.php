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
    'Prestation' => [
        'name' => "Gestion des prestations",
        'route' => 'users.index',
        'routes' => '',
        'icon' => 'icon ni ni-sign-waves-alt',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Ajouter d\'un type de prestation',
                'role'  => 'admin',
                'route' => 'users.create',
                'altRoute' => '',
            ],
            [
                'name'  => 'Tout les prestations',
                'role'  => 'admin',
                'route' => 'users.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajouter d\'une prestation',
                'role'  => 'admin',
                'route' => 'users.create',
                'altRoute' => '',
            ],
        ],
    ],
    'User' => [
        'name' => "Gestion des membres",
        'route' => 'users.index',
        'routes' => ['users.index', 'users.create', 'ayantsdroits.create', 'ayantsdroits.edit'],
        'icon' => 'icon ni ni-user-fill',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Tout les membres',
                'role'  => 'admin',
                'route' => 'users.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajout d\'un membre',
                'role'  => 'admin',
                'route' => 'users.create',
                'altRoute' => '',
            ],
        ],
    ],
];