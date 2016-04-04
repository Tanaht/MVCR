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
				<li class="yu-list-item"><a class="" href="{{ router.home.path | path : ''}}"><button class="btn btn-list">Home</button></a></li>
				<li class="yu-list-item"><a class=""  href="{{ router.mescartes.path | path : ''}}">{{'<button class="btn btn-list">Mes cartes</button>' | hideFor : 'USER'}}</a></li>
				<li class="yu-list-item"><a class="" href="{{ router.ajouterCarte.path | path : ''}}">{{'<button class="btn btn-list">Créer une carte</button>' | hideFor : 'USER'}}</li>
				<li class="yu-list-item"><a class="" href="{{ router.cartes.path | path : ''}}"><button class="btn btn-list">Les Cartes</button></a></li>
                <li class="yu-list-item"><a class="" href="{{ router.apropos.path | path : ''}}"><button class="btn btn-list">A Propos</button></a></li>
            </ul>
		</div>
		<div class="yu-page-container">
			<div class="yu-page">
				<div class="yu-wrapper">
					{{ content }}
				</div>
			</div>
		</div>
	</div>
	
	<div class="yu-footer">
		{{ footer }}
	</div>
	<script src="{{ baseuri }}/js/listShowDetail.js"></script>
</body>
</html>