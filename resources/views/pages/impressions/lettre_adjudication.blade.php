<!DOCTYPE html>

<html>

<head>
	<meta charset="utf-8" />
	<title>Lettre d'adjudication</title>
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
			<img src="{{ asset('images/logo.png') }}" alt="" />
			<img src="{{ asset('images/logo.png') }}" alt="" style="float: right" />
		</div>
		<div class="obj">
			<h1 style="text-align: center;"><b>Lettre d'adjudication</b></h1>
			<br />
			<p style="float: right; margin-right: 7%">
				Bafoussam, le {{ Carbon\Carbon::now()->format('d-m-Y') }} <br><br>
				<b>Destinataire</b> : L'adjudicataire {{ Str::upper($emprunt->membre->nom) . ' ' . $emprunt->membre->prenom }}
				<br /><br />
			</p>
		</div>
		<br /><br /><br /><br /><br /><br />
		<div class="title-1" style="font-size: 20px">
			<p>
				<b>Objet</b> : Avis d'Apppel à souscription n°
				{{ str_pad($emprunt->id, 2, '0', STR_PAD_LEFT) . '/' . Carbon\Carbon::parse($emprunt->date)->format('Y') }}
				du {{ Carbon\Carbon::parse($emprunt->date)->format('d/m/y') }}
			</p>
		</div>
		<div class="title-1" style="font-size: 20px">
			<p>
				<b>Référence</b> : V/L de souscription
				n° {{ str_pad($emprunt->id, 2, '0', STR_PAD_LEFT) . '/' . Carbon\Carbon::parse($emprunt->date)->format('Y') }}
				du {{ Carbon\Carbon::parse($emprunt->date)->format('d/m/y') }}
			</p>
		</div><br>
		<div class="title-2">
			<p style=" text-align: justify;">
				<span style="margin-left: 50px;">Monsieur (ou Madame)</span>
				<br /><br>
				<span style="margin-left: 50px;">Faisant</span> suite à votre lettre visée en reference relative à l'affaire reprise
				en objet, nous avons
				l'honneur de vous informer que vous êtes adjudicataire de {{ $emprunt->montant / 500000 }} tickets de trésorerie
				d'un
				montant global de
				<b>{{ number_format($emprunt->montant, 0, '.', ' ') }} [{{ $f->format((int) $emprunt->montant) }}] FCFA</b>,
				rémunéré suivant règlement de l'émission<br /><br>
				<br>
				<span style="margin-left: 50px;">Vous</span> voudrez bien prendre les dispositions nécéssaires pour le dépôt de
				ladite somme dans les caisse de MABEAC au plus tard le
				{{ Carbon\Carbon::parse($emprunt->date_de_fin)->format('d/m/Y') }}. <br><br>
				<span style="margin-left: 50px;">Par</span> ailleurs, nous vous informons qu'il vous sera servi des comissions
				nettes
				précomptées d'un montant de <b>{{ number_format($emprunt->montant_commission, 0, '.', ' ') }} FCFA</b>,
				déduction
				faite du fonds solidarité MABEAC.
				<br><br>
				<span style="margin-left: 50px;">Veuillez</span> agréer Monsieur (ou Madame) nos salutations cordiales.
				<br><br><br><br>

			</p>
			<div style=" text-align: center;">
				<b>Le président,</b><br><br><br><br>
				<b>MBOA Marcel</b><br><br><br>
			</div>

			<b><i>Heure de transmission : ce jour à 10 h 00 au plus tard.</i></b>
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
