<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>Ordre de paiment</title>
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
</head>

<body>
	@php
		$f = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
	@endphp
	<br><br>
	<button id="btn" class="btn btn-primary" style="width: 100%; height: 30px; font-size: 20px">Imprimer ou
		Télécharger</button>
	<div id="content">
		<div class="expe">
			<div style="text-align: left;">
				<b>MUTUELLE DES AGENTS <br>DE LA <br>BEAC DU CAMEROUN</b>

				<br><br>
				<img src="{{ asset('images/logo.png') }}" alt="" />
			</div>
		</div>
		<div class="obj">
			<h1 style="text-align: center;"><b><u>ORDRE DE PAIEMENT N° 02/2022</u></b></h1>
		</div>
		<br /><br /><br /><br /><br /><br />

		<div class="title-2" style=" text-align: justify;">
			<p>
				<span style="margin-left: 50px;">A</span> L'attention du Service des Ressources Humaines et Formation, <br><br><br>
				<span style="margin-left: 50px;">Veuillez</span> payer dans le compte de la Mutuelle MABEAC, confomément la lettre
				d'adjudication du {{ Carbon\Carbon::parse($emprunt->date_de_publication)->format('d/m/y') }}, la somme de
				<b>{{ number_format($emprunt->montant, 0, '.', ' ') }} [{{ $f->format((int) $emprunt->montant) }}] FCFA
					à M/Mme {{ Str::upper($emprunt->membre->nom) . ' ' . $emprunt->membre->prenom }}</b> representant le capital
				investi pour un financement Bridge Loan Immo. <br><br><br>
			<div style=" text-align: center;">
				Bafoussam, le {{ Carbon\Carbon::now()->format('d-m-Y') }} <br><br>
				Pour la Mutuelle, <br><br><br>
				<b>Le président,</b><br><br><br><br><br><br>
				<b>MBOA Marcel</b><br><br><br>
			</div>
			</p>
		</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
	integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/printThis.js') }}"></script>
<script>
	$("#btn").click(function(e) {
		e.preventDefault();
		$("#content").printThis({
			debug: false, // show the iframe for debugging
			importCSS: true, // import parent page css
			importStyle: false, // import style tags
			printContainer: true,
		});
	});
</script>

</html>
