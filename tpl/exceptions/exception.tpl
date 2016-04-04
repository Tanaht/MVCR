<!DOCTYPE html>
<html>
<head>
	<title>Erreur</title>
	<meta name="viewport" content="initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" href="{{ baseuri }}/css/yu.css">
	<link href='https://fonts.googleapis.com/icon?family=Material+Icons' rel='stylesheet' type='text/css'>
</head>
<body>
	<div class="yu-content">
		<div class="yu-page-container">
			<div class="yu-page">
					<div class="yu-block">
						<h1 class="yu-title yu-warn">Exception:</h1>
						<h3>Message:</h3>
						<p>{{ message }}</p>	
					</div>
					<div class="yu-block">
						<h3>Trace (Chaine de caractère):</h3>
						<pre>{{ traceAsString }}</pre>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>