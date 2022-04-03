<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<base href="../">
	<meta charset="utf-8">
	<meta name="author" content="Softnio">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description"
		content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
	<!-- Fav Icon  -->
	<link rel="shortcut icon" href="{!! asset('images/favicon.png') !!}">

	<!-- Page Title  -->
	<title>@yield('title')</title>
	<!-- StyleSheets  -->
	<link rel="stylesheet" href="{!! asset('assets/css/dashlite.css?ver=2.2.0') !!}">
	<link id="skin-default" rel="stylesheet" href="{!! asset('assets/css/theme.css?ver=2.2.0') !!}">
	<link rel="stylesheet" href="{!! asset('assets/css/style-email.css') !!}">

</head>

<body class="no-touch nk-nio-theme @yield('dark')">
	@yield('contenu')

	{{-- Script js --}}
	<script src="{!! asset('assets/js/bundle.js?ver=2.2.0') !!}"></script>
	<script src="{!! asset('assets/js/scripts.js?ver=2.2.0') !!}"></script>
	<script src="{!! asset('assets/js/libs/jkanban.js?ver=2.2.0') !!}"></script>
	{{-- <script src="{!! asset('assets/js/apps/kanban.js?ver=2.2.0') !!}"></script> --}}
	<script src="{!! asset('js/jquery.min.js') !!}"></script>
	@yield('script')
</body>

</html>
