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
											action="{{ Route::currentRouteName() === 'emprunt.getViewEnregistrementManuelD1Emprunt' ? route('emprunt.saveEmpruntManuel') : route('dons.store') }}">
											@csrf
											<div class="row g-gs">
												<div class="col-md-12">
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
												<div id="interne" class="col-md-12">
													<div class="row g-gs">
														<div class="col-md-12">
															<span>@lang('Selectionner le type d\'emprunt')</span><br>
														</div>
														<div class="col-md-6">
															<div class="row g-gs">
																<div class="col-md-6">
																	<div class="custom-control custom-radio">
																		<input type="radio" required name="type-emprunt" value="BL"
																			class="custom-control-input type-emprunt" id="bl">
																		<label class="custom-control-label" for="bl">@lang('Bridge Loan')</label>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="custom-control custom-radio">
																		<input type="radio" required name="type-emprunt" value="ASS"
																			class="custom-control-input type-emprunt" id="ass">
																		<label class="custom-control-label" for="ass">@lang('Avance Sur Salaire')</label>
																	</div>
																</div>
															</div>
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
																		<input type="radio" required name="type-emprunt" value="ASG"
																			class="custom-control-input type-emprunt" id="asg">
																		<label class="custom-control-label" for="asg">@lang('Avance Sur Gratification')</label>
																	</div>
																</div>
															</div>
															<div class="row g-gs">
																<div class="col-md-6">
																	<div class="custom-control custom-radio">
																		<input type="radio" required name="type-emprunt" value="BBL"
																			class="custom-control-input type-emprunt" id="bbl">
																		<label class="custom-control-label" for="bbl">@lang('Back to Back Loan')</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-12">
															<div class="form-group">
																<x-input name='objet' :value="isset($don) && $don->type == 'interne' ? abs($don->montant) : ''" input='text' :required="true" title="Objet l'emprunt">
																</x-input>

															</div>
														</div>
														<div class="col-md-12" id="amount_enter_div" hidden>
															<div class="form-group">
																<x-input name='montant_enter' :value="isset($don) && $don->type == 'interne' ? abs($don->montant) : ''" input='number' title="Montant de l'emprunt (FCFA) *">
																</x-input>
															</div>
														</div>
														<div class="col-md-12" id="amount_select_div">
															<div class="form-group">
																<label style="font-weight: bold;" for="montant">@lang('Montant de l\'emprunt')</label>

																<div class="row align-center">
																	<div class="col-md-1 d-flex justify-content-center"><button class="btn btn-primary" type="button"
																			id="btnmoin">-</button></div>
																	<div class="col-md-10 d-flex justify-content-center">
																		<input type="range" class="form-control" id="montant" name="montant" min="50000" max="50000000"
																			value="50000" step="50000">
																	</div>
																	<div class="col-md-1 d-flex justify-content-center" id="btnplus"><button class="btn btn-primary"
																			type="button">+</button></div>
																</div>
																<label for="" id="labelmontant">Montant : 50 000FCFA</label>
															</div>
														</div>

														{{-- <div class="col-md-12">
															<div class="form-group">
																<x-input name='montant_commission' :value="isset($don) && $don->type == 'interne' ? abs($don->montant) : ''" input='number' title="Montant de la commission">
																</x-input>
															</div>
														</div> --}}
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<button type="submit" class="btn btn-lg btn-primary btn-submit">@lang('Enregistrer')</button>
														<button type="button" onclick="clearformEmprunt()"
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

		$("#montant").on("input", function() {
			$('#labelmontant').text('Montant : ' + Intl.NumberFormat().format($(this).val()) + 'FCFA');
		});

		$("#btnmoin").on("click", function() {
			console.log(12);
			if ($('#montant').val() > 50000) {
				var montant = $('#montant').val();
				$('#montant').val(parseInt(montant) - 50000);
				$('#labelmontant').text('Montant : ' + Intl.NumberFormat().format($('#montant').val()) +
					'FCFA');
			}
		});

		$('#btnplus').click(function(e) {
			var montant = parseInt($('#montant').val());
			$('#montant').val(montant + 50000);
			$('#labelmontant').text('Montant : ' + Intl.NumberFormat().format($('#montant').val()) + 'FCFA');
			// if ($('#montant').val() > 50000) {}

		});

		$(document).ready(function() {
			clearformEmprunt();
		});
		// $('input:radio[name="type-emprunt"]').click(function(e) {
		// 	$("#montant").val(50000);
		// 	$("#montant_enter").val("");
		// 	$("#labelmontant").text('Montant : 50 000FCFA');
		// 	if ($("input[name='type-emprunt']:checked").val() == 'BLI') {
		// 		$("#amount_select_div").attr("hidden", false);
		// 		$("#amount_enter_div").attr("hidden", true);
		// 	} else {
		// 		if ($("input[name='type-emprunt']:checked").val() == 'BBL') {
		// 			$("#amount_select_div").attr("hidden", false);
		// 			$("#amount_enter_div").attr("hidden", true);
		// 		} else {
		// 			if ($("input[name='type-emprunt']:checked").val() == 'BL') {
		// 				$("#amount_select_div").attr("hidden", false);
		// 				$("#amount_enter_div").attr("hidden", true);
		// 			} else {
		// 				$("#amount_select_div").attr("hidden", true);
		// 				$("#amount_enter_div").attr("hidden", false);
		// 			}

		// 		}
		// 	}
		// });
		$('.btn-submit').click(function(e) {
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			$('.btn-submit').attr('disabled', true);
			e.preventDefault();
			let type = $(".type-emprunt:checked").val();
			let montant =  $("#montant").val();
			let objet = $("#objet").val();
			// let montant_commission = $("#montant_commission").val();
			var id = $("#listMembre").val();
			var formData = new FormData();
			if ($("input[name='type-emprunt']:checked").val()) {
			formData.append('type_emprunt', type);
			}
			formData.append('montant', montant);
			formData.append('objet', objet);
			// formData.append('montant_commission', montant_commission);
			if (id!=null) {
			formData.append('id', id);
			}
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

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		function clearformEmprunt() {

			// @if (Route::currentRouteName() === 'dons.edit')
			// 	history.pushState({}, null, "{{ route('dons.index') }}");
			// 	window.setTimeout('location.reload()', 1600);
			// @endif
			$('#formEmprunt').attr('action', "{{ route('emprunt.saveEmpruntManuel') }}");
			$('#formEmprunt').attr('method', "POST");
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			 $("#listMembre").empty();
			loadMembre();
			$('#objet').val('');
			$("#montant").val(50000);
			// $("#montant_enter").val("");
			// $("#montant_commission").val("");
			$("#labelmontant").text('Montant : 50 000FCFA');
			$(".type-emprunt:checked").prop('checked', false);
			$('.btn-submit').attr('disabled', false);
		}
	</script>
@endsection
