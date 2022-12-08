@extends('layouts.template')

@if (Auth::user()->role == 'admin')
	@section('contenu')
		<div class="nk-content">
			<div class="nk-block">
				<div class="row g-gs">
					<div class="container-fluid">
						<div class="row g-gs">
							<div class="col-md-12">
								<h4>@lang('Etat des caisses')</h4>
							</div>
						</div>
						<div class="row g-gs">
							<div class="col-xxl-6 col-sm-6">
								<div class="card">
									<div class="nk-ecwg nk-ecwg6">
										<div class="card-inner">
											<div class="card-title-group">
												<div class="card-title">
													<h6 class="title">@lang('Montant caise principal')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">Solde : {{ $principal }} FCFA</div>
													<div class="nk-ecwg6-ck">
														<canvas class="ecommerce-line-chart-s3" id="todayOrders"></canvas>
													</div>
												</div>
												{{-- <div class="info"><span class="change up text-danger"><em
															class="icon ni ni-arrow-long-up"></em>4.63%</span><span> vs. last
														week</span></div> --}}
											</div>
										</div><!-- .card-inner -->
									</div><!-- .nk-ecwg -->
								</div><!-- .card -->
							</div><!-- .col -->
							<div class="col-xxl-6 col-sm-6">
								<div class="card">
									<div class="nk-ecwg nk-ecwg6">
										<div class="card-inner">
											<div class="card-title-group">
												<div class="card-title">
													<h6 class="title">@lang('Montants empruntés')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">Solde : 0 FCFA</div>
													<div class="nk-ecwg6-ck">
														<canvas class="ecommerce-line-chart-s3" id="todayRevenue"></canvas>
													</div>
												</div>
												{{-- <div class="info"><span class="change up text-danger"><em
															class="icon ni ni-arrow-long-up"></em>4.63%</span><span> vs. last
														week</span></div> --}}
											</div>
										</div><!-- .card-inner -->
									</div><!-- .nk-ecwg -->
								</div><!-- .card -->
							</div><!-- .col -->
						</div>
					</div>
				</div>
				<br>
				{{-- <div class="row g-gs">
					<div class="container-fluid">
						<div class="row g-gs">
							<div class="col-md-12">
								<h4>@lang('Etat des autres caisses')</h4>
							</div>
						</div>
						<div class="row g-gs">
							<div class="col-xxl-3 col-sm-6">
								<div class="card">
									<div class="nk-ecwg nk-ecwg6">
										<div class="card-inner">
											<div class="card-title-group">
												<div class="card-title">
													<h6 class="title">@lang('Solde')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">{{ $quantine }} FCFA</div>
													<div class="nk-ecwg6-ck">
														<canvas class="ecommerce-line-chart-s3" id="todayRevenue"></canvas>
													</div>
												</div>
											</div>
										</div><!-- .card-inner -->
									</div><!-- .nk-ecwg -->
								</div><!-- .card -->
							</div><!-- .col -->
							<div class="col-xxl-3 col-sm-6">
								<div class="card">
									<div class="nk-ecwg nk-ecwg6">
										<div class="card-inner">
											<div class="card-title-group">
												<div class="card-title">
													<h6 class="title">>@lang('Solde')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">{{ $emprunt }} FCFA</div>
													<div class="nk-ecwg6-ck">
														<canvas class="ecommerce-line-chart-s3" id="todayCustomers"></canvas>
													</div>
												</div>
											</div>
										</div><!-- .card-inner -->
									</div><!-- .nk-ecwg -->
								</div><!-- .card -->
							</div><!-- .col -->
						</div>
					</div>
				</div> --}}
			</div>
		</div>
	@endsection

	@section('script')
		<script>
			var todayOrders = {
				labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM",
					"12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"
				],
				dataUnit: 'Orders',
				lineTension: .3,
				datasets: [{
					label: "Orders",
					color: "#854fff",
					background: "transparent",
					data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
				}]
			};
			var todayRevenue = {
				labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM",
					"12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"
				],
				dataUnit: 'Orders',
				lineTension: .3,
				datasets: [{
					label: "Revenue",
					color: "#33d895",
					background: "transparent",
					data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
				}]
			};
			var todayCustomers = {
				labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM",
					"12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"
				],
				dataUnit: 'Orders',
				lineTension: .3,
				datasets: [{
					label: "Customers",
					color: "#ff63a5",
					background: "transparent",
					data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
				}]
			};
			var todayVisitors = {
				labels: ["12AM - 02AM", "02AM - 04AM", "04AM - 06AM", "06AM - 08AM", "08AM - 10AM", "10AM - 12PM",
					"12PM - 02PM", "02PM - 04PM", "04PM - 06PM", "06PM - 08PM", "08PM - 10PM", "10PM - 12PM"
				],
				dataUnit: 'Orders',
				lineTension: .3,
				datasets: [{
					label: "Visitors",
					color: "#559bfb",
					background: "transparent",
					data: [92, 105, 125, 85, 110, 106, 131, 105, 110, 131, 105, 110]
				}]
			};

			function ecommerceLineS3(selector, set_data) {
				var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s3');
				$selector.each(function() {
					var $self = $(this),
						_self_id = $self.attr('id'),
						_get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

					var selectCanvas = document.getElementById(_self_id).getContext("2d");
					var chart_data = [];

					for (var i = 0; i < _get_data.datasets.length; i++) {
						chart_data.push({
							label: _get_data.datasets[i].label,
							tension: _get_data.lineTension,
							backgroundColor: _get_data.datasets[i].background,
							borderWidth: 2,
							borderColor: _get_data.datasets[i].color,
							pointBorderColor: 'transparent',
							pointBackgroundColor: 'transparent',
							pointHoverBackgroundColor: "#fff",
							pointHoverBorderColor: _get_data.datasets[i].color,
							pointBorderWidth: 2,
							pointHoverRadius: 4,
							pointHoverBorderWidth: 2,
							pointRadius: 4,
							pointHitRadius: 4,
							data: _get_data.datasets[i].data
						});
					}

					var chart = new Chart(selectCanvas, {
						type: 'line',
						data: {
							labels: _get_data.labels,
							datasets: chart_data
						},
						options: {
							legend: {
								display: _get_data.legend ? _get_data.legend : false,
								rtl: NioApp.State.isRTL,
								labels: {
									boxWidth: 12,
									padding: 20,
									fontColor: '#6783b8'
								}
							},
							maintainAspectRatio: false,
							tooltips: {
								enabled: true,
								rtl: NioApp.State.isRTL,
								callbacks: {
									title: function title(tooltipItem, data) {
										return false;
									},
									label: function label(tooltipItem, data) {
										return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem[
											'index']] + ' ' + _get_data.dataUnit;
									}
								},
								backgroundColor: '#1c2b46',
								titleFontSize: 8,
								titleFontColor: '#fff',
								titleMarginBottom: 4,
								bodyFontColor: '#fff',
								bodyFontSize: 8,
								bodySpacing: 4,
								yPadding: 6,
								xPadding: 6,
								footerMarginTop: 0,
								displayColors: false
							},
							scales: {
								yAxes: [{
									display: false,
									ticks: {
										beginAtZero: false,
										fontSize: 12,
										fontColor: '#9eaecf',
										padding: 0
									},
									gridLines: {
										color: NioApp.hexRGB("#526484", .2),
										tickMarkLength: 0,
										zeroLineColor: NioApp.hexRGB("#526484", .2)
									}
								}],
								xAxes: [{
									display: false,
									ticks: {
										fontSize: 12,
										fontColor: '#9eaecf',
										source: 'auto',
										padding: 0,
										reverse: NioApp.State.isRTL
									},
									gridLines: {
										color: "transparent",
										tickMarkLength: 0,
										zeroLineColor: NioApp.hexRGB("#526484", .2),
										offsetGridLines: true
									}
								}]
							}
						}
					});
				});
			} // init chart


			NioApp.coms.docReady.push(function() {
				ecommerceLineS3();
			});
		</script>
	@endsection
@endif
