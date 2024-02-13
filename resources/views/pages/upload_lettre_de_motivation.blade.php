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
											action="{{ Route::currentRouteName() === 'emprunt.showFormUploadLettreDeMotivation' ? route('emprunt.uploader-lettre-souscription') : route('dons.store') }}">
											@csrf
											<div class="row g-gs">
												<div id="interne" class="col-md-12">
													<div class="row g-gs">
														<input type="text" hidden name="id" id="id" value="{{ $id }}">
														<div class="col-md-12">
															<b>
																<h5>Après la validation de ce formulaire, vous ne pourriez plus modifier cette demande de souscription.
																	<br><br> Vous pouvez consulté le tableau de commission donnant les modalités de remboursement. <a
																		href="{{ asset('document/tableau_commission.pdf') }}">cliquez ici</a><br><br><br><br>
																</h5>
															</b>
															Télécharger la lettre de souscription en cliquant sur le boutton ci-après : <br><br><a
																href="{{ route('emprunt.download-lettre-souuscription', $id) }}" class="btn btn-primary"
																style="color: white;">Télécharger <em class="icon ni ni-download"></em></a><br>
															<p>Lettre de souscription.pdf</p>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="lss">Lettre de souscription signée</label>
																<div class="form-control-wrap">
																	<div class="custom-file">
																		<input type="file" required name="lss" id="lss" class="custom-file-input">
																		<label class="custom-file-label" id="lab_lss" for="cni">Choisir
																			un
																			fichier</label>
																	</div>
																</div>
															</div>
														</div>
														{{-- <div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="adi">Avis d'imposition</label>
																<div class="form-control-wrap">
																	<div class="custom-file">
																		<input type="file" required name="adi" id="adi" class="custom-file-input">
																		<label class="custom-file-label" id="lab_adi" for="cni">Choisir
																			un
																			fichier</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="bds">Bulletin de salaire</label>
																<div class="form-control-wrap">
																	<div class="custom-file">
																		<input type="file" required name="bds" id="bds" class="custom-file-input">
																		<label class="custom-file-label" id="lab_bds" for="cni">Choisir
																			un
																			fichier</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="ddt">devis de travaux</label>
																<div class="form-control-wrap">
																	<div class="custom-file">
																		<input type="file" required name="ddt" id="ddt" class="custom-file-input">
																		<label class="custom-file-label" id="lab_ddt" for="cni">Choisir
																			un
																			fichier</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="pdv">Proposition de vente</label>
																<div class="form-control-wrap">
																	<div class="custom-file">
																		<input type="file" required name="pdv" id="pdv" class="custom-file-input">
																		<label class="custom-file-label" id="lab_pdv" for="cni">Choisir
																			un
																			fichier</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="cdt">Contrat de travail</label>
																<div class="form-control-wrap">
																	<div class="custom-file">
																		<input type="file" required name="cdt" id="cdt" class="custom-file-input">
																		<label class="custom-file-label" id="lab_cdt" for="cni">Choisir
																			un
																			fichier</label>
																	</div>
																</div>
															</div>
														</div> --}}
														<div class="col-md-6">
															<div class="form-group">
																<label style="font-weight: bold;" for="autres">Autres</label>
																<div class="form-control-wrap">
																	<div class="custom-file">
																		<input type="file" required name="autres" id="autres" class="custom-file-input">
																		<label class="custom-file-label" id="lab_autres" for="cni">Choisir
																			un
																			fichier</label>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<button type="submit" class="btn btn-lg btn-primary btn-submit">@lang('Enregistrer')</button>
														<button type="button" onclick="clearform()" class="btn btn-lg btn-clear">@lang('Annuler')</button>
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
		$('.btn-submit').click(function(e) {
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			$('.btn-submit').attr('disabled', true);
			e.preventDefault();
			let id = $("#id").val();
			let lss = $("#lss")[0].files;
			// let adi = $("#adi")[0].files;
			// let bds = $("#bds")[0].files;
			// let ddt = $("#ddt")[0].files;
			// let pdv = $("#pdv")[0].files;
			// let cdt = $("#cdt")[0].files;
			let autres = $("#autres")[0].files;
			var formData = new FormData();
			formData.append('id', id);
			formData.append('lss', lss[0]);
			// formData.append('adi', adi[0]);
			// formData.append('bds', bds[0]);
			// formData.append('ddt', ddt[0]);
			// formData.append('pdv', pdv[0]);
			// formData.append('cdt', cdt[0]);
			formData.append('autres', autres[0]);
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

						Swal.fire(data.success,
							'Votre requête s\'est terminer avec succèss', 'success', );
						clearform();
						history.pushState({}, null,
							"{{ route('emprunt.viewForListOfEmpruntOfUUserWhoIsConnect') }}"
						);
						window.setTimeout('location.reload()', 1600);
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
			Swal.fire(data.success,
				'Votre requête s\'est terminer avec succèss',
				'success', );

		});

		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

		function clearform() {

			@if (Route::currentRouteName() === 'dons.edit')
				history.pushState({}, null, "{{ route('dons.index') }}");
				window.setTimeout('location.reload()', 1600);
			@endif
			$('#formEmprunt').attr('action', "{{ route('emprunt.saveEmprunt') }}");
			$('#formEmprunt').attr('method', "POST");
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			$("#lss").val('');
			$("#lab_lss").text('Choisir un fichier ');
			$('.btn-submit').attr('disabled', false);
		}
	</script>
@endsection
