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
								<div class="row">
									<div class="col-md-12">
										<div class="card">
											<div class="card-inner">
												<ul class="nav nav-tabs">
													<li class="nav-item">
														<a class="nav-link active" data-toggle="tab" href="#tabItem1">Bridge Loan</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" id="tabItem" data-toggle="tab" href="#tabItem2">Bridge Loan Immo</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab" href="#tabItem3">@lang('Back to Back Loan')</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab" href="#tabItem4">@lang('Avance sur Salaire')</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab" href="#tabItem5">@lang('Avance sur Gratification')</a>
													</li>
												</ul>
												<div class="tab-content">
													<div class="tab-pane active" id="tabItem1">
														<a class="btn btn-primary" href="{{ route('emprunt.impressionListDesEmpruntsBL') }}"><em
																class="icon ni ni-printer"></em></a>
														<h3 class="text-center">Recapitulatif Bridge Loan</h3>
														<div class="table-responsive">
															<table class="nk-tb-list nk-tb-ulist" id="historiqueEmpruntBL" data-auto-responsive="true">
																<thead>
																	<tr class="nk-tb-item nk-tb-head">
																		<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Type d\'emprunt')</span>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date de fin')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant (FCFA)')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant commission (FCFA)')</span></th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table>
															<br><br>
															<h5>Total : {{ number_format(abs($BLtotal), 0, ',', ' ') }} FCFA</h5>
															<h5>Montant Total des commissions : {{ number_format(abs($BLtotalCommission), 0, ',', ' ') }} FCFA</h5>
														</div>
													</div>
													<div class="tab-pane" id="tabItem2">
														<a class="btn btn-primary" href="{{ route('emprunt.impressionListDesEmpruntsBLI') }}"><em
																class="icon ni ni-printer"></em></a>
														<h3 class="text-center">Recapitulatif Bridge Loan Immo</h3>
														<div class="table-responsive">
															<table class="nk-tb-list nk-tb-ulist" id="historiqueEmpruntBLI" data-auto-responsive="true">
																<thead>
																	<tr class="nk-tb-item nk-tb-head">
																		<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Type d\'emprunt')</span>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date de fin')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant (FCFA)')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant commission (FCFA)')</span></th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table><br><br>
															<h5>Total : {{ number_format(abs($BLItotal), 0, ',', ' ') }} FCFA</h5>
															<h5>Montant Total des commissions : {{ number_format(abs($BLItotalCommission), 0, ',', ' ') }} FCFA</h5>
														</div>
													</div>
													<div class="tab-pane" id="tabItem3">
														<a class="btn btn-primary" href="{{ route('emprunt.impressionListDesEmpruntsBBL') }}"><em
																class="icon ni ni-printer"></em></a>
														<h3 class="text-center">Recapitulatif Back to Back Loan</h3>
														<div class="table-responsive">
															<table class="nk-tb-list nk-tb-ulist" id="historiqueEmpruntBBL" data-auto-responsive="true">
																<thead>
																	<tr class="nk-tb-item nk-tb-head">
																		<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Type d\'emprunt')</span>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date de fin')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant (FCFA)')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant commission (FCFA)')</span></th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table><br><br>
															<h5>Total : {{ number_format(abs($BBLtotal), 0, ',', ' ') }} FCFA</h5>
															<h5>Montant Total des commissions : {{ number_format(abs($BBLtotalCommission), 0, ',', ' ') }} FCFA</h5>
														</div>
													</div>
													<div class="tab-pane" id="tabItem4">
														<a class="btn btn-primary" href="{{ route('emprunt.impressionListDesEmpruntsASS') }}"><em
																class="icon ni ni-printer"></em></a>
														<h3 class="text-center">Recapitulatif Avance sur Salaire</h3>
														<div class="table-responsive">
															<table class="nk-tb-list nk-tb-ulist" id="historiqueEmpruntASS" data-auto-responsive="true">
																<thead>
																	<tr class="nk-tb-item nk-tb-head">
																		<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Type d\'emprunt')</span>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date de fin')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant (FCFA)')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant commission (FCFA)')</span></th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table><br><br>
															<h5>Total : {{ number_format(abs($ASStotal), 0, ',', ' ') }} FCFA</h5>
															<h5>Montant Total des commissions : {{ number_format(abs($ASStotalCommission), 0, ',', ' ') }} FCFA</h5>
														</div>
													</div>
													<div class="tab-pane" id="tabItem5">
														<a class="btn btn-primary" href="{{ route('emprunt.impressionListDesEmpruntsASG') }}"><em
																class="icon ni ni-printer"></em></a>
														<h3 class="text-center">Recapitulatif Avance sur Gratification</h3>
														<div class="table-responsive">
															<table class="nk-tb-list nk-tb-ulist" id="historiqueEmpruntASG" data-auto-responsive="true">
																<thead>
																	<tr class="nk-tb-item nk-tb-head">
																		<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Type d\'emprunt')</span>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Date de fin')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant (FCFA)')</span></th>
																		<th class="nk-tb-col"><span class="sub-text">@lang('Montant commission (FCFA)')</span></th>
																	</tr>
																</thead>
																<tbody></tbody>
															</table><br><br>
															<h5>Total : {{ number_format(abs($ASGtotal), 0, ',', ' ') }} FCFA</h5>
															<h5>Montant Total des commissions : {{ number_format(abs($ASGtotalCommission), 0, ',', ' ') }} FCFA</h5>
														</div>
													</div>
												</div>
											</div>
										</div>
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
	<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>

	<script>
		$(document).ready(function() {
			$('#historiqueEmpruntBL').DataTable({
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
				ajax: "{{ route('emprunt.getHistoriqueBLEmpruntAjax') }}",
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
						"data": 'date_de_fin',
						"name": 'date_de_fin',
						"className": 'nk-tb-col'
					},
					{
						"data": 'montant',
						"name": 'montant',
						"className": 'nk-tb-col'
					},
					{
						"data": 'montant_commission',
						"name": 'montant_commission',
						"className": 'nk-tb-col'
					},
					{
						"data": 'status',
						"name": 'status',
						"className": 'nk-tb-col'
					},
				]
			});
		});
		$('#historiqueEmpruntBLI').DataTable({
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
			ajax: "{{ route('emprunt.getHistoriqueBLIEmpruntAjax') }}",
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
					"data": 'date_de_fin',
					"name": 'date_de_fin',
					"className": 'nk-tb-col'
				},
				{
					"data": 'montant',
					"name": 'montant',
					"className": 'nk-tb-col'
				},
				{
						"data": 'montant_commission',
						"name": 'montant_commission',
						"className": 'nk-tb-col'
				},
				{
						"data": 'status',
						"name": 'status',
						"className": 'nk-tb-col'
					},
			]
		});
		$('#historiqueEmpruntBBL').DataTable({
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
			ajax: "{{ route('emprunt.getHistoriqueBBLEmpruntAjax') }}",
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
					"data": 'date_de_fin',
					"name": 'date_de_fin',
					"className": 'nk-tb-col'
				},
				{
					"data": 'montant',
					"name": 'montant',
					"className": 'nk-tb-col'
				},
				{
						"data": 'montant_commission',
						"name": 'montant_commission',
						"className": 'nk-tb-col'
				},
				{
						"data": 'status',
						"name": 'status',
						"className": 'nk-tb-col'
					},
			]
		});
		$('#historiqueEmpruntASS').DataTable({
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
			ajax: "{{ route('emprunt.getHistoriqueASSEmpruntAjax') }}",
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
					"data": 'date_de_fin',
					"name": 'date_de_fin',
					"className": 'nk-tb-col'
				},
				{
					"data": 'montant',
					"name": 'montant',
					"className": 'nk-tb-col'
				},
				{
						"data": 'montant_commission',
						"name": 'montant_commission',
						"className": 'nk-tb-col'
				},
				{
						"data": 'status',
						"name": 'status',
						"className": 'nk-tb-col'
					},
			]
		});
		$('#historiqueEmpruntASG').DataTable({
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
			ajax: "{{ route('emprunt.getHistoriqueASGEmpruntAjax') }}",
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
					"data": 'date_de_fin',
					"name": 'date_de_fin',
					"className": 'nk-tb-col'
				},
				{
					"data": 'montant',
					"name": 'montant',
					"className": 'nk-tb-col'
				},
				{
						"data": 'montant_commission',
						"name": 'montant_commission',
						"className": 'nk-tb-col'
				},
				{
						"data": 'status',
						"name": 'status',
						"className": 'nk-tb-col'
					},
			]
		});
		$(".loader").addClass("d-none");
	</script>
@endsection
