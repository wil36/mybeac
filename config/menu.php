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
        'name' => "Gestion des catégories",
        'route' => 'users.index',
        'routes' => ['categories.create', 'categories.index', 'categories.edit'],
        'icon' => 'icon ni ni-layout-alt-fill',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Liste des Catégories',
                'role'  => 'admin',
                'route' => 'categories.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajouter une Catégorie',
                'role'  => 'admin',
                'route' => 'categories.create',
                'altRoute' => '',
            ],
        ],
    ],
    'Prestation' => [
        'name' => "Gestion des prestations",
        'route' => 'users.index',
        'routes' => ['typeprestation.index', 'typeprestation.create', 'typeprestation.edit'],
        'icon' => 'icon ni ni-sign-waves-alt',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Liste des types de prestation',
                'role'  => 'admin',
                'route' => 'typeprestation.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajouter d\'un type de prestation',
                'role'  => 'admin',
                'route' => 'typeprestation.create',
                'altRoute' => '',
            ],
            [
                'name'  => 'Tout les prestations',
                'role'  => 'admin',
                'route' => 'typeprestation.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajouter d\'une prestation',
                'role'  => 'admin',
                'route' => 'typeprestation.create',
                'altRoute' => '',
            ],
        ],
    ],
    'Cotisation' => [
        'name' => "Gestion des cotisations",
        'route' => 'users.index',
        'routes' => ['users.cotisation'],
        'icon' => 'icon ni ni-sign-dollar',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Liste des membres',
                'role'  => 'admin',
                'route' => 'users.cotisation',
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