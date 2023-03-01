@extends('layouts.template_profile')

@section('css')
	{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css"> --}}
@endsection

@section('contenu')
	<div class="nk-content">
		<div class="nk-block">
			<div class="row g-gs">
				<div class="container">
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
										<div class="alert alert-danger alert-dismissible d-none" id="alert-javascript" role="alert">
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
										<form method="POST" id="formUser" action="{{ route('user.update.profile', $user->id) }}">
											@csrf
											<div class="row d-flex justify-content-center">
												<div class='user-avatar-lg bg-primary d-flex justify-items-center'
													style="height: 150px; width: 150px; margin-bottom: 20px;">
													<img class='popup-image h-8 w-8 rounded-full object-cover' data-toggle="modal"
														data-target="#view-photo-modal" id="show_img" {{-- @dd(public_path('picture_profile\\' . $user->profile_photo_path)) --}}
														src="{{ isset($user) ? (isset($user->profile_photo_path) ? asset('picture_profile/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . $user->nom . '&background=c7932b&size=150&color=fff') : 'https://ui-avatars.com/api/?name=Membre&background=c7932b&size=150&color=fff' }}"
														alt='' />
												</div>
												<input type="file" class="p-md-5" id="pictureupload" accept=".jpg, .jpeg, .png, .webp" value="">
											</div>
											<div style="height: 20px"></div>
											<input type="text" id="id" name="id" value="{{ isset($user) ? $user->id : '0' }}" hidden>
											<div class="row g-gs">
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='matricule' :disabled=true :value="isset($user) ? $user->matricule : ''" input='text' :required="true"
															title="Matricule *">
														</x-input>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='nom' :value="isset($user) ? $user->nom : ''" input='text' :required="true" title="Nom *">
														</x-input>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='prenom' :value="isset($user) ? $user->prenom : ''" input='text' :required="true" title="Prenom *">
														</x-input>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='nationalité' :value="isset($user) ? $user->nationalité : ''" input='select' :options="[
														    ['name' => '', 'value' => ''],
														    ['name' => 'Camerounaise', 'value' => 'Camerounaise'],
														    ['name' => 'Centrafricaine', 'value' => 'Centrafricaine'],
														    ['name' => 'Congolaise', 'value' => 'Congolaise'],
														    ['name' => 'Gabonaise', 'value' => 'Gabonaise'],
														    ['name' => 'Guinéenne', 'value' => 'Guinéenne'],
														    ['name' => 'Tchadienne', 'value' => 'Tchadienne'],
														]" :required="true"
															title="Nationalité *">
														</x-input>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='email' :disabled=true :value="isset($user) ? $user->email : ''" input='email' :required="true" title="Email *">
														</x-input>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='dateNaissance' :value="isset($user) ? date('Y-m-d', strtotime($user->date_naissance)) : ''" input='date' :required="true"
															title="Date de Naissance *">
														</x-input>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='sexe' :value="isset($user) ? ($user->sexe == 'Masculin' ? 'Masculin' : 'Feminin') : ''" input='select' :options="[
														    ['name' => '', 'value' => ''],
														    ['name' => 'Masculin', 'value' => 'Masculin'],
														    ['name' => 'Feminin', 'value' => 'Feminin'],
														]" :required="true"
															title="Sexe *">
														</x-input>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<x-input name='status_matrimonial' :value="isset($user) ? $user->status_matrimonial : ''" input='select' :options="[
														    ['name' => '', 'value' => ''],
														    ['name' => 'Marié', 'value' => 'Marié'],
														    ['name' => 'Célibataire', 'value' => 'Célibataire'],
														    ['name' => 'Divorcé', 'value' => 'Divorcé'],
														    ['name' => 'Veuf(ve)', 'value' => 'Veuf(ve)'],
														]" :required="true"
															title="Statut matrimonial du membre*">
														</x-input>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<button type="submit" class="btn btn-lg btn-primary btn-submit-user">Sauvegarder</button>
														<button type="button" onclick="clearFormUser()" class="btn btn-lg btn-clear">@lang('Annuler')</button>
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
			$('.btn-submit-user').attr('disabled', true);
			e.preventDefault();
			let picture = ($('#pictureupload')[0].files)[0] ?? null;
			let matricule = $("#matricule").val();
			let nom = $("#nom").val();
			let id = $("#id").val();
			let prenom = $("#prenom").val();
			let nationalité = $("#nationalité").val();
			let agence = $("#agence").val();
			let email = $("#email").val();
			let dateNaissance = $("#dateNaissance").val();
			let dateHadhésion = $("#dateHadhésion").val();
			let categorie = $("#categorie").val();
			let listCategorie = $("#listCategorie").val();
			let sexe = $("#sexe").val();
			let role = $("#role").val();
			let type_parent = $("#type_parent").val();
			let status_matrimonial = $("#status_matrimonial").val();

			var formData = new FormData();
			formData.append('matricule', matricule);
			formData.append('nom', nom);
			formData.append('id', id);
			formData.append('prenom', prenom);
			formData.append('nationalité', nationalité);
			formData.append('email', email);
			formData.append('dateNaissance', dateNaissance);
			formData.append('sexe', sexe);
			formData.append('picture', picture);
			formData.append('status_matrimonial', status_matrimonial);
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				url: "" + $('#formUser').attr('action'),
				type: "" + $('#formUser').attr('method'),
				dataType: 'json',
				data: formData,
				contentType: false,
				processData: false,
				success: function(data) {
					$('.btn-submit-user').attr('disabled', false);
					if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
						//success
						Swal.fire(data.success,
							'Votre requête s\'est terminer avec succèss', 'success', );
					} else {
						$('.btn-submit-user').attr('disabled', false);
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
					$('.btn-submit-user').attr('disabled', false);
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
	</script>
@endsection
