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
		Télécharger</button>
	<div id="content">
		<div class="expe"><br><br>
			<div>
				<span>{{ Str::upper($emprunt->membre->nom) . ' ' . $emprunt->membre->prenom }}
					<div style="float: right">
						{{ $emprunt->membre->agence }} le {{ Carbon\Carbon::now()->format('d-m-Y') }}
					</div>
				</span><br><br>
				<span>{{ $emprunt->membre->email }}</span><br><br>

			</div>
		</div>
		<br><br>
		<div class="obj">
			<p style="float: right; margin-right: 7%; text-align: center">
				A <br><br> Monsieur le président de MABEAC-CAMEROUN
			</p>
		</div>
		<br /><br /><br /><br /><br /><br /><br><br><br><br>
		<div class="title-1">
			<p>
				<b>Objet</b> : Demande de prêt
			</p>
		</div>
		<br />

		<div class="title-2" style=" text-align: justify;">
			<p>
				Monsieur, <br><br><br>
				Afin de pouvoir réaliser un projet qui me tient à cœur, je me permets de solliciter les
				services <b>MABEAC-CAMEROUN</b> pour un prêt d’un montant
				de <b> {{ number_format($emprunt->montant, 0, '.', ' ') }} [{{ $f->format((int) $emprunt->montant) }}] FCFA</b>.
				Je souhaiterais en effet (préciser le projet : <b>{{ $emprunt->objet }}</b>), et mes
				ressources actuelles ne me permettent pas de le réaliser sans un appui financier
				complémentaire. <br><br>
				Vous trouverez ci-joint les pièces justificatives nécessaires à mon dossier : (Préciser les
				pièces : <b>avis d’imposition, bulletins de salaire, devis de travaux, proposition de
					vente, contrat de travail, etc.</b>) <br><br>
				Je me tiens à votre disposition pour fixer un rendez-vous à l’horaire qui vous conviendra. <br><br>
				En vous remerciant par avance de l’attention que vous porterez à ma demande, je vous
				prie d’agréer, Madame, Monsieur, l’expression de mes salutations distinguées. <br><br><br><br>


				{{-- <br><br><br> Je soussigné, {{ Str::upper($emprunt->membre->nom) . ' ' . $emprunt->membre->prenom }}
				<br /><br>
				Après avoir pris connaissance des information contenues dans l'avis
				d'appel à souscription y compris les addictifs <br /><br>
				N° {{ str_pad($emprunt->id, 2, '0', STR_PAD_LEFT) . '/' . Carbon\Carbon::parse($emprunt->date)->format('Y') }}
				relatif à {{ $emprunt->objet }} <br><br>
				<span>-</span> Me soumets et m'engage à souscrire, conformement au dossier d'appel à souscription, un montant
				de <b>{{ number_format($emprunt->montant, 0, '.', ' ') }} [{{ $f->format((int) $emprunt->montant) }}] francs
					CFA</b> <br><br>
				<span>-</span> M'engage à déposer ces fonds dans un delai de <b>6 mois</b>, après
				la publication des adjudications ; <br><br>
				La présente souscription acceptée par moi vaut engagement entre nous. <br><br><br><br><br> --}}

				<i style="float: right; margin-right: 10%; text-align: left">Signature</i>
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
