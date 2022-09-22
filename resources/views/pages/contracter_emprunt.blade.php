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
										<form method="POST" id="formEmprunt"
											action="{{ Route::currentRouteName() === 'emprunt.appelASouscription' ? route('emprunt.saveEmprunt') : route('dons.store') }}">
											@csrf
											<div class="row g-gs">

												<div id="interne" class="col-md-12">
													<div class="row g-gs">
														<div class="col-md-12">
															<span>@lang('Selectionner le type d\'emprunt')</span><br>
														</div>
														<div class="col-md-6">
															<div class="row g-gs">
																<div class="col-md-6">
																	<div class="custom-control custom-radio">
																		<input type="radio" required name="type-emprunt" value="BLI"
																			class="custom-control-input type-emprunt" id="bli">
																		<label class="custom-control-label" for="bli">@lang('Bridge Loan Immo')</label>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="custom-control custom-radio">
																		<input type="radio" required name="type-emprunt" value="BBL"
																			class="custom-control-input type-emprunt" id="bbl">
																		<label class="custom-control-label" for="bbl">@lang('Back to Back Loan')</label>
																	</div>
																</div>
															</div>
															<div class="row g-gs">
																<div class="col-md-6">
																	<div class="custom-control custom-radio">
																		<input type="radio" required name="type-emprunt" value="ASS"
																			class="custom-control-input type-emprunt" id="ass">
																		<label class="custom-control-label" for="ass">@lang('Avance Sur Solde')</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="form-group">
																<x-input name='objet' :value="isset($don) && $don->type == 'interne' ? abs($don->montant) : ''" input='text' :required="true" title="Objet l'emprunt *">
																</x-input>
															</div>
														</div>
														<div class="col-md-12">
															<div class="form-group">
																<x-input name='montant' :value="isset($user) ? $user->status_matrimonial : ''" input='select' :options="[
																    ['name' => '', 'value' => ''],
																    ['name' => '500 000', 'value' => '500000'],
																    ['name' => '1 000 000', 'value' => '100000'],
																    ['name' => '1 500 000', 'value' => '1500000'],
																    ['name' => '2 000 000', 'value' => '2000000'],
																    ['name' => '2 500 000', 'value' => '2500000'],
																    ['name' => '3 000 000', 'value' => '3000000'],
																    ['name' => '3 500 000', 'value' => '3500000'],
																    ['name' => '4 000 000', 'value' => '4000000'],
																    ['name' => '4 500 000', 'value' => '4500000'],
																    ['name' => '5 000 000', 'value' => '5000000'],
																    ['name' => '5 500 000', 'value' => '5500000'],
																    ['name' => '6 000 000', 'value' => '6000000'],
																    ['name' => '6 500 000', 'value' => '6500000'],
																    ['name' => '7 000 000', 'value' => '7000000'],
																    ['name' => '7 500 000', 'value' => '7500000'],
																    ['name' => '8 000 000', 'value' => '8000000'],
																    ['name' => '8 500 000', 'value' => '8500000'],
																    ['name' => '9 000 000', 'value' => '9000000'],
																    ['name' => '9 500 000', 'value' => '9500000'],
																    ['name' => '10 000 000', 'value' => '10000000'],
																]" :required="true"
																	title="Montant de l'emprunt (FCFA) *">
																</x-input>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<button type="submit" class="btn btn-lg btn-primary btn-submit">@lang('Enregistrer')</button>
														<button type="button" onclick="clearformEmprunt()" class="btn btn-lg btn-clear">@lang('Annuler')</button>
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
		$('input:radio[name="type-emprunt"]').click(function(e) {
			if ($("input[name='type-emprunt']:checked").val() == 'BLI') {
				$("#montant").text('');
				$("#montant").append(
					'<option value=""></option><option value="2000000">2 000 000</option><option value="2500000">2 500 000</option><option value="3000000">3 000 000</option><option value="3500000">3 500 000</option><option value="4000000">4 000 000</option><option value="4500000">4 500 000</option><option value="5000000">5 000 000</option><option value="5500000">5 500 000</option><option value="6000000">6 000 000</option><option value="6500000">6 500 000</option><option value="7000000">7 000 000</option><option value="7500000">7 500 000</option> <option value="8000000">8 000 000</option><option value="8500000">8 500 000</option><option value="9000000">9 000 000</option><option value="9500000">9 500 000</option><option value="10000000">10 000 000</option>'
				);
			} else {
				if ($("input[name='type-emprunt']:checked").val() == 'BBL') {
					$("#montant").text('');
					$("#montant").append(
						'<option value=""></option><option value="500000">500 000</option><option value="1000000">1 000 000</option><option value="1500000">1 500 000</option><option value="2000000">2 000 000</option><option value="2500000">2 500 000</option><option value="3000000">3 000 000</option>'
					);
				} else {
					$("#montant").text('');
					$("#montant").append(
						'<option value=""></option><option value="500000">500 000</option><option value="1000000">1 000 000</option><option value="1500000">1 500 000</option><option value="2000000">2 000 000</option><option value="2500000">2 500 000</option><option value="3000000">3 000 000</option><option value="3500000">3 500 000</option><option value="4000000">4 000 000</option><option value="4500000">4 500 000</option><option value="5000000">5 000 000</option><option value="5500000">5 500 000</option><option value="6000000">6 000 000</option><option value="6500000">6 500 000</option><option value="7000000">7 000 000</option><option value="7500000">7 500 000</option> <option value="8000000">8 000 000</option><option value="8500000">8 500 000</option><option value="9000000">9 000 000</option><option value="9500000">9 500 000</option><option value="10000000">10 000 000</option>'
					);
				}
			}
		});
		$('.btn-submit').click(function(e) {
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			$('.btn-submit').attr('disabled', true);
			e.preventDefault();
			let type = $(".type-emprunt:checked").val();
			let montant = $("#montant").val();
			let objet = $("#objet").val();
			var formData = new FormData();
			if (!$("input[name='type-emprunt']:checked").val()) {
				$('#alert-javascript').text('');
				$('#alert-javascript').removeClass('d-none');
				$('#alert-javascript').text("Sélectionner le type d\'emprunt");
				$('.btn-submit').attr('disabled', false);
				return false;
			}


			formData.append('type_emprunt', type);
			formData.append('montant', montant);
			formData.append('objet', objet);
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				url: "" + $('#formEmprunt').attr('action'),
				type: "" + $('#formEmprunt').attr('method'),
				dataType: 'json',
				data: formData,
				contentType: false,
				processData: false,
				success: function(data) {
					if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
						//success
						Swal.fire(data.success,
							'Votre requête s\'est terminer avec succèss', 'success', );
						Swal.fire({
							title: 'Voulez-vous envoyer le dossier à la mutuele pour étude ?',
							text: "Si oui, cliquez sur Envoyer",
							icon: 'success',
							showCancelButton: true,
							confirmButtonText: 'Envoyer',
							allowOutsideClick: false,
							cancelButtonText: 'Ne pas envoyer'
						}).then(function(result) {
							if (result.value) {
								history.pushState({}, null,
									data.route

								);
								window.setTimeout('location.reload()', 500);
							} else {
								Swal.fire(data.success,
									'Votre requête s\'est terminer avec succèss', 'success', );
							}
						});
						clearformEmprunt();
					} else {
						$('.btn-submit').attr('disabled', false);
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

		function clearformEmprunt() {

			@if (Route::currentRouteName() === 'dons.edit')
				history.pushState({}, null, "{{ route('dons.index') }}");
				window.setTimeout('location.reload()', 1600);
			@endif
			$('#formEmprunt').attr('action', "{{ route('emprunt.saveEmprunt') }}");
			$('#formEmprunt').attr('method', "POST");
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			$('#objet').val('');
			$("#montant").text('');
			$("#montant").append(
				'<option value=""></option><option value="500000">500 000</option><option value="1000000">1 000 000</option><option value="1500000">1 500 000</option><option value="2000000">2 000 000</option><option value="2500000">2 500 000</option><option value="3000000">3 000 000</option><option value="3500000">3 500 000</option><option value="4000000">4 000 000</option><option value="4500000">4 500 000</option><option value="5000000">5 000 000</option><option value="5500000">5 500 000</option><option value="6000000">6 000 000</option><option value="6500000">6 500 000</option><option value="7000000">7 000 000</option><option value="7500000">7 500 000</option> <option value="8000000">8 000 000</option><option value="8500000">8 500 000</option><option value="9000000">9 000 000</option><option value="9500000">9 500 000</option><option value="10000000">10 000 000</option>'
			);
			$(".type-emprunt:checked").prop('checked', false);
			$('.btn-submit').attr('disabled', false);
		}
	</script>
@endsection
