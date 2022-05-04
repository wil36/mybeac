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
						<div class="col-md-11">
							@if (session('status'))
								<br>
								<div class="alert alert-success alert-dismissible" role="alert">
									{{ session('status') }}
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
							@php
								$tabDate = explode('-', $dateDeRecherche);
							@endphp
							<h2 style="text-align: center;">
								{{ isset($tabDate[1]) && isset($tabDate[0])? DateTime::createFromFormat('Y-m', $dateDeRecherche)->format('M Y'): 'La date est le mauvais format' }}
							</h2>
							<div class="card">
								<div class="nk-block nk-block-lg">
									<div class="card card-preview">
										<div class="card-inner">
											<div class="table-responsive">
												<table class="nk-tb-list nk-tb-ulist" id="userList" data-auto-responsive="false">
													<thead>
														<tr class="nk-tb-item nk-tb-head">
															<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Matricule')</span>
															</th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Nom')</span>
															</th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Prenom')</span>
															</th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Sexe')</span>
															</th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Nationalité')</span>
															</th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Agence')</span>
															</th>
															{{-- <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Email')</span>
                                                            </th> --}}
															<th class="nk-tb-col"><span class="sub-text">@lang('Telephone')</span>
															</th>
															<th class="nk-tb-col"><span class="sub-text">@lang('Categorie')</span>
															</th>
															{{-- <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Date de naissance')</span>
                                                            </th> --}}
															{{-- <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Date de
                                                                    recrutement')</span>
                                                            </th>
                                                            <th class="nk-tb-col"><span
                                                                    class="sub-text">@lang('Date d\'hadésion')</span>
                                                            </th> --}}
															<th class="nk-tb-col"><span class="sub-text">@lang('Status')</span>
															</th>
															{{-- <th class="nk-tb-col"><span class="sub-text">Status</span>
                                                        </th> --}}
															<th class="nk-tb-col nk-tb-col-tools text-right"><span class="sub-text">Action</span></th>
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


	@if (config('app.locale') == 'fr')
		<script>
		 $(document).ready(function() {
		  $('#userList').DataTable({
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
		   ajax: "{{ route('cotisation.getUserDetailCotisationHistoriqueMensuel', $dateDeRecherche) }}",
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
		     "data": 'matricule',
		     "name": 'matricule',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'nom',
		     "name": 'nom',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'prenom',
		     "name": 'prenom',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'sexe',
		     "name": 'sexe',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'nationalité',
		     "name": 'nationalité',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'agence',
		     "name": 'agence',
		     "className": 'nk-tb-col '
		    },
		    // {
		    //     "data": 'email',
		    //     "name": 'email',
		    //     "className": 'nk-tb-col '
		    // },
		    {
		     "data": 'tel',
		     "name": 'tel',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'category',
		     "name": 'category',
		     "className": 'nk-tb-col'
		    },
		    // {
		    //     "data": 'dateNais',
		    //     "name": 'dateNais',
		    //     "className": 'nk-tb-col'
		    // },
		    // {
		    //     "data": 'dateRecru',
		    //     "name": 'dateRecru',
		    //     "className": 'nk-tb-col'
		    // },
		    // {
		    //     "data": 'dateHade',
		    //     "name": 'dateHade',
		    //     "className": 'nk-tb-col'
		    // },
		    {
		     "data": 'status',
		     "name": 'status',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'Actions',
		     "name": 'Actions',
		     "visible": false,
		     "orderable": false,
		     "serachable": false,
		     "className": 'nk-tb-col nk-tb-col-tools'
		    },
		   ]
		  });
		 });
		 $(".loader").addClass("d-none");
		</script>
	@else
		<script>
		 $(document).ready(function() {
		  $('#userList').DataTable({
		   processing: true,
		   serverSide: true,
		   autoWidth: false,
		   pageLength: 10,
		   paginate: true,
		   info: true,
		   language: {
		    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json",
		   },
		   buttons: [
		    'copy', 'excel', 'pdf'
		   ],
		   // scrollX: true,
		   // "order": [[ 0, "desc" ]],
		   ajax: "{{ route('cotisation.getUserDetailCotisationHistoriqueMensuel', $dateDeRecherche) }}",
		   order: [
		    [0, "desc"]
		   ], //or asc 
		   columns: [{
		     "data": 'updated_at',
		     "name": 'updated_at',
		     "visible": false,
		     "className": 'nk-tb-col nk-tb-col-check'
		    },
		    {
		     "data": 'matricule',
		     "name": 'matricule',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'nom',
		     "name": 'nom',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'prenom',
		     "name": 'prenom',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'sexe',
		     "name": 'sexe',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'nationalité',
		     "name": 'nationalité',
		     "className": 'nk-tb-col '
		    },
		    {
		     "data": 'agence',
		     "name": 'agence',
		     "className": 'nk-tb-col '
		    },
		    // {
		    //     "data": 'email',
		    //     "name": 'email',
		    //     "className": 'nk-tb-col '
		    // },
		    {
		     "data": 'tel',
		     "name": 'tel',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'category',
		     "name": 'category',
		     "className": 'nk-tb-col'
		    },
		    // {
		    //     "data": 'dateNais',
		    //     "name": 'dateNais',
		    //     "className": 'nk-tb-col'
		    // },
		    // {
		    //     "data": 'dateRecru',
		    //     "name": 'dateRecru',
		    //     "className": 'nk-tb-col'
		    // },
		    // {
		    //     "data": 'dateHade',
		    //     "name": 'dateHade',
		    //     "className": 'nk-tb-col'
		    // },
		    {
		     "data": 'status',
		     "name": 'status',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'Actions',
		     "name": 'Actions',
		     "visible": false,
		     "orderable": false,
		     "serachable": false,
		     "className": 'nk-tb-col nk-tb-col-tools'
		    },
		   ]
		  });
		 });
		 $(".loader").addClass("d-none");
		</script>
	@endif

	{{-- <script src="{{ mix('js/app.js') }}">
    </script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script> --}}
@endsection
