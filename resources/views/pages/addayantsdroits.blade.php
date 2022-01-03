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
                                {{-- @dd($ayantsdroits) --}}
                                <div class="card">
                                    <div class="card-inner">
                                        <form method="POST" id="formayantsdroits"
                                            action="{{ Route::currentRouteName() === 'ayantsdroits.edit' ? route('ayantsdroits.update', $ayantsdroits->id) : route('ayantsdroits.store') }}">
                                            @csrf
                                            <div class="row g-gs">
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-inner">
                                                            <div class="card-head">
                                                                <h4 class="card-title" style="margin-bottom: 20px;">
                                                                    Informations
                                                                    sur le membre</h4>
                                                            </div>
                                                            <div class="row g-gs">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <x-input name='father_matricule'
                                                                            :value="isset($father) ? $father->matricule : ''"
                                                                            input='text' :required="true"
                                                                            title="Matricule *" :disabled="true">
                                                                        </x-input>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <x-input name='father_nom'
                                                                            :value="isset($father) ? $father->nom : ''"
                                                                            input='text' :required="true" title="Nom *"
                                                                            :disabled="true">
                                                                        </x-input>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <x-input name='father_prenom'
                                                                            :value="isset($father) ? $father->prenom : ''"
                                                                            input='text' :required="true" title="Prénom *"
                                                                            :disabled="true">
                                                                        </x-input>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <x-input name='father_email'
                                                                            :value="isset($father) ? $father->email : ''"
                                                                            input='text' :required="true" title="Prénom *"
                                                                            :disabled="true">
                                                                        </x-input>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- card -->
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="card">
                                                        <div class="card-inner">
                                                            <div class="card-head">
                                                                <h4 class="card-title" style="margin-bottom: 20px;">
                                                                    Informations
                                                                    sur l'ayant droit</h4>
                                                            </div>
                                                            <div class="row g-gs">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <input type="text" hidden name="id" id="id"
                                                                            value="{{ isset($id) ? $id : '' }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <x-input name='nom'
                                                                            :value="isset($ayantsdroits) ? $ayantsdroits->code : ''"
                                                                            input='text' :required="true" title="Nom *">
                                                                        </x-input>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <x-input name='prenom'
                                                                            :value="isset($ayantsdroits) ? $ayantsdroits->libelle : ''"
                                                                            input='text' :required="true" title="Prénom *">
                                                                        </x-input>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <x-input name='statut'
                                                                            :value="isset($user) ? $user->role=='admin'?'Administrateur':'Agent' : ''"
                                                                            input='select'
                                                                            :options='[["name"=>"","value"=>""],["name"=>"Conjoint","value"=>"Conjoint"],["name"=>"Enfant","value"=>"Enfant"],["name"=>"Père","value"=>"Père"],["name"=>"Mère","value"=>"Mère"], ["name"=>"Tuteur","value"=>"Tuteur"],["name"=>"Tatrice","value"=>"Tatrice"],["name"=>"Bénéficiaire","value"=>"Bénéficiaire"],]'
                                                                            :required="true" title="Statut *">
                                                                        </x-input>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label style="font-weight: bold;" for="cni">Copie de
                                                                            CNI</label>
                                                                        <div class="form-control-wrap">
                                                                            <div class="custom-file">
                                                                                <input type="file" name="cni" id="cni"
                                                                                    class="custom-file-input">
                                                                                <label class="custom-file-label"
                                                                                    id="lab_cni" for="cni">Choisir
                                                                                    un
                                                                                    fichier</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label style="font-weight: bold;"
                                                                            for="acte_naissance">Copie d'acte
                                                                            de
                                                                            naissance</label>
                                                                        <div class="form-control-wrap">
                                                                            <div class="custom-file">
                                                                                <input type="file" name="acte_naissance"
                                                                                    id="acte_naissance"
                                                                                    class="custom-file-input">
                                                                                <label class="custom-file-label"
                                                                                    id="lab_acte_naissance"
                                                                                    for="acte_naissance">Choisir
                                                                                    un
                                                                                    fichier</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label style="font-weight: bold;"
                                                                            for="certificat_vie">Copie de
                                                                            certificat de vie</label>
                                                                        <div class="form-control-wrap">
                                                                            <div class="custom-file">
                                                                                <input type="file" name="certificat_vie"
                                                                                    id="certificat_vie"
                                                                                    class="custom-file-input">
                                                                                <label class="custom-file-label"
                                                                                    id="lab_certificat_vie"
                                                                                    for="certificat_vie">Choisir
                                                                                    un
                                                                                    fichier</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div><!-- card -->
                                                </div>


                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary btn-submit-ayantsdroits">Sauvegarder</button>
                                                        <button type="button" onclick="clearformayantsdroits()"
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
        $('.btn-submit-ayantsdroits').click(function(e) {
            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            e.preventDefault();
            let id = $("#id").val();
            let nom = $("#nom").val();
            let prenom = $("#prenom").val();
            let statut = $("#statut").val();
            let cni = $("#cni");
            console.log(cni);
            var myForm = document.getElementById('formayantsdroits');
            var formData = new FormData(myForm);
            // formData.append('cni', input.files[0]);
            console.log(formData[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "" + $('#formayantsdroits').attr('action'),
                type: "" + $('#formayantsdroits').attr('method'),
                dataType: 'json',
                data: formData,
                // data: {
                //     id: id,
                //     nom: nom,
                //     prenom: prenom,
                //     statut: statut,
                //     cni: cni,
                // },
                success: function(data) {
                    if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                        //success
                        Swal.fire(data.success,
                            'Votre requête s\'est terminer avec succèss', 'success', );
                        clearformayantsdroits();
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

        function clearformayantsdroits() {
            history.pushState({}, null, "{{ route('ayantsdroits.create', $id) }}");
            $('#formayantsdroits').attr('action', "{{ route('ayantsdroits.store') }}");
            $('#formayantsdroits').attr('method', "POST");
            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            $("#nom").focus();
            $("#nom").val('');
            $("#prenom").val('');
            $("#statut").val('');
            $('#statut').empty();
            $('#statut').append(
                '<option value=""></option>',
                '<option value="Conjoint">Conjoint</option>',
                '<option value="Enfant">Enfant</option>',
                '<option value="Père">Père</option>',
                '<option value="Mère">Mère</option>',
                '<option value="Tuteur">Tuteur</option>',
                '<option value="Tatrice">Tatrice</option>',
                '<option value="Bénéficiaire">Bénéficiaire</option>',
            );
            $("#cni").val('');
            $("#acte_naissance").val('');
            $("#certificat_vie").val('');
            $("#lab_certificat_vie").val('Choisir un fichier ');
            $("#lab_cni").val('Choisir un fichier ');
            $("#lab_acte_naissance").val('Choisir un fichier ');
        }
    </script>
@endsection
