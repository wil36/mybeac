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
                        <div class="col-md-11">
                            @if (session('status'))
                                <br>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
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
                            <div class="flex-row-reverse d-flex justify-content-center loader">

                                <div class="spinner-grow text-success loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="spinner-grow text-success loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="spinner-grow text-success loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="spinner-grow text-success loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="spinner-grow text-success loader" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <div class="card">
                                <div class="nk-block nk-block-lg">
                                    {{-- <button class="btn btn-primary right" style="position: relative">Test</button> --}}
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="row">
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <x-input name='dateHadhésion'
                                                            :value="Carbon\Carbon::now()->format('Y-m-d') " input='date'
                                                            :required="true" title="Date">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='dateHadhésion' :value="50" input='text' :required="true"
                                                        title="Numéro de la séance" :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='dateHadhésion' :value="50000000000" input='text'
                                                        :required="true" title="Solde de la séance en cours"
                                                        :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='dateHadhésion' :value="50000000000" input='text'
                                                        :required="true" title="Total global des cotisations"
                                                        :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-12" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary btn-submit-ayantsdroits">Sauvegarder</button>
                                                        <button type="button" onclick="clearformayantsdroits()"
                                                            class="btn btn-lg btn-clear">@lang('Annuler')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="nk-block nk-block-lg">
                                    {{-- <button class="btn btn-primary right" style="position: relative">Test</button> --}}
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="table-responsive">
                                                <table class="nk-tb-list nk-tb-ulist" id="cotisationList"
                                                    data-auto-responsive="true">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
                                                            <th class="nk-tb-col" hidden><span
                                                                    class="sub-text"></span></th>
                                                            <th class="nk-tb-col"><span class="sub-text"></span>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input"
                                                                        id="customCheckAll">
                                                                    <label class="custom-control-label"
                                                                        for="customCheckAll"></label>
                                                                </div>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Matricule')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Nom')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Prenom')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Nationalité')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Agence')</span>
                                                            </th>
                                                            {{-- <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Email')</span>
                                                            </th> --}}
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Telephone')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Categorie')</span>
                                                            </th>
                                                            {{-- <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Date de naissance')</span>
                                                            </th> --}}
                                                            {{-- <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Date de
                                                                    recrutement')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Date d\'hadésion')</span>
                                                            </th> --}}
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Status')</span>
                                                            </th>
                                                            {{-- <th class="nk-tb-col"><span class="sub-text">Status</span>
                                                        </th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="nk-block nk-block-lg">
                                    {{-- <button class="btn btn-primary right" style="position: relative">Test</button> --}}
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="row">
                                                <div class="col-md-12" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary btn-submit-ayantsdroits">Sauvegarder</button>
                                                        <button type="button" onclick="clearformayantsdroits()"
                                                            class="btn btn-lg btn-clear">@lang('Annuler')</button>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <x-input name='dateHadhésion'
                                                            :value="Carbon\Carbon::now()->format('Y-m-d') " input='date'
                                                            :required="true" title="Date">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='dateHadhésion' :value="50" input='text' :required="true"
                                                        title="Numéro de la séance">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='dateHadhésion' :value="50000000000" input='text'
                                                        :required="true" title="Solde de la séance en cours">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='dateHadhésion' :value="50000000000" input='text'
                                                        :required="true" title="Total global des cotisations">
                                                    </x-input>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    @if (config('app.locale') == 'fr')
        <script>
            $(document).ready(function() {
                $('#cotisationList').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    pageLength: 10,
                    paginate: false,
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
                    ajax: "{{ route('getUserCotisation') }}",
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
                            "data": 'select',
                            "name": 'select',
                            "orderable": false,
                            "serachable": false,
                            "className": 'nk-tb-col nk-tb-col-check'
                        },
                        {
                            "data": 'matricule',
                            "name": 'matricule',
                            "className": 'nk-tb-col '
                        },
                        {
                            "data": 'nom',
                            "name": 'nom',
                            "className": 'nk-tb-col '
                        },
                        {
                            "data": 'prenom',
                            "name": 'prenom',
                            "className": 'nk-tb-col '
                        },
                        {
                            "data": 'nationalité',
                            "name": 'nationalité',
                            "className": 'nk-tb-col '
                        },
                        {
                            "data": 'agence',
                            "name": 'agence',
                            "className": 'nk-tb-col '
                        },
                        // {
                        //     "data": 'email',
                        //     "name": 'email',
                        //     "className": 'nk-tb-col '
                        // },
                        {
                            "data": 'tel',
                            "name": 'tel',
                            "className": 'nk-tb-col'
                        },
                        {
                            "data": 'category',
                            "name": 'category',
                            "className": 'nk-tb-col'
                        },
                        // {
                        //     "data": 'dateNais',
                        //     "name": 'dateNais',
                        //     "className": 'nk-tb-col'
                        // },
                        // {
                        //     "data": 'dateRecru',
                        //     "name": 'dateRecru',
                        //     "className": 'nk-tb-col'
                        // },
                        // {
                        //     "data": 'dateHade',
                        //     "name": 'dateHade',
                        //     "className": 'nk-tb-col'
                        // },
                        {
                            "data": 'status',
                            "name": 'status',
                            "className": 'nk-tb-col'
                        },
                    ]
                });
            });
            $(".loader").addClass("d-none");
        </script>
    @else
        <script>
            $(document).ready(function() {
                $('#cotisationList').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    pageLength: 10,
                    paginate: false,
                    info: true,
                    language: {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json",
                    },
                    buttons: [
                        'copy', 'excel', 'pdf'
                    ],
                    // scrollX: true,
                    // "order": [[ 0, "desc" ]],
                    ajax: "{{ route('getUserCotisation') }}",
                    order: [
                        [0, "desc"]
                    ], //or asc 
                    columns: [
                        // {
                        //     "data": 'id',
                        //     "name": 'id',
                        //     "className": 'nk-tb-col nk-tb-col-check'
                        // },
                        {
                            "data": 'updated_at',
                            "name": 'updated_at',
                            "visible": false,
                            "className": 'nk-tb-col nk-tb-col-check'
                        },
                        {
                            "data": 'select',
                            "name": 'select',
                            "orderable": false,
                            "serachable": false,
                            "className": 'nk-tb-col nk-tb-col-check'
                        },
                        {
                            "data": 'name',
                            "name": 'name',
                            "className": 'nk-tb-col '
                        },
                        {
                            "data": 'email',
                            "name": 'email',
                            "className": 'nk-tb-col'
                        },
                        {
                            "data": 'role',
                            "name": 'role',
                            "className": 'nk-tb-col'
                        },
                        {
                            "data": 'status',
                            "name": 'status',
                            "className": 'nk-tb-col'
                        },
                    ]
                });
            });
            $(".loader").addClass("d-none");
        </script>
    @endif
    <script>
        $('#customCheckAll').click(function(e) {
            var table = $('#cotisationList').DataTable();
            var data = table.rows().data();
            if ($("#customCheckAll").prop("checked")) {
                console.log(1);
                data.each(function(value, index) {
                    $("#customCheck" + value.id).prop("checked", true);
                });
            } else {
                data.each(function(value, index) {
                    $("#customCheck" + value.id).prop("checked", false);
                });
            }
        });

        function setCheckBox() {
            var test = false;
            var table = $('#cotisationList').DataTable();
            var data = table.rows().data();
            data.each(function(value, index) {
                if ($("#customCheck" + value.id).prop("checked") == false) {
                    $("#customCheckAll").prop("checked", false);
                    test = true;
                    return false;
                }
            });
            if (test == false) {
                $("#customCheckAll").prop("checked", true);
            }
            return false;
        }
    </script>
@endsection
