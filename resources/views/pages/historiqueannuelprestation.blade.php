@extends('layouts.template')

@section('css')
	{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css"> --}}
@endsection

@section('contenu')
	@csrf
	<div class="nk-content">
		<div class="nk-block">
			<div class="row g-gs">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-11" style="margin-top: 80px">
							@if (session('status'))
								<br>
								<div class="alert alert-success alert-dismissible" role="alert">
									{{ session('status') }}
								</div>
							@endif
							@if (count($errors) > 0)
								<br>
								<div class="alert alert-danger alert-dismissible" role="alert" id="alert-javascript">
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
							<a class="btn btn-primary" href="{{ route('prestation.impressionListHistoriqueAnnuelPrestation') }}"><em
									class="icon ni ni-printer"></em></a>
							<div class="card">
								<div class="nk-block nk-block-lg">
									<div class="card card-preview">
										<div class="card-inner">
											<div class="table-responsive">
												<table class="nk-tb-list nk-tb-ulist" id="cotisationListHistoriqueAnnuel" data-auto-responsive="false">
													<thead>
														<tr class="nk-tb-item nk-tb-head">
															<th class="nk-tb-col"><span class="sub-text">@lang('Année')</span>
															</th>
															<th class="nk-tb-col nk-tb-col-tools text-left"><span class="sub-text">@lang('Montant')</span>
															</th>
															<th class="nk-tb-col nk-tb-col-tools text-right"><span class="sub-text"></span>
															</th>
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
		$(document).ready(function() {
			$('#cotisationListHistoriqueAnnuel').DataTable({
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
				ajax: "{{ route('prestation.historique.annuel') }}",
				order: [
					[0, "desc"]
				],
				columns: [{
						"data": 'annee',
						"name": 'annee',
						"className": 'nk-tb-col '
					},
					{
						"data": 'montant',
						"name": 'montant',
						"className": 'nk-tb-col '
					},
					{
						"data": 'Actions',
						"name": 'Actions',
						"visible": false,
						"orderable": false,
						"serachable": false,
						"className": 'nk-tb-col '
					},
				]
			});
		});
		$(".loader").addClass("d-none");
	</script>
@endsection
