@extends('layouts.template')

@section('css')
@endsection

@section('contenu')
	<div class="nk-content">
		<div class="nk-block">
			<div class="row g-gs">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-11">
							@if (session('status'))
								<br>
								<div class="alert alert-success alert-dismissible" role="alert">
									{{ session('status') }}
								</div>
							@endif
							{{-- <div class="alert alert-info">{{ Session::get('message') }}</div> --}}

							@if (session('error'))
								<br>
								<div class="alert alert-success alert-dismissible" role="alert">
									{{ session('error') }}
								</div>
							@endif
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
							<div class="d-flex justify-content-center loader flex-row-reverse">

								<div class="spinner-grow text-success loader" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<div class="spinner-grow text-success loader" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<div class="spinner-grow text-success loader" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<div class="spinner-grow text-success loader" role="status">
									<span class="sr-only">Loading...</span>
								</div>
								<div class="spinner-grow text-success loader" role="status">
									<span class="sr-only">Loading...</span>
								</div>
							</div>
							<a class="btn btn-primary" href="{{ route('emprunt.impressionListDesEmpruntsValiderParLaMutuelle') }}"><em
									class="icon ni ni-printer"></em></a>
							<div class="card">
								<div class="nk-block nk-block-lg">
									{{-- <button class="btn btn-primary right" style="position: relative">Test</button> --}}
									<div class="card card-preview">
										<div class="card-inner">
											<div class="table-responsive">
												<table class="nk-tb-list nk-tb-ulist" id="typeprestationList" data-auto-responsive="true">
													<thead>
														<tr class="nk-tb-item nk-tb-head">
															<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Membre')</span>
															<th class="nk-tb-col"><span class="sub-text">@lang('Matricule')</span>
															<th class="nk-tb-col"><span class="sub-text">@lang('Type d\'emprunt')</span>
															<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span></th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Date de fin')</span></th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Montant (FCFA)')</span></th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Commission (FCFA)')</span></th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Etat du dossier d\'emprunt')</span></th>
															<th class="nk-tb-col nk-tb-col-tools text-right"><span class="sub-text">@lang('Action')</span></th>
														</tr>
													</thead>
													<tbody></tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection


@section('script')
	<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

	<script>
		$(document).on('click', '.refuse-data-emprunt', function(e) {
			e.preventDefault();
			var id = $(this).attr('data_id');
			Swal.fire({
				title: 'Voulez-vous vraiment refuser ?',
				text: "Vous êtes en train de vouloir refuser cet emprunt ! Assurez-vous que c'est bien la bonne !",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Oui',
				cancelButtonText: 'Annuler',
			}).then((result) => {
				if (result.value) {
					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						url: "{{ route('emprunt.refuserLeDossier') }}",
						type: "POST",
						dataType: 'json',
						data: {
							id: id,
						},
						success: function(data) {
							if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
								Swal.fire(
									'Dossier refuser!',
									data.success,
									'success'
								);
								window.setTimeout('location.reload()', 1500);
							} else {
								Swal.fire(
									'Erreur!',
									data.error,
									'error'
								)
							}
							$("html, body").animate({
								scrollTop: 0
							}, "slow");
						},
						error: function(data) {
							console.log(data);
							Swal.fire('Une erreur s\'est produite.',
								'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
								'error');

						}
					});
				}
			});
		});

		$(document).on('click', '.emprunt-data', function(e) {
			e.preventDefault();
			var id = $(this).attr('data_id');
			Swal.fire({
				title: 'Voulez-vous vraiment rembourser ?',
				text: "Vous êtes en train de vouloir rembourser cet emprunt ! Assurez-vous que c'est bien le bon !",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Oui',
				cancelButtonText: 'Annuler',
			}).then((result) => {
				if (result.value) {
					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						url: "{{ route('emprunt.rembourserLeDossier') }}",
						type: "POST",
						dataType: 'json',
						data: {
							id: id,
						},
						success: function(data) {
							if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
								Swal.fire(
									'Emprunt rembourser !',
									data.success,
									'success'
								);
								window.setTimeout('location.reload()', 1500);
							} else {
								console.log(data);
								Swal.fire(
									'Erreur!',
									data.error,
									'error'
								)
							}
							$("html, body").animate({
								scrollTop: 0
							}, "slow");
						},
						error: function(data) {
							console.log(data);
							Swal.fire('Une erreur s\'est produite.',
								'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
								'error');

						}
					});
				}
			});
		});
	</script>

	<script>
		$(document).ready(function() {
			$('#typeprestationList').DataTable({
				processing: true,
				serverSide: true,
				autoWidth: false,
				pageLength: 10,
				paginate: true,
				info: true,
				language: {
					"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json",
					"sEmptyTable": "Aucune donnée disponible dans le tableau",
					"sInfo": "Affichage des éléments _START_ à _END_ sur _TOTAL_ éléments",
					"sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
					"sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
					"sInfoPostFix": "",
					"sInfoThousands": ",",
					"sLengthMenu": "Afficher _MENU_ éléments",
					"sLoadingRecords": "Chargement...",
					"sProcessing": "Traitement...",
					"sSearch": "Rechercher :",
					"sZeroRecords": "Aucun élément correspondant trouvé",
					"oPaginate": {
						"sFirst": "Premier",
						"sLast": "Dernier",
						"sNext": "Suivant",
						"sPrevious": "Précédent"
					},
					"oAria": {
						"sSortAscending": ": activer pour trier la colonne par ordre croissant",
						"sSortDescending": ": activer pour trier la colonne par ordre décroissant"
					},
					"select": {
						"rows": {
							"_": "%d lignes sélectionnées",
							"0": "Aucune ligne sélectionnée",
							"1": "1 ligne sélectionnée"
						}
					}
				},
				buttons: [
					'copy', 'excel', 'pdf'
				],
				ajax: "{{ route('emprunt.getListOfEmpruntWhoIsValidateByTheMutualAjax') }}",
				order: [
					[0, "desc"]
				],
				columns: [{
						"data": 'updated_at',
						"name": 'updated_at',
						"visible": false,
						"className": 'nk-tb-col nk-tb-col-check'
					},
					{
						"data": 'membre',
						"name": 'membre',
						"className": 'nk-tb-col'
					},
					{
						"data": 'matricule',
						"name": 'matricule',
						"className": 'nk-tb-col'
					},
					{
						"data": 'type',
						"name": 'type',
						"className": 'nk-tb-col'
					},
					{
						"data": 'date',
						"name": 'date',
						"className": 'nk-tb-col'
					},
					{
						"data": 'date_fin',
						"name": 'date_fin',
						"className": 'nk-tb-col'
					},
					{
						"data": 'montant',
						"name": 'montant',
						"className": 'nk-tb-col'
					},
					{
						"data": 'commission',
						"name": 'commission',
						"className": 'nk-tb-col'
					},
					{
						"data": 'status',
						"name": 'status',
						"className": 'nk-tb-col'
					},
					{
						"data": 'Actions',
						"name": 'Actions',
						"orderable": false,
						"serachable": false,
						"className": 'nk-tb-col nk-tb-col-tools'
					},
				]
			});
		});
		$(".loader").addClass("d-none");
	</script>
@endsection
