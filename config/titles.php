<?php
$mytime = Carbon\Carbon::now();
return [
    'dashboard' => 'Acceuil',
    'membre' => [
        'info' => 'Information sur les membres',
        'index' => 'Liste des membres',
        'create' => 'Ajout d\'un nouveau membre',
        'edit' => 'Modification d\'un membre',
        'cotisation' => 'Cotisation de ' . $mytime->format('M Y'),
    ],
    'categories' => [
        'index' => 'Liste des catégories',
        'create' => 'Ajout d\'une nouvelle catégorie',
        'edit' => 'Modification d\'une catégorie',
    ],
    'ayantsdroits' => [
        'index' => 'Liste des ayants droits',
        'create' => 'Ajout d\'un ayant droit',
        'edit' => 'Modification d\'un ayant droit',
    ],
    'typeprestation' => [
        'index' => 'Liste des types de prestations',
        'create' => 'Ajout d\'un type de prestation',
        'edit' => 'Modification d\'un type de prestation',
    ],
    'prestation' => [
        'index' => 'Liste des prestations',
        'create' => 'Ajout d\'une prestation',
        'edit' => 'Modification d\'une prestation',
    ],
];