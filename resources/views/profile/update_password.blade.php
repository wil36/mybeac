@extends('layouts.template_profile')

@section('title')
	@lang('Ma Beac')
@endsection

@section('contenu')
	<div class="nk-app-root">
		<!-- main @s -->
		<div class="nk-main">
			<!-- wrap @s -->
			<div class="nk-wrap nk-wrap-nosidebar">
				<!-- content @s -->

				<div class="nk-content">
					<div class="nk-block nk-block-middle nk-auth-body wide-xs">

						<div class="card">
							<div class="card-inner card-inner-lg">
								<div class="nk-block-head">
									<div class="nk-block-head-content">


									</div>
									@if (session('status'))
										<br>
										<div class="alert alert-success">
											{{ session('status') }}
										</div>
									@endif
									<div class="alert alert-danger alert-dismissible d-none" id="alert-javascript" role="alert">
									</div>
									@if (count($errors) > 0)
										<br>
										<div class="alert alert-danger">
											<ul>
												@foreach ($errors->all() as $error)
													<li>{{ $error }}</li>
												@endforeach
											</ul>
										</div>
									@endif
								</div>
								<form method="POST" class="form-validate" id="form-validate" action="{{ route('user.update.password') }}">
									@csrf
									<input type="hidden" name="token" value="">
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="email">@lang('Mot de Passe actuel')</label>
										</div>
										<input class="form-control form-control-lg" id="current_password" type="password" name="current_password"
											value="" required autofocus placeholder="@lang('Entrer votre mot de passe actuel')">
									</div>
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="password">@lang('Nouveau Mot de Passe')</label>
										</div>
										<input class="form-control form-control-lg" id="password" type="password" name="password" required
											autocomplete="new-password" placeholder="@lang('Entrer votre nouveau mot de passe')">
									</div>
									<div class="form-group">
										<div class="form-label-group">
											<label class="form-label" for="password_confirmation">@lang('Confirmation du nouveau mot de passe')</label>
										</div>
										<input class="form-control form-control-lg" id="password_confirmation" type="password"
											name="password_confirmation" required autocomplete="new-password" placeholder="@lang('Confirmer votre nouveau mot de passe')">
									</div><br>
									<div class="form-group">
										<button type="submit" class="btn btn-lg btn-primary btn-block btn-submit">@lang('Changer le mot de passe')</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- content @e -->
		</div>
		<!-- main @e -->
	</div>
@endsection

@section('script')
	<script>
		$('.btn-submit').click(function(e) {
			$('#alert-javascript').addClass('d-none');
			$('#alert-javascript').text('');
			$('.btn-submit').attr('disabled', true);
			e.preventDefault();
			console.log(1);
			let currentPassword = $("#current_password").val();
			let newPassword = $("#password").val();
			let confirmPassword = $("#password_confirmation").val();

			if (newPassword != confirmPassword) {
				$('#alert-javascript').removeClass('d-none');
				$('#alert-javascript').text('Le nouveau mot de passe et la confirmation ne correspondent pas');
				$('.btn-submit').attr('disabled', false);

			} else {
				var formData = new FormData();
				formData.append('current_password', currentPassword);
				formData.append('password', newPassword);

				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
					},
					url: "" + $('#form-validate').attr('action'),
					type: "" + $('#form-validate').attr('method'),
					dataType: 'json',
					data: formData,
					contentType: false,
					processData: false,
					success: function(data) {
						$('.btn-submit').attr('disabled', false);
						if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
							//success
							$("#current_password").val("");
							$("#password").val("");
							$("#password_confirmation").val("");
							Swal.fire(data.success,
								'Votre requête s\'est terminer avec succèss', 'success', );
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
						$('.btn-submit-user').attr('disabled', false);
						Swal.fire('Une erreur s\'est produite.',
							'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
							'error');

					}
				});
			}



		});

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
