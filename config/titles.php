<?php
$mytime = Carbon\Carbon::now();
return [
    'dashboard' => 'Tableaux de Bord',
    'caisse' => ['index' => 'Caisse de la mutuelle',],
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
    'dons' => [
        'index' => 'Liste des dons',
        'create' => 'Ajout d\'un don',
        'edit' => 'Modification d\'un don',
    ],
    'emprunt' => [
        'appelASouscription' => 'Formulaire de souscription à un emprunt',
        'viewForListOfEmpruntOfUUserWhoIsConnect' => 'Etat des emprunts',
        'viewListEmpruntWhoWatingTheValidationByAdmin' => 'Liste des emprunts en cours d\'étude',
        'showFormUploadLettreDeMotivation' => 'Enregistrer la lettre de souscription signée',
        'showFormWhoShowListOfEmpruntWhoIsValidateByTheMutual' => 'Liste des emprunts validés par la mutuelle',
        'showFormWhoShowListOfEmpruntWhoIsIsReturnByTheMember' => 'Liste des emprunts remboursés par les membres',
        'getViewEnregistrementManuelD1Emprunt' => 'Formulaire d\'ajout manuel d\'un emprunt',
        'getViewForListOfEmpruntWhoIsExpire' => 'Liste des emprunts expirés',
        'historiqueEmprunts' => 'Liste des emprunts remboursés classer par types'
    ],
    'messagerie' => [
        'sendMessageToMutual' => 'Envoyer un message à la mutuelle',
        'sendMessageToMember' => 'Envoyer un message à un membre',
        'getMessageMember' => 'Boite de reception du membre',
        'getMessageMutual' => 'Boite de reception de la mutuelle',
        'detailMessage' => 'Detail du message',
        'detailMessageMutual' => 'Detail du message',
    ],
];