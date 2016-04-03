<!DOCTYPE html>
<html>
<head>
	<title>{{title}}</title>
	<meta name="viewport" content="initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="{{ baseuri }}/css/yu.css">
	<link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet' type='text/css'>
</head>
<body>
	<div class="yu-header">
		{{ header }}
	</div>

	<div class="yu-content">
		<div class="yu-menu">
			<ul class="yu-list">
				<li class="yu-list-item"><a class="btn btn-list" href="{{ router.index.path | path : ''}}">Home</a></li>
				<li class="yu-list-item"><a class="btn btn-list"  href="{{ router.mescartes.path | path : ''}}">Mes Cartes</a></li>
				<li class="yu-list-item"><a class="btn btn-list" href="{{ router.ajouterCarte.path | path : ''}}">Créer une carte</a></li>
				<li class="yu-list-item"><a class="btn btn-list" href="{{ router.cartes.path | path : ''}}">Cartes</a></li>
			</ul>
		</div>
		<div class="yu-page-container">
			<div class="yu-page">
				{{ content }}
			</div>
		</div>
	</div>
	
	<div class="yu-footer">
		{{ footer }}
	</div>
	<script src="{{ baseuri }}/js/listShowDetail.js"></script>
</body>
</html>