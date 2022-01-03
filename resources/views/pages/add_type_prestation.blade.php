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
                                <div class="card">
                                    <div class="card-inner">
                                        <form method="POST" id="formTypeprestation"
                                            action="{{ Route::currentRouteName() === 'typeprestation.edit' ? route('typeprestation.update', $typeprestation->id) : route('typeprestation.store') }}">
                                            @csrf
                                            <div class="row g-gs">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <x-input name='libelle'
                                                            :value="isset($typeprestation) ? $typeprestation->libelle : ''"
                                                            input='text' :required="true" title="Nom de la prestation *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <x-input name='montant'
                                                            :value="isset($typeprestation) ? floatval($typeprestation->montant) : ''"
                                                            input='number' :required="true" title="Montant * (FCFA)">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary btn-submit-Typeprestation">Sauvegarder</button>
                                                        <button type="button" onclick="clearformTypeprestation()"
                                                            class="btn btn-lg btn-clear">@lang('Annuler')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
        $('.btn-submit-Typeprestation').click(function(e) {
            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            e.preventDefault();
            var libelle = $("#libelle").val();
            var montant = $("#montant").val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "" + $('#formTypeprestation').attr('action'),
                type: "" + $('#formTypeprestation').attr('method'),
                dataType: 'json',
                data: {
                    libelle: libelle,
                    montant: montant,
                },
                success: function(data) {
                    if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                        //success
                        Swal.fire(data.success,
                            'Votre requête s\'est terminer avec succèss', 'success', );
                        clearformTypeprestation();
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

        function clearformTypeprestation() {
            history.pushState({}, null, "{{ route('typeprestation.create') }}");
            $('#formTypeprestation').attr('action', "{{ route('typeprestation.store') }}");
            $('#formTypeprestation').attr('method', "POST");
            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            $("#libelle").val('');
            $("#libelle").focus();
            $("#montant").val('');
        }
    </script>
@endsection
