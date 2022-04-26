<?php

return [
    'Acceuil' => [
        'name' => "Tableau de bord",
        'route' => 'dashboard',
        'icon' => 'icon ni ni-bar-chart-alt',
        'routes' => ['dashboard'],
        'role'   => 'agent',
    ],
    'Caisse' => [
        'name' => "Etat de caisse",
        'route' => 'caisse.index',
        'icon' => 'icon ni ni-dashboard-fill',
        'routes' => ['caisse.index'],
        'role'   => 'admin',
    ],
    'Catégorie' => [
        'name' => "Gestion des catégories",
        'route' => 'membre.index',
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
        'route' => 'membre.index',
        'routes' => ['typeprestation.index', 'typeprestation.create', 'typeprestation.edit', 'prestation.index', 'prestation.create', 'prestation.deit'],
        'icon' => 'icon ni ni-sign-waves-alt',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Liste des types de prestations',
                'role'  => 'admin',
                'route' => 'typeprestation.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajouter un type de prestation',
                'role'  => 'admin',
                'route' => 'typeprestation.create',
                'altRoute' => '',
            ],
            [
                'name'  => 'Toutes les prestations',
                'role'  => 'admin',
                'route' => 'prestation.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Historique mensuel',
                'role'  => 'admin',
                'route' => 'prestation.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Historique annuel',
                'role'  => 'admin',
                'route' => 'prestation.index',
                'altRoute' => '',
            ],
        ],
    ],
    'Cotisation' => [
        'name' => "Gestion des cotisations",
        'route' => 'membre.index',
        'routes' => ['membre.cotisation', 'membre.historiquecotisationannuel', 'membre.historiquecotisationmensuel', 'membre.historiquecotisationmensuelDetailMembre'],
        'icon' => 'icon ni ni-money',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Liste des membres pour cotisation',
                'role'  => 'admin',
                'route' => 'membre.cotisation',
                'altRoute' => '',
            ],
            [
                'name'  => 'Historique mensuel',
                'role'  => 'admin',
                'route' => 'membre.historiquecotisationmensuel',
                'altRoute' => '',
            ],
            [
                'name'  => 'Historique annuel',
                'role'  => 'admin',
                'route' => 'membre.historiquecotisationannuel',
                'altRoute' => '',
            ],
        ],
    ],
    'User' => [
        'name' => "Gestion des membres",
        'route' => 'membre.index',
        'routes' => ['membre.index', 'membre.create', 'ayantsdroits.create', 'ayantsdroits.edit', 'membre.info', 'membre.getMembreDecede', 'membre.getMembreRetraite', 'membre.getMembreExclus'],
        'icon' => 'icon ni ni-user-fill',
        'role'   => 'admin',
        'childrens' => [
            [
                'name'  => 'Liste des membres de la mutuelle',
                'role'  => 'admin',
                'route' => 'membre.index',
                'altRoute' => '',
            ],
            [
                'name'  => 'Ajout d\'un membre',
                'role'  => 'admin',
                'route' => 'membre.create',
                'altRoute' => '',
            ],
            [
                'name'  => 'Liste des membres décédés',
                'role'  => 'admin',
                'route' => 'membre.getMembreDecede',
                'altRoute' => '',
            ],
            [
                'name'  => 'Liste des membres en retraite',
                'role'  => 'admin',
                'route' => 'membre.getMembreRetraite',
                'altRoute' => '',
            ],
            [
                'name'  => 'Liste des membres exclus',
                'role'  => 'admin',
                'route' => 'membre.getMembreExclus',
                'altRoute' => '',
            ],
        ],
    ],
];