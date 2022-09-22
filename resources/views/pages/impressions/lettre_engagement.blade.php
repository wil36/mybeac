<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>Lettre de souscription</title>
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
		Télécharger</button><br>
	<div id="content">
		<div class="expe">
			<div style="text-align: left;">
				<b>MUTUELLE DES AGENTS <br>DE LA <br>BEAC DU CAMEROUN</b>

				<br><br>
				<img src="{{ asset('images/logo.png') }}" alt="" />
			</div>
		</div><br> <br><br> <br>
		<div class="obj">
			<h1 style="text-align: center;"><b><u>LETTRE D'ENGAGEMENT</u></b></h1>
		</div>
		<br /><br /><br /><br /><br /><br />

		<div class="title-2" style=" text-align: justify;">
			<p>
				<span style="margin-left: 50px;">Je</span> Soussignée <b><i>M/Mme
						{{ Str::upper($emprunt->membre->nom) . ' ' . $emprunt->membre->prenom }}</i></b>,
				reconnait avoir bénéficié auprès de la Mutuele <b>MABEAC-CAMEROUN</b> du montant de
				<b>{{ number_format($emprunt->montant, 0, '.', ' ') }} [{{ $f->format((int) $emprunt->montant) }}]</b> en vue
				d'éffectuer le remboursement anticipé de mes prêts en cours (prêt Automobile et Petit
				Equipement) et m'engage à rembourser cette somme moyennant un intérêt de
				<b>.........</b> après déblocage du nouveau prêt consenti ; soit de <i><b>...........
						(.......).</b></i><br><br><br>
				<span style="margin-left: 50px;">Il</span> est à noter que cette comission établit sur un mois, pourrait augmenter
				si
				l'échéance actuelle venait à ne pas être respecter pour une quelconque raison que ce soit suivant le tableau de
				calcul des comissions précomptées en annexe. <br><br><br><br>
			<div style=" text-align: center;">
				Bafoussam, le {{ Carbon\Carbon::now()->format('d-m-Y') }} <br><br><br><br><br><br><br><br><br>
			</div>
			<i>Signature précédé de la mention : lu et approuvé</i>
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
