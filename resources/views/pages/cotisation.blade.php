@extends('layouts.template')

@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css"> --}}

@endsection

@section('contenu')
    @csrf
    <div class="nk-content">
        <div class="nk-block">
            <div class="row g-gs">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-md-11" style="margin-top: 80px">
                            @if (session('status'))
                                <br>
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if (count($errors) > 0)
                                <br>
                                <div class="alert alert-danger alert-dismissible" role="alert" id="alert-javascript">
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
                                <div class="nk-block nk-block-">
                                    {{-- <button class="btn btn-primary right" style="position: relative">Test</button> --}}
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="row">
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <x-input name='date1'
                                                            :value="Carbon\Carbon::now()->format('Y-m-d') " input='text'
                                                            :required="true" title="Date" :disabled="true">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='num_seance1' :value="$numero_seance" input='text'
                                                        :required="true" title="Numéro de la séance" :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='montant_seance' :value="0" input='text' :required="true"
                                                        title="Solde de la séance en cours (FCFA)" :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='montant_global' :value="$montant_global" input='text'
                                                        :required="true" title="Total global des cotisations (FCFA)"
                                                        :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-12" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <button type="button"
                                                            class="btn btn-lg btn-primary btn-submit">Sauvegarder</button>
                                                        <button type="button" onclick="clearform()"
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
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="table-responsive">
                                                <table class="nk-tb-list nk-tb-ulist" id="cotisationList"
                                                    data-auto-responsive="false">
                                                    <thead>
                                                        <tr class="nk-tb-item nk-tb-head">
                                                            <th class="nk-tb-col" hidden><span
                                                                    class="sub-text"></span></th>
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
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Telephone')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Categorie')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Montant')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Status')</span>
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
                            <div class="card">
                                <div class="nk-block nk-block-">
                                    {{-- <button class="btn btn-primary right" style="position: relative">Test</button> --}}
                                    <div class="card card-preview">
                                        <div class="card-inner">
                                            <div class="row">
                                                <div class="col-md-12" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <button type="button"
                                                            class="btn btn-lg btn-primary btn-submit">Sauvegarder</button>
                                                        <button type="button" onclick="clearform()"
                                                            class="btn btn-lg btn-clear">@lang('Annuler')</button>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <div class="form-group">
                                                        <x-input name='date2'
                                                            :value="Carbon\Carbon::now()->format('Y-m-d') " input='text'
                                                            :required="true" title="Date" :disabled="true">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='num_seance2' :value="$numero_seance" input='text'
                                                        :required="true" title="Numéro de la séance" :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='montant_seance2' :value="0" input='text' :required="true"
                                                        title="Solde de la séance en cours (FCFA)" :disabled="true">
                                                    </x-input>
                                                </div>
                                                <div class="col-md-3" style="margin-bottom: 10px">
                                                    <x-input name='montant_global2' :value="$montant_global" input='text'
                                                        :required="true" title="Total global des cotisations (FCFA)"
                                                        :disabled="true">
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

    <script>
        $('.btn-submit').click(function() {
            var table = $('#cotisationList').DataTable();
            var data = table.rows().data();
            var date = $("#date1").val();
            var num_seance = $("#num_seance1").val();
            var liste = new Array();
            data.each(function(value, index) {
                if ($("#customCheck" + value.id).prop("checked")) {
                    liste.push(value);
                }
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('membre.savecotisation') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    liste: liste,
                    date: date,
                    num_seance: num_seance,
                },
                success: function(data) {
                    if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                        //success
                        Swal.fire(data.success,
                            'Votre requête s\'est terminer avec succèss', 'success', );
                        $('#montant_global').val(data.montant);
                        $('#montant_global2').val(data.montant);
                        clearform();
                    } else {
                        var error = "";
                        data.errors.forEach(element => {
                            error = error + element + "<br>";
                        });
                        Swal.fire('errors',
                            error, 'error', );
                        if (!$.isEmptyObject(data.error)) {
                            $('#alert-javascript').removeClass('d-none');
                            $('#alert-javascript').text(data.error);
                        } else {
                            if (!$.isEmptyObject(data.errors)) {
                                data.errors.forEach(element => {
                                    error = error + element + "<br>";
                                });
                                $('#alert-javascript').removeClass('d-none');
                                $('#alert-javascript').append(error);
                            }
                        }
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
            $("#customCheckAll").prop("checked", false);
        });

        // $('#date1').on("input", function() {
        // });

        function clearform() {
            var table = $('#cotisationList').DataTable();
            table.ajax.reload();
            $("#customCheckAll").prop("checked", false);
            $('#montant_seance').val('0');
            $('#montant_seance2').val('0');
        }

        $('#customCheckAll').click(function(e) {
            var table = $('#cotisationList').DataTable();
            var data = table.rows().data();
            if ($("#customCheckAll").prop("checked")) {
                var montant_en_cour = 0;
                data.each(function(value, index) {
                    $("#customCheck" + value.id).prop("checked", true);
                    if ({{ Carbon\Carbon::now()->format('m') }} == '03' ||
                        {{ Carbon\Carbon::now()->format('m') }} == '12') {
                        montant_en_cour += value.montant * 2;
                    } else {
                        montant_en_cour += value.montant;
                    }
                });
                $('#montant_seance').val(montant_en_cour);
                $('#montant_seance2').val(montant_en_cour);
            } else {
                data.each(function(value, index) {
                    $("#customCheck" + value.id).prop("checked", false);
                });
                $('#montant_seance').val('0');
                $('#montant_seance2').val('0');
            }
        });

        function setCheckBox() {
            var test = false;
            var table = $('#cotisationList').DataTable();
            var data = table.rows().data();
            var montant_en_cour = 0;
            data.each(function(value, index) {
                if ($("#customCheck" + value.id).prop("checked") == false) {
                    $("#customCheckAll").prop("checked", false);
                    test = true;
                } else {
                    if ({{ Carbon\Carbon::now()->format('m') }} == '03' ||
                        {{ Carbon\Carbon::now()->format('m') }} == '12') {
                        montant_en_cour += value.montant * 2;
                    } else {
                        montant_en_cour += value.montant;
                    }
                }

            });
            $('#montant_seance').val(montant_en_cour);
            $('#montant_seance2').val(montant_en_cour);
            if (test == false) {
                $("#customCheckAll").prop("checked", true);
            }
            return false;
        }
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
                ajax: "{{ route('getUserCotisation', Carbon\Carbon::now()->format('Y-m-d')) }}",
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
                        "data": 'montant',
                        "name": 'montant',
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
                    {
                        "data": 'montant',
                        "name": 'montant',
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

@endsection
