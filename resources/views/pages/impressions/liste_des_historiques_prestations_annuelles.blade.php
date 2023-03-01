<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>Historique prestation annuelle</title>
	<style>
		body {
			max-width: 800px;
			margin: 0 auto;
			font-size: 16px;
		}

		h1 {
			font-size: 1.5em;
			text-align: center;
		}

		h2 {
			font-size: 1.3em;
		}

		p,
		ul,
		ol {
			font-size: 1.1em;
		}

		.expe {
			float: right;
			width: 100%;
		}

		.txt-expe {
			float: right;
		}

		.dest {
			float: left;
			width: 100%;
		}

		.txt-indent {
			margin-left: 50px;
		}
	</style>
	<link rel="stylesheet" href="{!! asset('assets/css/dashlite.css?ver=2.7.0') !!}">
	<link id="skin-default" rel="stylesheet" href="{!! asset('assets/css/theme.css?ver=2.7.0') !!}">
</head>

<body
	style="justify-content: center; justify-items: center; position: relative; margin-left: auto;
  margin-right: auto;">
	@php
		$f = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
	@endphp
	<br><br>
	<button id="btn" class="btn btn-primary"
		style="width: 100%; height: 30px; font-size: 20px; text-align: center; justify-content: center; margin-bottom: 20px;">Imprimer
		ou
		Télécharger</button>
	<div id="content">
		<div class="expe">
			<img src="{{ asset('images/logo.png') }}" alt="" />
			<img src="{{ asset('images/logo.png') }}" alt="" style="float: right" />
		</div>
		<br><br>
		<div class="obj">
			<h1 style="text-align: center;"><b>Historique prestation annuelle</b></h1>
			<br />
		</div>

		<div class="title-2" style=" text-align: justify;">
			<div class="table-responsive" style="padding-bottom: 75px; padding-top: 75px;">
				<table class="nk-tb-list nk-tb-ulist table-bordered" id="userList" data-auto-responsive="false"
					style="text-align: center;">
					<thead>
						<tr class="nk-tb-item nk-tb-head">
							<th class="nk-tb-col"><span class="sub-text">@lang('Année')</span>
							</th>
							<th class="nk-tb-col"><span class="sub-text">@lang('Montant (FCFA)')</span>
							</th>
						</tr>
					</thead>

					{{-- @dd($datas) --}}
					<tbody style="text-align: center;">
						@foreach ($datas as $data)
							<tr>
								<td>{{ $data->annee }}</td>
								<td>{{ number_format($data->montant, 0, ',', ' ') . ' FCFA' }}</td>

							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
</body>
<script src="{!! asset('assets/js/bundle.js?ver=2.7.0') !!}"></script>
<script src="{!! asset('assets/js/scripts.js?ver=2.7.0') !!}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/printThis.js') }}"></script>
<script>
	$("#btn").click(function(e) {
		e.preventDefault();
		$("#content").printThis({
			debug: false, // show the iframe for debugging
			importCSS: true, // import parent page css
			importStyle: true, // import style tags
			printContainer: true,

			loadCSS: "", // path to additional css file - use an array [] for multiple
			pageTitle: "", // add title to print page
			removeInline: false, // remove inline styles from print elements
			removeInlineSelector: "*", // custom selectors to filter inline styles. removeInline must be true
			printDelay: 333, // variable print delay
			header: null, // prefix to html
			footer: null, // postfix to html
			base: false, // preserve the BASE tag or accept a string for the URL
			formValues: true, // preserve input/form values
			canvas: false, // copy canvas content
			doctypeString: '...', // enter a different doctype for older markup
			removeScripts: false, // remove script tags from print content
			copyTagClasses: false, // copy classes from the html & body tag
			beforePrintEvent: null, // function for printEvent in iframe
			beforePrint: null, // function called before iframe is filled
			afterPrint: null
		});
	});

	$(document).ready(function() {
		$("#content").printThis({
			debug: false, // show the iframe for debugging
			importCSS: true, // import parent page css
			importStyle: true, // import style tags
			printContainer: true,

			loadCSS: "", // path to additional css file - use an array [] for multiple
			pageTitle: "", // add title to print page
			removeInline: false, // remove inline styles from print elements
			removeInlineSelector: "*", // custom selectors to filter inline styles. removeInline must be true
			printDelay: 333, // variable print delay
			header: null, // prefix to html
			footer: null, // postfix to html
			base: false, // preserve the BASE tag or accept a string for the URL
			formValues: true, // preserve input/form values
			canvas: false, // copy canvas content
			doctypeString: '...', // enter a different doctype for older markup
			removeScripts: false, // remove script tags from print content
			copyTagClasses: false, // copy classes from the html & body tag
			beforePrintEvent: null, // function for printEvent in iframe
			beforePrint: null, // function called before iframe is filled
			afterPrint: null
		});
	});
</script>

</html>
