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
								{{-- @dd($ayantsdroits) --}}
								<div class="card">
									<div class="card-inner">
										<form method="POST" id="formayantsdroits"
											action="{{ Route::currentRouteName() === 'ayantsdroits.edit'? route('ayantsdroits.update', $ayantsdroits->id): route('ayantsdroits.store') }}">
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
																		<x-input name='father_matricule' :value="isset($father) ? $father->matricule : ''" input='text' :required="true" title="Matricule *"
																			:disabled="true">
																		</x-input>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<x-input name='father_nom' :value="isset($father) ? $father->nom : ''" input='text' :required="true" title="Nom *"
																			:disabled="true">
																		</x-input>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<x-input name='father_prenom' :value="isset($father) ? $father->prenom : ''" input='text' :required="true" title="Prénom *"
																			:disabled="true">
																		</x-input>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<x-input name='father_email' :value="isset($father) ? $father->email : ''" input='text' :required="true" title="Email *"
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
																		<input type="text" hidden name="id" id="id" value="{{ isset($id) ? $id : '' }}">
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="form-group">
																		<x-input name='nom' :value="isset($ayantsdroits) ? $ayantsdroits->nom : ''" input='text' :required="true" title="Nom *">
																		</x-input>
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="form-group">
																		<x-input name='prenom' :value="isset($ayantsdroits) ? $ayantsdroits->prenom : ''" input='text' :required="true" title="Prénom *">
																		</x-input>
																	</div>
																</div>
																{{-- <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <x-input name='statut'
                                                                            :value="isset($ayantsdroits) ? $ayantsdroits->statut : ''"
                                                                            input='select'
                                                                            :options='[["name"=>"","value"=>""],["name"=>"Conjoint","value"=>"Conjoint"],["name"=>"Enfant","value"=>"Enfant"],["name"=>"{{ $father->type_parent == 0 ? 'Parent' : 'Tuteur ou tutrice' }}","value"=>"Parent"],["name"=>"Tuteur ou tutrice","value"=>"Tuteur"],["name"=>"Tatrice","value"=>"Tatrice"],["name"=>"Bénéficiaire","value"=>"Bénéficiaire"],]'
                                                                            :required="true" title="Statut *">
                                                                        </x-input>
                                                                    </div>
                                                                </div> --}}
																<div class="col-md-12">
																	<div class="form-group">
																		<label style="font-weight: bold;" for="statut">Statut *</label>
																		<div class="form-control-wrap">
																			<select class="form-select form-control" id="statut" required name="statut">
																				@isset($ayantsdroits)
																					<option value="{{ $ayantsdroits->id }}">
																						{{ $ayantsdroits->statut == 'Tuteur' ? 'Tuteur ou tutrice' : $ayantsdroits->statut }}
																					</option>
																				@endisset
																				<option value="">
																				</option>
																				<option value="Conjoint">
																					@lang('Conjoint')
																				</option>
																				<option value="Enfant">
																					@lang('Enfant')
																				</option>
																				@if ($father->type_parent == 0)
																					<option value="Parent">
																						@lang('Parent')
																					</option>
																				@else
																					<option value="Tuteur">
																						@lang('Tuteur ou tutrice')
																					</option>
																				@endif
																				<option value="Tatrice">
																					@lang('Tatrice')
																				</option>
																				<option value="Bénéficiaire">
																					@lang('Bénéficiaire')
																				</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label style="font-weight: bold;" for="cni">Copie de
																			CNI</label>
																		<div class="form-control-wrap">
																			<div class="custom-file">
																				<input type="file" name="cni" id="cni" class="custom-file-input">
																				<label class="custom-file-label" id="lab_cni" for="cni">Choisir
																					un
																					fichier</label>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label style="font-weight: bold;" for="acte_naissance">Copie d'acte
																			de
																			naissance</label>
																		<div class="form-control-wrap">
																			<div class="custom-file">
																				<input type="file" name="acte_naissance" id="acte_naissance" class="custom-file-input">
																				<label class="custom-file-label" id="lab_acte_naissance" for="acte_naissance">Choisir
																					un
																					fichier</label>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-md-4">
																	<div class="form-group">
																		<label style="font-weight: bold;" for="certificat_vie">Copie de
																			certificat de vie</label>
																		<div class="form-control-wrap">
																			<div class="custom-file">
																				<input type="file" name="certificat_vie" id="certificat_vie" class="custom-file-input">
																				<label class="custom-file-label" id="lab_certificat_vie" for="certificat_vie">Choisir
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
														<button type="submit" class="btn btn-lg btn-primary btn-submit-ayantsdroits">Sauvegarder</button>
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
	  $('.btn-submit-ayantsdroits').attr('disabled', true);
	  e.preventDefault();
	  let id = $("#id").val();
	  let nom = $("#nom").val();
	  let prenom = $("#prenom").val();
	  let statut = $("#statut").val();
	  let cni = $("#cni")[0].files;
	  let acte_naissance = $("#acte_naissance")[0].files;
	  let certificat_vie = $("#certificat_vie")[0].files;
	  var formData = new FormData();
	  formData.append('id', id);
	  formData.append('cni', cni[0]);
	  formData.append('acte_naissance', acte_naissance[0]);
	  formData.append('certificat_vie', certificat_vie[0]);
	  formData.append('nom', nom);
	  formData.append('prenom', prenom);
	  formData.append('statut', statut);
	  $.ajax({
	   headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
	   },
	   url: "" + $('#formayantsdroits').attr('action'),
	   type: "" + $('#formayantsdroits').attr('method'),
	   dataType: 'json',
	   // data: {
	   //     id: id,
	   //     nom: nom,
	   //     prenom: prenom,
	   //     statut: statut,
	   //     cni: cni,
	   // },
	   data: formData,
	   contentType: false,
	   processData: false,
	   dataType: "json",
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
	     $('.btn-submit-ayantsdroits').attr('disabled', false);
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
	   {{ $father->type_parent }} == 0 ? '<option value="Parent">Parent</option>' :
	   '<option value="Tuteur">Tuteur ou tutrice</option>',
	   '<option value="Tatrice">Tatrice</option>',
	   '<option value="Bénéficiaire">Bénéficiaire</option>',
	  );
	  $("#cni").val('');
	  $("#acte_naissance").val('');
	  $("#certificat_vie").val('');
	  $("#lab_certificat_vie").text('Choisir un fichier ');
	  $("#lab_cni").text('Choisir un fichier ');
	  $("#lab_acte_naissance").text('Choisir un fichier ');
	@if (Route::currentRouteName() === 'ayantsdroits.edit')
		// history.pushState({}, null, "{{ route('membre.index') }}");
		// window.setTimeout('location.reload()', 1500);
		window.setTimeout(' history.back();', 1500);
	@endif
	  $('.btn-submit-ayantsdroits').attr('disabled', false);
	 }
	</script>
@endsection
