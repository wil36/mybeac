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
										<form method="POST" id="formUser"
											action="{{ Route::currentRouteName() === 'dons.edit' ? route('dons.update', $don->id) : route('dons.store') }}">
											@csrf
											<div class="row g-gs">
												<div class="col-md-12">
													<div class="form-group">
														<div class="custom-control custom-radio">
															<input type="radio" checked id="membre_interne" name="membre_interne_externe"
																class="custom-control-input membre_interne_externe" value="1">
															<label class="custom-control-label" for="membre_interne">@lang('Membre interne')</label>
														</div>
														<div class="custom-control custom-radio" style="margin-left: 20px;">
															<input type="radio" id="membre_externe" name="membre_interne_externe"
																class="custom-control-input membre_interne_externe" value="2">
															<label class="custom-control-label" for="membre_externe">@lang('Membre externe')</label>
														</div>
													</div>
												</div>
												<div id="interne" class="col-md-12">
													<div class="row g-gs">
														<div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="listMembre">@lang('Membre')
																	*</label>
																<div class="form-control-wrap">
																	<select class="form-control" id="listMembre" name="membre">
																		@if (isset($user) && $don->type == 'interne')
																			<option value="{{ $don->users_id }}">
																				{{ $user->matricule . '   |   ' . $user->nom . ' ' . $user->prenom }}
																			</option>
																		@endif
																	</select>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<x-input name='montant1' :value="isset($don) && $don->type == 'interne' ? $don->montant : ''" input='number' :required="true" title="Montant du don *">
																</x-input>
															</div>
														</div>
													</div>
												</div>
												<div id="externe" hidden class="col-md-12">
													<div class="row g-gs">
														<div class="col-md-6">
															<div class="form-group">
																<x-input name='nom' :value="isset($don) && $don->type == 'externe' ? $don->nom : ''" input='text' :required="true" title="Nom *">
																</x-input>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<x-input name='prenom' :value="isset($don) && $don->type == 'externe' ? $don->prenom : ''" input='text' :required="true" title="Prénom *">
																</x-input>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<x-input name='email' :value="isset($don) && $don->type == 'externe' ? $don->email : ''" input='email' :required="true" title="Email *">
																</x-input>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<x-input name='tel' :value="isset($don) && $don->type == 'externe' ? $don->tel : ''" input='tel' :required="true" title="Numéro de Téléphone *">
																</x-input>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<x-input name='sexe' :value="isset($don) && $don->type == 'externe'
																    ? ($don->sexe == 'Masculin'
																        ? 'Masculin'
																        : 'Feminin')
																    : ''" input='select' :options="[
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
																<x-input name='montant2' :value="isset($don) && $don->type == 'externe' ? $don->montant : ''" input='number' :required="true" title="Montant du don *">
																</x-input>
															</div>
														</div>
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
	 $(document).ready(function() {
	@if (Route::currentRouteName() === 'dons.edit' && isset($don) && $don->type == 'externe')
		$("#membre_externe").prop("checked", true);
		$('#interne').attr('hidden', true);
		$('#externe').attr('hidden', false);
	@endif
	 });
	 $('#membre_interne').change(function() {
	  $('#interne').attr('hidden', false);
	  $('#externe').attr('hidden', true);
	  clearFormUser();
	 });
	 $('#membre_externe').change(function() {
	  $('#interne').attr('hidden', true);
	  $('#externe').attr('hidden', false);
	  clearFormUser();
	 });


	 $('.btn-submit-user').click(function(e) {
	  $('#alert-javascript').addClass('d-none');
	  $('#alert-javascript').text('');
	  $('.btn-submit-user').attr('disabled', true);
	  e.preventDefault();
	  let choix = $('#membre_interne').is(':checked') ? $("#membre_interne").val() : $("#membre_externe")
	   .val();
	  let nom = $("#nom").val();
	  let prenom = $("#prenom").val();
	  let email = $("#email").val();
	  let tel = $("#tel").val();
	  let montant1 = $("#montant1").val();
	  let montant2 = $("#montant2").val();
	  let id_membre = $("#listMembre").val();
	  let sexe = $("#sexe").val();

	  var formData = new FormData();
	  formData.append('choix', choix);
	  formData.append('nom', nom);
	  formData.append(
	   'prenom', prenom);
	  formData.append('email', email);
	  formData.append('tel', tel);
	  formData.append('montant1',
	   montant1);
	  formData.append('montant2', montant2);
	  formData.append('id_membre', id_membre);
	  formData.append('sexe',
	   sexe);
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
	    if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
	     console.log(data);
	     //success
	     Swal.fire(data.success,
	      'Votre requête s\'est terminer avec succèss', 'success', );
	     clearFormUser();
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
	    Swal.fire('Une erreur s\'est produite.',
	     'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
	     'error');

	   }
	  });

	 });
	 var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	 $(document).ready(function() {
	  loadMembre();
	 });

	 function loadMembre() {

	  $("#listMembre").select2({
	   language: "fr",
	   ajax: {
	    url: "{{ route('getuserAjaxCombobox') }}",
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
	@if (Route::currentRouteName() === 'dons.edit')
		history.pushState({}, null, "{{ route('dons.index') }}");
		window.setTimeout('location.reload()', 1600);
	@endif
	  $('#formUser').attr('action', "{{ route('dons.store') }}");
	  $('#formUser').attr('method', "POST");
	  $('#alert-javascript').addClass('d-none');
	  $('#alert-javascript').text('');
	  $("#montant1").val('');
	  $("#montant2").val('');
	  $("#nom").val('');
	  $("#prenom").val('');
	  $("#email").val('');
	  $("#tel").val('');
	  $("#sexe").text('');
	  $("#sexe").append(
	   '<option value=""></option><option value="Masculin">Masculin</option><option value="Feminin">Feminin</option>'
	  );
	  $("#listMembre").empty();
	  loadMembre();
	  $('.btn-submit-user').attr('disabled', false);
	 }
	</script>
@endsection
