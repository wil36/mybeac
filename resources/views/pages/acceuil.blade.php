@extends('layouts.template')

@if (Auth::user()->role == 'admin')
	@section('contenu')
		<div class="nk-content">
			<div class="nk-block">
				<div class="row g-gs">
					<div class="container-fluid">
						<div class="row g-gs">
							<div class="col-xxl-6 col-sm-6">
								<div class="card">
									<div class="nk-ecwg nk-ecwg6">
										<div class="card-inner">
											<div class="card-title-group">
												<div class="card-title">
													<h6 class="title">@lang('Nombre total de membre dans la mutuelle')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">{{ $nbmembre }}</div>
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
													<h6 class="title">@lang('Montant total des dons')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">{{ $totalDons }} FCFA</div>
													<div class="nk-ecwg6-ck">
														<canvas class="ecommerce-line-chart-s3" id="todayVisitors"></canvas>
													</div>
												</div>
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
													<h6 class="title">@lang('Montant global des cotisations')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">{{ $totalcotisationglobal }} FCFA</div>
													<div class="nk-ecwg6-ck">
														<canvas class="ecommerce-line-chart-s3" id="todayRevenue"></canvas>
													</div>
												</div>
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
													<h6 class="title">@lang('Montant global des prestations')</h6>
												</div>
											</div>
											<div class="data">
												<div class="data-group">
													<div class="amount">{{ $totalprestationglobal }} FCFA</div>
													<div class="nk-ecwg6-ck">
														<canvas class="ecommerce-line-chart-s3" id="todayCustomers"></canvas>
													</div>
												</div>
											</div>
										</div><!-- .card-inner -->
									</div><!-- .nk-ecwg -->
								</div><!-- .card -->
							</div><!-- .col -->
							<div class="col-xxl-12">
								<div class="card card-full">
									<div class="nk-ecwg nk-ecwg8 h-100">
										<div class="card-inner">
											<div class="card-title-group mb-3">
												<div class="card-title">
													<h6 class="title">Visualisation graphique des cotisations et
														prestations ({{ Carbon\Carbon::now()->isoFormat('Y') }})</h6>
												</div>

											</div>
											<ul class="nk-ecwg8-legends">
												<li>
													<div class="title">
														<span class="dot dot-lg sq" data-bg="#0fac81"></span>
														<span>Cotisations</span>
													</div>
												</li>
												<li>
													<div class="title">
														<span class="dot dot-lg sq" data-bg="#e85347"></span>
														<span>Prestations</span>
													</div>
												</li>
											</ul>
											<div class="nk-ecwg8-ck">
												<canvas class="ecommerce-line-chart-s4" id="salesStatistics"></canvas>
											</div>
											<div class="chart-label-group pl-5">
												<div class="chart-label">
													{{ Carbon\Carbon::now()->startOfYear()->isoFormat('MMMM') }}
												</div>
												<div class="chart-label">
													{{ Carbon\Carbon::now()->endOfYear()->isoFormat('MMMM') }}
												</div>
											</div>
										</div><!-- .card-inner -->
									</div>
								</div><!-- .card -->
							</div><!-- .col -->
							{{-- <div class="col-xxl-6 col-md-6">
                                <div class="overflow-hidden card card-full">
                                    <div class="nk-ecwg nk-ecwg7 h-100">
                                        <div class="card-inner flex-grow-1">
                                            <div class="mb-4 card-title-group">
                                                <div class="card-title">
                                                    <h6 class="title">Statistiques des livraisons terminées
                                                    </h6>
                                                </div>
                                            </div>
                                            <div class="nk-ecwg7-ck">
                                                <canvas class="ecommerce-doughnut-s1" id="orderStatistics"></canvas>
                                            </div>
                                            <ul class="nk-ecwg7-legends">
                                                <li>
                                                    <div class="title">
                                                        <span class="dot dot-lg sq" data-bg="#0fac81"></span>
                                                        <span>Livraisons Effectuées par Lykati
                                                            ({{ $nbLyliv }})</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="title">
                                                        <span class="dot dot-lg sq" data-bg="#e85347"></span>
                                                        <span>Livraisons Effectuées par les Tiers
                                                            ({{ $nbTilivT }})</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div><!-- .card-inner -->
                                    </div>
                                </div><!-- .card -->
                            </div><!-- .col --> --}}
						</div>

					</div>
				</div>
			</div>
		</div>
	@endsection

	@section('script')
		<script>
		 <?php
		echo 'var tabMontantCotisation = ' . json_encode($listeCotisation) . ';';
		echo 'var tabMontantPrestation = ' . json_encode($listePrestation) . ';';
		?>
		 var tabMontantCotisationArray = [];
		 var tabMontantPrestationArray = [];
		 for (let i = 0; i < 12; i++) {
		  tabMontantCotisationArray.push(0);
		  tabMontantPrestationArray.push(0);
		 }
		 for (var i = 0; i < tabMontantCotisation.length; i++) {
		  tabMontantCotisationArray[tabMontantCotisation[i]['dat'] - 1] = tabMontantCotisation[i]['nombre'];
		 }
		 for (var i = 0; i < tabMontantPrestation.length; i++) {
		  tabMontantPrestationArray[tabMontantPrestation[i]['dat'] - 1] = tabMontantPrestation[i]['nombre'];
		 }
		 var salesStatistics = {
		  labels: ["Janvier", "Fevrier", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout", "Septembre", "Octobre",
		   "Novembre",
		   "Décembre"
		  ],
		  dataUnit: 'People',
		  lineTension: .0,
		  datasets: [{
		   label: "Total orders",
		   color: "#0fac81",
		   dash: 0,
		   background: "transparent",
		   data: tabMontantCotisationArray,
		  }, {
		   label: "Canceled orders",
		   color: "#e85347",
		   dash: 0,
		   background: "transparent",
		   data: tabMontantPrestationArray
		  }, ]
		 };

		 function ecommerceLineS4(selector, set_data) {
		  var $selector = selector ? $(selector) : $('.ecommerce-line-chart-s4');
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
		     borderDash: _get_data.datasets[i].dash,
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
		        return data['labels'][tooltipItem[0]['index']];
		       },
		       label: function label(tooltipItem, data) {
		        return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem[
		         'index']];
		       }
		      },
		      backgroundColor: '#1c2b46',
		      titleFontSize: 13,
		      titleFontColor: '#fff',
		      titleMarginBottom: 6,
		      bodyFontColor: '#fff',
		      bodyFontSize: 12,
		      bodySpacing: 4,
		      yPadding: 10,
		      xPadding: 10,
		      footerMarginTop: 0,
		      displayColors: false
		     },
		     scales: {
		      yAxes: [{
		       display: true,
		       stacked: _get_data.stacked ? _get_data.stacked : false,
		       position: NioApp.State.isRTL ? "right" : "left",
		       ticks: {
		        beginAtZero: true,
		        fontSize: 11,
		        fontColor: '#9eaecf',
		        padding: 10,
		        callback: function callback(value, index, values) {
		         return value;
		        },
		        min: 0,
		        stepSize: 3000
		       },
		       gridLines: {
		        color: NioApp.hexRGB("#526484", .2),
		        tickMarkLength: 0,
		        zeroLineColor: NioApp.hexRGB("#526484", .2)
		       }
		      }],
		      xAxes: [{
		       display: false,
		       stacked: _get_data.stacked ? _get_data.stacked : false,
		       ticks: {
		        fontSize: 9,
		        fontColor: '#9eaecf',
		        source: 'auto',
		        padding: 10,
		        reverse: NioApp.State.isRTL
		       },
		       gridLines: {
		        color: "transparent",
		        tickMarkLength: 0,
		        zeroLineColor: 'transparent'
		       }
		      }]
		     }
		    }
		   });
		  });
		 } // init chart


		 NioApp.coms.docReady.push(function() {
		  ecommerceLineS4();
		 });


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


		 // var trafficSources = {
		 //     labels: ["Organic Search", "Social Media", "Referrals", "Others"],
		 //     dataUnit: 'People',
		 //     legend: false,
		 //     datasets: [{
		 //         borderColor: "#fff",
		 //         background: ["#b695ff", "#b8acff", "#ffa9ce", "#f9db7b"],
		 //         data: [4305, 859, 482, 138]
		 //     }]
		 // };
		 // var orderStatistics = {
		 //     labels: ["Livré par Lykati", "Livré par les tiers"],
		 //     dataUnit: 'Livraison(s)',
		 //     legend: false,
		 //     datasets: [{
		 //         borderColor: "#fff",
		 //         background: ["#0fac81", "#e85347"],
		 //         data: []
		 //     }]
		 // };

		 // function ecommerceDoughnutS1(selector, set_data) {
		 //     var $selector = selector ? $(selector) : $('.ecommerce-doughnut-s1');
		 //     $selector.each(function() {
		 //         var $self = $(this),
		 //             _self_id = $self.attr('id'),
		 //             _get_data = typeof set_data === 'undefined' ? eval(_self_id) : set_data;

		 //         var selectCanvas = document.getElementById(_self_id).getContext("2d");
		 //         var chart_data = [];

		 //         for (var i = 0; i < _get_data.datasets.length; i++) {
		 //             chart_data.push({
		 //                 backgroundColor: _get_data.datasets[i].background,
		 //                 borderWidth: 2,
		 //                 borderColor: _get_data.datasets[i].borderColor,
		 //                 hoverBorderColor: _get_data.datasets[i].borderColor,
		 //                 data: _get_data.datasets[i].data
		 //             });
		 //         }

		 //         var chart = new Chart(selectCanvas, {
		 //             type: 'doughnut',
		 //             data: {
		 //                 labels: _get_data.labels,
		 //                 datasets: chart_data
		 //             },
		 //             options: {
		 //                 legend: {
		 //                     display: _get_data.legend ? _get_data.legend : false,
		 //                     rtl: NioApp.State.isRTL,
		 //                     labels: {
		 //                         boxWidth: 12,
		 //                         padding: 20,
		 //                         fontColor: '#6783b8'
		 //                     }
		 //                 },
		 //                 rotation: -1.5,
		 //                 cutoutPercentage: 70,
		 //                 maintainAspectRatio: false,
		 //                 tooltips: {
		 //                     enabled: true,
		 //                     rtl: NioApp.State.isRTL,
		 //                     callbacks: {
		 //                         title: function title(tooltipItem, data) {
		 //                             return data['labels'][tooltipItem[0]['index']];
		 //                         },
		 //                         label: function label(tooltipItem, data) {
		 //                             return data.datasets[tooltipItem.datasetIndex]['data'][tooltipItem[
		 //                                 'index']] + ' ' + _get_data.dataUnit;
		 //                         }
		 //                     },
		 //                     backgroundColor: '#1c2b46',
		 //                     titleFontSize: 13,
		 //                     titleFontColor: '#fff',
		 //                     titleMarginBottom: 6,
		 //                     bodyFontColor: '#fff',
		 //                     bodyFontSize: 12,
		 //                     bodySpacing: 4,
		 //                     yPadding: 10,
		 //                     xPadding: 10,
		 //                     footerMarginTop: 0,
		 //                     displayColors: false
		 //                 }
		 //             }
		 //         });
		 //     });
		 // } // init chart


		 // NioApp.coms.docReady.push(function() {
		 //     ecommerceDoughnutS1();
		 // });
		</script>
	@endsection
