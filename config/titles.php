<?php
$mytime = Carbon\Carbon::now();
return [
    'dashboard' => 'Tableau de Bord',
    'caisse' => ['index' => 'Etat de caisse de la mutuelle',],
    'membre' => [
        'info' => 'Information sur les membres',
        'index' => 'Liste des membres',
        'create' => 'Ajout d\'un nouveau membre',
        'edit' => 'Modification d\'un membre',
        'getMembreDecede' => 'Liste des membres décédés',
        'getMembreRetraite' => 'Liste des membres retraités',
        'getMembreExclus' => 'Liste des membres exclus',
        'cotisation' => 'Cotisation de ' . $mytime->format('M Y'),
        'historiquecotisationannuel' => 'Historiques annuels des cotisations',
        'historiquecotisationmensuel' => 'Historiques mensuels des cotisations',
        'historiquecotisationmensuelDetailMembre' => 'Liste des membres de la cotisation',
        'historiqueprestationannuel' => 'Historiques annuels des prestations',
        'historiqueprestationmensuel' => 'Historiques mensuels des prestations',
        'historiqueprestationmensuelDetailMembre' => 'Liste des membres de la prestations',
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