@extends('layouts.template')

@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css"> --}}
@endsection

@section('contenu')
    <div class="nk-content">
        <div class="nk-block">
            <div class="row g-gs">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="nk-block nk-block-lg">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        @if (session('status'))
                                            <br>
                                            <div class="alert alert-success alert-dismissible" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        <div class="alert alert-danger alert-dismissible d-none" id="alert-javascript"
                                            role="alert">
                                        </div>
                                        @if (count($errors) > 0)
                                            <br>
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12 text-center" style="margin-bottom: 30px;">
                                    <h2>Information sur le compte de {{ $membre->sexe == 'Masculin' ? ' M. ' : ' Mme ' }}
                                        {{ Str::upper($membre->nom) . ' ' . ucfirst(trans($membre->prenom)) }}</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class='user-card user-card-s2'>
                                            <div class='user-avatar-lg bg-primary d-flex justify-content-left'
                                                style="height: 150px; width: 150px">
                                                <img class='object-cover w-8 h-8 rounded-full popup-image'
                                                    src="{{ isset($membre->profile_photo_path)? asset('picture_profile/' . $membre->profile_photo_path): 'https://ui-avatars.com/api/?name=' . $membre->nom . '&background=1ee0ac&size=150&color=fff' }}"
                                                    alt='' />
                                            </div>
                                            <div class="row user-info text-left">
                                                <div class="col-md-12 text-center">
                                                    <h3> {{ $membre->nom . ' ' . $membre->prenom }}</h3>
                                                </div>
                                                <div style="margin-bottom: 70px"></div>
                                                <div class="col-md-12">
                                                    <h5>Matricule : {{ $membre->matricule }}</h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Nationalité : {{ $membre->nationalité }}</h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Agence : {{ $membre->agence }}</h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Sexe : {{ $membre->sexe }}</h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Téléphone : <a
                                                            href="tel:{{ $membre->tel }}">{{ $membre->tel }}</a></h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Email : <a
                                                            href="mailto:{{ $membre->email }}">{{ $membre->email }}</a>
                                                    </h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Categorie : {{ $category->libelle }}
                                                    </h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Date de naissance :
                                                        {{ date('d M Y', strtotime($membre->date_naissance)) }}
                                                    </h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Date de recrutement :
                                                        {{ date('d M Y', strtotime($membre->date_recrutement)) }}
                                                    </h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Date d'hadésion à la mutuelle :
                                                        {{ date('d M Y', strtotime($membre->date_hadésion)) }}</h5>
                                                </div>
                                                <div style="margin-bottom: 80px"></div>
                                                <div class="col-md-12">
                                                    <h5>Total des cotisations :
                                                        {{ $totalCotisation }} FCFA</h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Total des prestations :
                                                        {{ $totalPrestation }} FCFA</h5>
                                                </div>
                                                <div style="margin-bottom: 40px"></div>
                                                <div class="col-md-12">
                                                    <h5>Poids du membre :
                                                        {{ $poidMembre }} FCFA
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-inner">
                                                <ul class="nav nav-tabs">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab" href="#tabItem1">Liste
                                                            des cotisation</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="tabItem" data-toggle="tab"
                                                            href="#tabItem2">Liste
                                                            des prestations</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#tabItem3">Liste
                                                            des ayant droits</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane active" id="tabItem1">
                                                        <h3 class="text-center">Liste des cotisations</h3>
                                                        <div class="table-responsive">
                                                            <table class="nk-tb-list nk-tb-ulist" id="cotisationList"
                                                                data-auto-responsive="true">
                                                                <thead>
                                                                    <tr class="nk-tb-item nk-tb-head">
                                                                        <th class="nk-tb-col" hidden><span
                                                                                class="sub-text"></span></th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Date')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Montant')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Numéro de la
                                                                                séance')</span>
                                                                        </th>
                                                                        <th class="text-right nk-tb-col nk-tb-col-tools">
                                                                            <span class="sub-text">Action</span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tabItem2">
                                                        <div class="row m-md-2">
                                                            <div class="col-md-2">
                                                                <a href="{{ route('prestation.create', $membre->id) }}"
                                                                    class="btn btn-primary"><em
                                                                        class="icon ni ni-plus"></em></a>
                                                            </div>
                                                            <h3 class="text-center col-md-10">Liste des prestations</h3>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="nk-tb-list nk-tb-ulist" id="prestationList"
                                                                data-auto-responsive="true">
                                                                <thead>
                                                                    <tr class="nk-tb-item nk-tb-head">
                                                                        <th class="nk-tb-col" hidden><span
                                                                                class="sub-text"></span></th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Date')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Montant')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Type de
                                                                                prestation')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Ayant
                                                                                droit')</span>
                                                                        </th>
                                                                        <th class="text-right nk-tb-col nk-tb-col-tools">
                                                                            <span class="sub-text">Action</span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane" id="tabItem3">
                                                        <div class="row m-md-2">
                                                            <div class="col-md-2">
                                                                <a href="{{ route('ayantsdroits.create', $membre->id) }}"
                                                                    class="btn btn-primary"><em
                                                                        class="icon ni ni-plus"></em></a>
                                                            </div>
                                                            <h3 class="text-center col-md-10">Liste des ayant droits</h3>
                                                        </div>
                                                        <div class="table-responsive">
                                                            <table class="nk-tb-list nk-tb-ulist" id="ayantdroitList"
                                                                data-auto-responsive="true">
                                                                <thead>
                                                                    <tr class="nk-tb-item nk-tb-head">
                                                                        <th class="nk-tb-col" hidden><span
                                                                                class="sub-text"></span></th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Nom')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Liens de
                                                                                parenté avec le membre')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Cni')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Acte de
                                                                                naissance')</span>
                                                                        </th>
                                                                        <th class="nk-tb-col"><span
                                                                                class="sub-text">@lang('Certificat de
                                                                                vie')</span>
                                                                        </th>
                                                                        <th class="text-right nk-tb-col nk-tb-col-tools">
                                                                            <span class="sub-text">Action</span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .nk-block -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#cotisationList').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 10,
                paginate: true,
                info: true,
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json",
                    "sEmptyTable": "Aucune donnée disponible dans le tableau",
                    "sInfo": "Affichage des éléments _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sLoadingRecords": "Chargement...",
                    "sProcessing": "Traitement...",
                    "sSearch": "Rechercher :",
                    "sZeroRecords": "Aucun élément correspondant trouvé",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        }
                    }
                },
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                ajax: "{{ route('getcotisationListForUser', $membre->id) }}",
                order: [
                    [0, "desc"]
                ],
                columns: [{
                        "data": 'updated_at',
                        "name": 'updated_at',
                        "visible": false,
                        "className": 'nk-tb-col nk-tb-col-check'
                    },
                    {
                        "data": 'date',
                        "name": 'date',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'montant',
                        "name": 'montant',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'numero_seance',
                        "name": 'numero_seance',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'Actions',
                        "name": 'Actions',
                        "orderable": false,
                        "serachable": false,
                        "className": 'nk-tb-col nk-tb-col-tools'
                    },
                ]
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#prestationList').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 10,
                paginate: true,
                info: true,
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json",
                    "sEmptyTable": "Aucune donnée disponible dans le tableau",
                    "sInfo": "Affichage des éléments _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sLoadingRecords": "Chargement...",
                    "sProcessing": "Traitement...",
                    "sSearch": "Rechercher :",
                    "sZeroRecords": "Aucun élément correspondant trouvé",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        }
                    }
                },
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                ajax: "{{ route('getprestationListForUser', $membre->id) }}",
                order: [
                    [0, "desc"]
                ],
                columns: [{
                        "data": 'updated_at',
                        "name": 'updated_at',
                        "visible": false,
                        "className": 'nk-tb-col nk-tb-col-check'
                    },
                    {
                        "data": 'date',
                        "name": 'date',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'montant',
                        "name": 'montant',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'typePrestation',
                        "name": 'typePrestation',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'ayantDroit',
                        "name": 'ayantDroit',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'Actions',
                        "name": 'Actions',
                        "orderable": false,
                        "serachable": false,
                        "className": 'nk-tb-col nk-tb-col-tools'
                    },
                ]
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#ayantdroitList').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                pageLength: 10,
                paginate: true,
                info: true,
                language: {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json",
                    "sEmptyTable": "Aucune donnée disponible dans le tableau",
                    "sInfo": "Affichage des éléments _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ",",
                    "sLengthMenu": "Afficher _MENU_ éléments",
                    "sLoadingRecords": "Chargement...",
                    "sProcessing": "Traitement...",
                    "sSearch": "Rechercher :",
                    "sZeroRecords": "Aucun élément correspondant trouvé",
                    "oPaginate": {
                        "sFirst": "Premier",
                        "sLast": "Dernier",
                        "sNext": "Suivant",
                        "sPrevious": "Précédent"
                    },
                    "oAria": {
                        "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    },
                    "select": {
                        "rows": {
                            "_": "%d lignes sélectionnées",
                            "0": "Aucune ligne sélectionnée",
                            "1": "1 ligne sélectionnée"
                        }
                    }
                },
                buttons: [
                    'copy', 'excel', 'pdf'
                ],
                ajax: "{{ route('ayantsdroitsListForUser', $membre->id) }}",
                order: [
                    [0, "desc"]
                ],
                columns: [{
                        "data": 'updated_at',
                        "name": 'updated_at',
                        "visible": false,
                        "className": 'nk-tb-col nk-tb-col-check'
                    },
                    {
                        "data": 'nom',
                        "name": 'nom',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'liens',
                        "name": 'liens',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'cni',
                        "name": 'cni',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'acte_naissance',
                        "name": 'acte_naissance',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'certificat_vie',
                        "name": 'certificat_vie',
                        "className": 'nk-tb-col'
                    },
                    {
                        "data": 'Actions',
                        "name": 'Actions',
                        "orderable": false,
                        "serachable": false,
                        "className": 'nk-tb-col nk-tb-col-tools'
                    },
                ]
            });
        });

        $(document).on('click', '.delete-data-cot', function(e) {
            e.preventDefault();
            var id = $(this).attr('data_id');
            Swal.fire({
                title: 'Voulez-vous vraiment supprimer ?',
                text: "Vous êtes en train de vouloir supprimer une donnée ! Assurez-vous que c'est bien la bonne !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('membre.deletecotisation') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                                Swal.fire(
                                    'Supprimer!',
                                    data.success,
                                    'success'
                                )
                                window.setTimeout('location.reload()', 1500);
                            } else {
                                Swal.fire(
                                    'Erreur!',
                                    data.error,
                                    'error'
                                )
                            }
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");
                        },
                        error: function(data) {
                            Swal.fire('Une erreur s\'est produite.',
                                'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
                                'error');

                        }
                    });
                }
            });
        });


        $(document).on('click', '.delete-data-pres', function(e) {
            e.preventDefault();
            var id = $(this).attr('data_id');
            Swal.fire({
                title: 'Voulez-vous vraiment supprimer ?',
                text: "Vous êtes en train de vouloir supprimer une donnée ! Assurez-vous que c'est bien la bonne !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('prestation.delete') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                                Swal.fire(
                                    'Supprimer!',
                                    data.success,
                                    'success'
                                )
                                window.setTimeout('location.reload()', 1500);
                            } else {
                                Swal.fire(
                                    'Erreur!',
                                    data.error,
                                    'error'
                                )
                            }
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");
                        },
                        error: function(data) {
                            Swal.fire('Une erreur s\'est produite.',
                                'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
                                'error');

                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-data-ayant', function(e) {
            e.preventDefault();
            var id = $(this).attr('data_id');
            Swal.fire({
                title: 'Voulez-vous vraiment supprimer ?',
                text: "Vous êtes en train de vouloir supprimer une donnée ! Assurez-vous que c'est bien la bonne !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Annuler',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "{{ route('ayantsdroits.delete') }}",
                        type: "POST",
                        dataType: 'json',
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                                Swal.fire(
                                    'Supprimer!',
                                    data.success,
                                    'success'
                                )
                                window.setTimeout('location.reload()', 1500);
                            } else {
                                Swal.fire(
                                    'Erreur!',
                                    data.error,
                                    'error'
                                )
                            }
                            $("html, body").animate({
                                scrollTop: 0
                            }, "slow");
                        },
                        error: function(data) {
                            Swal.fire('Une erreur s\'est produite.',
                                'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
                                'error');

                        }
                    });
                }
            });
        });
    </script>
@endsection
