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
								{{-- @dd($data->date) --}}
								<div class="card">
									<div class="card-inner">
										<p>
											<b>Date : </b><br>
											{{ Carbon\Carbon::parse($data->date)->format('d-m-Y') }}

											<br><br><b>Expediteur : </b><br>
											{{ $data->expediteur == 'Mutuelle' ? 'Mutuelle' : $data->membre->nom . ' ' . $data->membre->prenom }}

											<br><br><b>Message</b><br>
											{{ $data->description }}

											@if ($data->link_file != null || $data->link_file != '')
												<br><br><b>Telecharger le fichier</b><br>
												<a href="{{ route('messagerie.downloadFile', $data->link_file) }}">Cliquez-ici</a>
											@endif
										</p>
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
