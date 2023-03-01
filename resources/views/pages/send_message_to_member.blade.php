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
										<form method="POST" id="formMessage" action="{{ route('messagerie.SendMessageToMemberPost') }}">
											@csrf
											<div class="row g-gs">
												<div class="col-md-12">
													<div class="form-group">
														<label style="font-weight: bold;" for="listMembre">@lang('Membre')
															*</label>
														<div class="form-control-wrap">
															<select class="form-control" id="listMembre" name="membre">

															</select>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label style="font-weight: bold;" for="listMembre">@lang('Message')
															*</label>
														<div class="form-control-wrap">
															<textarea name="message" id="message" cols="30" rows="10" class="form-control" required></textarea>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label style="font-weight: bold;" for="fiichier">Joindre un fichier</label>
														<div class="form-control-wrap">
															<div class="custom-file">
																<input type="file" name="fichier" id="fichier" class="custom-file-input">
																<label class="custom-file-label" id="lab_fichier" for="cni">Choisir
																	un
																	fichier</label>
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<button type="submit" class="btn btn-lg btn-primary btn-submit">Sauvegarder</button>
														<button type="button" onclick="clearFormMessage()" class="btn btn-lg btn-clear">@lang('Annuler')</button>
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

			//variable
			let message = $("#message").val();
			let fichier = $("#fichier")[0].files;
			let id_membre = $("#listMembre").val();
			//formadata
			var formData = new FormData();
			formData.append('message', message);
			formData.append('id_membre', id_membre);
			formData.append('fichier', fichier[0]);

			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
				},
				url: "" + $('#formMessage').attr('action'),
				type: "" + $('#formMessage').attr('method'),
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
						clearFormMessage();
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

		//load member input
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


		function clearFormMessage() {
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			$("#message").val('');
			$("#fichier").val('');
			$("#lab_fichier").text('Choisir un fichier ');
			$('.btn-submit').attr('disabled', false);
			$("#listMembre").empty();
			loadMembre();
		}
	</script>
@endsection
