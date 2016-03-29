<!DOCTYPE html>
<html>
<head>
	<title>{{title}}</title>
	<meta name="viewport" content="initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="css/yu.css">
	<link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet' type='text/css'>
</head>
<body>
	<div class="yu-header">
		{{ header }}
	</div>

	<div class="yu-content">
		<div class="yu-menu">
			<form method="GET" action="index.php">
				<ul class="yu-list">
					<li class="yu-list-item"><button class="btn btn-list" name="action" value="home">Home</button></li>
					{{ '<li class="yu-list-item"><button class="btn btn-list" name="action" value="mescartes">Mes Cartes</button></li>' | hideFor : 'USER'}}
					{{ '<li class="yu-list-item"><button class="btn btn-list" name="action" value="create">Créer une carte</button></li>' | hideFor : 'USER'}}
					<li class="yu-list-item"><button class="btn btn-list" name="action" value="cartes">Cartes</button></li>
				</ul>
			</form>
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

	<script src="js/validatorMessages.js"></script>
	<script src="js/validateDocument.js"></script>
</body>
</html>