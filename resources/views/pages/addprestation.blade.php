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
                                    <h2>Prestation pour le compte de :{{ $membre->sexe == 'Masculin' ? ' M. ' : ' Mme ' }}
                                        {{ Str::upper($membre->nom) . ' ' . ucfirst(trans($membre->prenom)) }}</h2>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class='user-card user-card-s2'>
                                            <div class='user-avatar-lg bg-primary d-flex justify-content-center'
                                                style="height: 150px; width: 150px">
                                                <img class='object-cover w-8 h-8 rounded-full'
                                                    src="{{ isset($membre->profile_photo_path)? asset('picture_profile/' . $membre->profile_photo_path): 'https://ui-avatars.com/api/?name=' . $membre->nom . '&background=1ee0ac&size=150&color=fff' }}"
                                                    alt='' />
                                            </div>
                                            <div class="row user-info">
                                                <div class="col-md-12">
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
                                                <div style="margin-bottom: 70px"></div>
                                                <div class="col-md-12">
                                                    <h5>Date d'hadésion à la mutuelle :
                                                        {{ date('d M Y', strtotime($membre->date_hadésion)) }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-inner">
                                                <form method="POST" id="formPrestation"
                                                    action="{{ Route::currentRouteName() === 'prestation.edit'? route('prestation.update', $prestation->id): route('prestation.store') }}">
                                                    @csrf
                                                    <div class="row g-gs">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <input type="number" value="{{ $membre->id }}" name="id"
                                                                    id="id" hidden>
                                                                <label style="font-weight: bold;" for="typePrestation">Type
                                                                    de prestation
                                                                    *</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control" id="typePrestation"
                                                                        name="typePrestation">
                                                                        @isset($type_prestation)
                                                                            <option
                                                                                value="{{ $type_prestation->id . '|' . $type_prestation->montant }}">
                                                                                {{ $type_prestation->libelle }}
                                                                            </option>
                                                                        @endisset
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <x-input name='montant'
                                                                    :value="isset($prestation) ? floatval($prestation->montant) : ''"
                                                                    input='number' :required="true" :disabled="true"
                                                                    title="Montant * (FCFA)">
                                                                </x-input>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <x-input name='date'
                                                                    :value="isset($prestation) ?date('Y-m-d', strtotime($prestation->date))  : Carbon\Carbon::now()->format('Y-m-d')"
                                                                    input='date' :required="true" title="Date *">
                                                                </x-input>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label style="font-weight: bold;" for="listAyantDroit">
                                                                    Ayants
                                                                    droit *</label>
                                                                <div class="form-control-wrap">
                                                                    <select class="form-control" id="listAyantDroit"
                                                                        name="listAyantDroit">
                                                                        @isset($ayant_droit)
                                                                            <option value="{{ $ayant_droit->id }}">
                                                                                {{ $ayant_droit->nom . ' ' . $ayant_droit->prenom . '   |   ' . $ayant_droit->statut }}
                                                                            </option>
                                                                        @endisset
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-lg btn-primary btn-submit-prestation">Sauvegarder</button>
                                                                <button type="button" onclick="clearformPrestation()"
                                                                    class="btn btn-lg btn-clear">@lang('Annuler')</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
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
    <script>
        $('.btn-submit-prestation').click(function(e) {
            e.preventDefault();

            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            var typePrestation = $("#typePrestation").val();
            var montant = $("#montant").val();
            var id = $("#id").val();
            var date = $("#date").val();
            var listAyantDroit = $("#listAyantDroit").val();

            // history.pushState({}, null, "{{ route('membre.index') }}");
            // window.setTimeout('location.reload()', 1000);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "" + $('#formPrestation').attr('action'),
                type: "" + $('#formPrestation').attr('method'),
                dataType: 'json',
                data: {
                    typePrestation: typePrestation,
                    montant: montant,
                    date: date,
                    listAyantDroit: listAyantDroit,
                    id: id,
                },
                success: function(data) {
                    if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                        //success
                        Swal.fire(data.success,
                            'Votre requête s\'est terminer avec succèss', 'success', );
                        clearformPrestation();
                        history.back();
                        window.setTimeout('location.reload()', 1500);
                        // history.pushState({}, null, "{{ route('membre.index') }}");
                        // window.setTimeout('location.reload()', 1500);
                    } else {
                        if (!$.isEmptyObject(data.error)) {
                            $('#alert-javascript').removeClass('d-none');
                            $('#alert-javascript').text(data.error);
                        } else {
                            if (!$.isEmptyObject(data.errors)) {
                                var error = "";
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

        });

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {
            loadprestation();
            loadAyantDroit();
        });
        $("#typePrestation").on("select2:select", function(e) {
            var select_val = $(e.currentTarget).val();
            var montant = select_val.split('|');
            $('#montant').val(montant[1]);
        });

        function loadprestation() {

            $("#typePrestation").select2({
                language: "fr",
                ajax: {
                    url: "{{ route('getTypePrestations') }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                }
            });
        }

        function loadAyantDroit() {

            $("#listAyantDroit").select2({
                language: "fr",
                ajax: {
                    url: "{{ route('getAyantsDroit', $membre->id) }}",
                    type: 'post',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term // search term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true,
                }
            });
        }

        function clearformPrestation() {
            // $('#formPrestation').attr('action', "{{ route('prestation.store') }}");
            $('#formPrestation').attr('method', "POST");
            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            $("#date").val("{{ Carbon\Carbon::now()->format('Y-m-d') }}");
            $("#typePrestation").focus();
            $("#montant").val('');
            $("#typePrestation").empty();
            $("#listAyantDroit").empty();
            loadprestation();
            loadAyantDroit();
            @if (Route::currentRouteName() === 'prestation.edit')
                // history.pushState({}, null, "{{ route('membre.index') }}");
                window.setTimeout('window.history.back()', 1500);
            @endif
        }
    </script>
@endsection