@else
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
									<div class="col-md-12 text-center" style="margin-bottom: 30px;">
										<h2>Information sur le compte de
											{{ $membre->sexe == 'Masculin' ? ' M. ' : ' Mme ' }}
											{{ Str::upper($membre->nom) . ' ' . ucfirst(trans($membre->prenom)) }}</h2>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class='user-card user-card-s2'>
												<div class='user-avatar-lg bg-primary d-flex justify-content-left' style="height: 150px; width: 150px">
													<img class='popup-image h-8 w-8 rounded-full object-cover'
														src="{{ isset($membre->profile_photo_path) ? asset('picture_profile/' . $membre->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . $membre->nom . '&background=c7932b&size=150&color=fff' }}"
														alt='' />
												</div>
												<div class="row user-info text-left">
													<div class="col-md-12 text-center">
														<h3> {{ $membre->nom . ' ' . $membre->prenom }}</h3>
													</div>
													<div style="margin-bottom: 70px"></div>
													<div class="col-md-12">
														<h5>Matricule : {{ $membre->matricule }}</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Nationalité : {{ $membre->nationalité }}</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Agence : {{ $membre->agence }}</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Sexe : {{ $membre->sexe }}</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Téléphone : <a href="tel:{{ $membre->tel }}">{{ $membre->tel }}</a>
														</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Email : <a href="mailto:{{ $membre->email }}">{{ $membre->email }}</a>
														</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Categorie : {{ $category->libelle }}
														</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Date de naissance :
															{{ date('d M Y', strtotime($membre->date_naissance)) }}
														</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Date de recrutement :
															{{ date('d M Y', strtotime($membre->date_recrutement)) }}
														</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Date d'hadésion à la mutuelle :
															{{ date('d M Y', strtotime($membre->date_hadésion)) }}</h5>
													</div>
													<div style="margin-bottom: 80px"></div>
													<div class="col-md-12">
														<h5>Total des cotisations :
															{{ $totalCotisation }} FCFA</h5>
													</div>
													<div style="margin-bottom: 40px"></div>
													<div class="col-md-12">
														<h5>Total des prestations :
															{{ $totalPrestation }} FCFA</h5>
													</div>v>
												</div>
											</div>
										</div>
										<div class="col-md-8">
											<div class="card">
												<div class="card-inner">
													<ul class="nav nav-tabs">
														<li class="nav-item">
															<a class="nav-link active" data-toggle="tab" href="#tabItem1">Liste
																des cotisation</a>
														</li>
														<li class="nav-item">
															<a class="nav-link" id="tabItem" data-toggle="tab" href="#tabItem2">Liste
																des prestations</a>
														</li>
														<li class="nav-item">
															<a class="nav-link" data-toggle="tab" href="#tabItem3">Liste
																des ayant droits</a>
														</li>
													</ul>
													<div class="tab-content">
														<div class="tab-pane active" id="tabItem1">
															<h3 class="text-center">Liste des cotisations</h3>
															<div class="table-responsive">
																<table class="nk-tb-list nk-tb-ulist" id="cotisationList" data-auto-responsive="true">
																	<thead>
																		<tr class="nk-tb-item nk-tb-head">
																			<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Montant
																					(FCFA)')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Numéro de
																					la
																					séance')</span>
																			</th>
																		</tr>
																	</thead>
																	<tbody></tbody>
																</table>
															</div>
														</div>
														<div class="tab-pane" id="tabItem2">
															<div class="row m-md-2">
																<h3 class="col-md-10 text-center">Liste des prestations
																</h3>
															</div>
															<div class="table-responsive">
																<table class="nk-tb-list nk-tb-ulist" id="prestationList" data-auto-responsive="true">
																	<thead>
																		<tr class="nk-tb-item nk-tb-head">
																			<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Date')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Montant
																					(FCFA)')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Type de
																					prestation')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Ayant
																					droit')</span>
																			</th>
																		</tr>
																	</thead>
																	<tbody></tbody>
																</table>
															</div>
														</div>
														<div class="tab-pane" id="tabItem3">
															<div class="row m-md-2">
																<h3 class="col-md-10 text-center">Liste des ayant droits
																</h3>
															</div>
															<div class="table-responsive">
																<table class="nk-tb-list nk-tb-ulist" id="ayantdroitList" data-auto-responsive="true">
																	<thead>
																		<tr class="nk-tb-item nk-tb-head">
																			<th class="nk-tb-col" hidden><span class="sub-text"></span></th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Nom')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Liens de
																					parenté avec le membre')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Cni')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Acte de
																					naissance')</span>
																			</th>
																			<th class="nk-tb-col"><span class="sub-text">@lang('Certificat
																					de
																					vie')</span>
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

		  $('#cotisationList').DataTable({
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
		   ajax: "{{ route('getcotisationListForUser', $membre->id) }}",
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
		     "data": 'date',
		     "name": 'date',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'montant',
		     "name": 'montant',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'numero_seance',
		     "name": 'numero_seance',
		     "className": 'nk-tb-col'
		    },
		   ]
		  });
		 });
		</script>

		<script>
		 $(document).ready(function() {
		  $('#prestationList').DataTable({
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
		   ajax: "{{ route('getprestationListForUser', $membre->id) }}",
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
		     "data": 'date',
		     "name": 'date',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'montant',
		     "name": 'montant',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'typePrestation',
		     "name": 'typePrestation',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'ayantDroit',
		     "name": 'ayantDroit',
		     "className": 'nk-tb-col'
		    },
		   ]
		  });
		 });
		</script>

		<script>
		 $(document).ready(function() {
		  $('#ayantdroitList').DataTable({
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
		   ajax: "{{ route('ayantsdroitsListForUser', $membre->id) }}",
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
		     "data": 'nom',
		     "name": 'nom',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'liens',
		     "name": 'liens',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'cni',
		     "name": 'cni',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'acte_naissance',
		     "name": 'acte_naissance',
		     "className": 'nk-tb-col'
		    },
		    {
		     "data": 'certificat_vie',
		     "name": 'certificat_vie',
		     "className": 'nk-tb-col'
		    },
		   ]
		  });
		 });

		 $(document).on('click', '.delete-data-cot', function(e) {
		  e.preventDefault();
		  var id = $(this).attr('data_id');
		  Swal.fire({
		   title: 'Voulez-vous vraiment supprimer ?',
		   text: "Vous êtes en train de vouloir supprimer une donnée ! Assurez-vous que c'est bien la bonne !",
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
		     url: "{{ route('membre.deletecotisation') }}",
		     type: "POST",
		     dataType: 'json',
		     data: {
		      id: id,
		     },
		     success: function(data) {
		      if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
		       Swal.fire(
		        'Supprimer!',
		        data.success,
		        'success'
		       )
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
		      Swal.fire('Une erreur s\'est produite.',
		       'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
		       'error');

		     }
		    });
		   }
		  });
		 });


		 $(document).on('click', '.delete-data-pres', function(e) {
		  e.preventDefault();
		  var id = $(this).attr('data_id');
		  Swal.fire({
		   title: 'Voulez-vous vraiment supprimer ?',
		   text: "Vous êtes en train de vouloir supprimer une donnée ! Assurez-vous que c'est bien la bonne !",
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
		     url: "{{ route('prestation.delete') }}",
		     type: "POST",
		     dataType: 'json',
		     data: {
		      id: id,
		     },
		     success: function(data) {
		      if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
		       Swal.fire(
		        'Supprimer!',
		        data.success,
		        'success'
		       )
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
		      Swal.fire('Une erreur s\'est produite.',
		       'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
		       'error');

		     }
		    });
		   }
		  });
		 });

		 $(document).on('click', '.delete-data-ayant', function(e) {
		  e.preventDefault();
		  var id = $(this).attr('data_id');
		  Swal.fire({
		   title: 'Voulez-vous vraiment supprimer ?',
		   text: "Vous êtes en train de vouloir supprimer une donnée ! Assurez-vous que c'est bien la bonne !",
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
		     url: "{{ route('ayantsdroits.delete') }}",
		     type: "POST",
		     dataType: 'json',
		     data: {
		      id: id,
		     },
		     success: function(data) {
		      if ($.isEmptyObject(data.errors) && $.isEmptyObject(data.error)) {
		       Swal.fire(
		        'Supprimer!',
		        data.success,
		        'success'
		       )
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
		      Swal.fire('Une erreur s\'est produite.',
		       'Veuilez contacté l\'administration et leur expliqué l\'opération qui a provoqué cette erreur.',
		       'error');

		     }
		    });
		   }
		  });
		 });
		</script>
	@endsection
@endif
