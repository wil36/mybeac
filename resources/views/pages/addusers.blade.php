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
                                        <form method="POST" id="formUser"
                                            action="{{ Route::currentRouteName() === 'membre.edit' ? route('membre.update', $user->id) : route('membre.store') }}">
                                            @csrf
                                            <div class="row d-flex justify-content-center">
                                                <div class='user-avatar-lg bg-primary d-flex justify-items-center'
                                                    style="height: 150px; width: 150px; margin-bottom: 20px;">
                                                    <img class='object-cover w-8 h-8 rounded-full popup-image'
                                                        data-toggle="modal" data-target="#view-photo-modal" id="show_img"
                                                        {{-- @dd(public_path('picture_profile\\' . $user->profile_photo_path)) --}}
                                                        src='{{ isset($user)? (isset($user->profile_photo_path)? asset('picture_profile/' . $user->profile_photo_path): 'https://ui-avatars.com/api/?name=' . $user->nom . '&background=1ee0ac&size=150&color=fff'): 'https://ui-avatars.com/api/?name=Membre&background=1ee0ac&size=150&color=fff' }}'
                                                        alt='' />
                                                </div>
                                                <input type="file" class="p-md-5" id="pictureupload"
                                                    accept=".jpg, .jpeg, .png, .webp" value="">
                                            </div>
                                            <div style="height: 20px"></div>
                                            <div class="row g-gs">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='matricule'
                                                            :value="isset($user) ? $user->matricule : ''" input='text'
                                                            :required="true" title="Matricule *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='nom' :value="isset($user) ? $user->nom : ''"
                                                            input='text' :required="true" title="Nom *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='prenom' :value="isset($user) ? $user->prenom : ''"
                                                            input='text' :required="true" title="Prenom *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='nationalité'
                                                            :value="isset($user) ? $user->nationalité : ''" input='text'
                                                            :required="true" title="Nationalité *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='agence' :value="isset($user) ? $user->agence : ''"
                                                            input='text' :required="true" title="Agence *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='email' :value="isset($user) ? $user->email : ''"
                                                            input='email' :required="true" title="Email *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='tel' :value="isset($user) ? $user->tel : ''"
                                                            input='tel' :required="true" title="Numéro de Téléphone *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='dateNaissance'
                                                            :value="isset($user) ?date('Y-m-d', strtotime($user->date_naissance))  : ''"
                                                            input='date' :required="true" title="Date de Naissance *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='dateRecrutement'
                                                            :value="isset($user) ?date('Y-m-d', strtotime($user->date_recrutement))  : ''"
                                                            input='date' :required="true" title="Date de Recrutement *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='dateHadhésion'
                                                            :value="isset($user) ?date('Y-m-d', strtotime($user->date_hadésion))  : ''"
                                                            input='date' :required="true" title="Date de d'Hadhésion *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label style="font-weight: bold;" for="listCategorie">Catégorie
                                                            *</label>
                                                        <div class="form-control-wrap">
                                                            <select class="form-control" id="listCategorie"
                                                                name="listCategorie">
                                                                @isset($category)
                                                                    <option value="{{ $user->categories_id }}">
                                                                        {{ $category->code . '   |   ' . $category->libelle }}
                                                                    </option>
                                                                @endisset
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <x-input name='sexe'
                                                            :value="isset($user) ? $user->sexe=='Masculin'?'Masculin':'Feminin' : ''"
                                                            input='select'
                                                            :options='[["name"=>"","value"=>""],["name"=>"Masculin","value"=>"Masculin"],["name"=>"Feminin","value"=>"Feminin"]]'
                                                            :required="true" title="Sexe *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <x-input name='role'
                                                            :value="isset($user) ? $user->role=='admin'?'Administrateur':'Membre' : ''"
                                                            input='select'
                                                            :options='[["name"=>"","value"=>""],["name"=>"Administrateur","value"=>"admin"],["name"=>"Membre","value"=>"agent"]]'
                                                            :required="true" title="Grade *">
                                                        </x-input>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <button type="submit"
                                                            class="btn btn-lg btn-primary btn-submit-user">Sauvegarder</button>
                                                        <button type="button" onclick="clearFormUser()"
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
        $('.btn-submit-user').click(function(e) {
            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            e.preventDefault();
            let picture = ($('#pictureupload')[0].files)[0] ?? null;
            let matricule = $("#matricule").val();
            let nom = $("#nom").val();
            let prenom = $("#prenom").val();
            let nationalité = $("#nationalité").val();
            let agence = $("#agence").val();
            let email = $("#email").val();
            let tel = $("#tel").val();
            let dateNaissance = $("#dateNaissance").val();
            let dateRecrutement = $("#dateRecrutement").val();
            let dateHadhésion = $("#dateHadhésion").val();
            let categorie = $("#categorie").val();
            let listCategorie = $("#listCategorie").val();
            let sexe = $("#sexe").val();
            let role = $("#role").val();

            var formData = new FormData();
            formData.append('matricule', matricule);
            formData.append('nom', nom);
            formData.append('prenom', prenom);
            formData.append('nationalité', nationalité);
            formData.append('agence', agence);
            formData.append('email', email);
            formData.append('tel', tel);
            formData.append('dateNaissance', dateNaissance);
            formData.append('dateRecrutement', dateRecrutement);
            formData.append('dateHadhésion', dateHadhésion);
            formData.append('categorie', categorie);
            formData.append('role', role);
            formData.append('sexe', sexe);
            formData.append('listCategorie', listCategorie);
            formData.append('picture', picture);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                url: "" + $('#formUser').attr('action'),
                type: "" + $('#formUser').attr('method'),
                dataType: 'json',
                // data: {
                //     matricule: matricule,
                //     nom: nom,
                //     prenom: prenom,
                //     nationalité: nationalité,
                //     agence: agence,
                //     email: email,
                //     tel: tel,
                //     dateNaissance: dateNaissance,
                //     dateRecrutement: dateRecrutement,
                //     dateHadhésion: dateHadhésion,
                //     categorie: categorie,
                //     role: role,
                //     sexe: sexe,
                //     listCategorie: listCategorie,
                //     picture: picture == null ? null : picture,
                // },
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
                        //success
                        Swal.fire(data.success,
                            'Votre requête s\'est terminer avec succèss', 'success', );
                        clearFormUser();
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
            loadCategories();
        });

        function loadCategories() {

            $("#listCategorie").select2({
                language: "fr",
                ajax: {
                    url: "{{ route('getCategories') }}",
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

        $(function() {
            $('#pictureupload').change(function(event) {
                var x = URL.createObjectURL(event.target.files[0]);
                $('#show_img').attr('src', x);
                $('#show_img').attr('height', 150);
                $('#show_img').attr('width', 150);
            });
        });

        function clearFormUser() {
            @if (Route::currentRouteName() === 'membre.edit')
                history.pushState({}, null, "{{ route('membre.index') }}");
                window.setTimeout('location.reload()', 1500);
            @endif
            $('#formUser').attr('action', "{{ route('membre.store') }}");
            $('#formUser').attr('method', "POST");
            $('#alert-javascript').addClass('d-none');
            $('#alert-javascript').text('');
            $("#matricule").focus();
            $("#matricule").val('');
            $("#nom").val('');
            $("#prenom").val('');
            $("#nationalité").val('');
            $("#agence").val('');
            $("#email").val('');
            $("#tel").val('');
            $("#dateNaissance").val('');
            $("#dateRecrutement").val('');
            $("#dateHadhésion").val('');
            $("#role").text('');
            $("#sexe").text('');
            $("#role").append(
                '<option value=""></option><option value="admin">Administrateur</option><option value="agent">Agent</option>'
            );
            $("#sexe").append(
                '<option value=""></option><option value="Masculin">Masculin</option><option value="Feminin">Feminin</option>'
            );
            $("#listCategorie").empty();
            loadCategories();
            $("#pictureupload").val('');
            $('#show_img').attr('src', '');
            $('#show_img').attr('height', 0);
            $('#show_img').attr('width', 0);
        }
    </script>
@endsection
