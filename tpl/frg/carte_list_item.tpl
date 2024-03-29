<li class="yu-layout-row yu-list-item">
	<img class="" src="{{ assets }}/cartes/{{ carte.id }}_thumb.jpg" width="138" height="200" alt="{{carte.nom | escape }}"/>
	<div class="yu-padding yu-large yu-grow">
		<div class="yu-layout-column">
			<span class="yu-subtext yu-hide">Créer par {{ carte.utilisateur.username }} (<a href="mailto:{{ carte.utilisateur.email }}">{{ carte.utilisateur.email }}</a>) le {{ carte.dateCreation }}</span>
			<span class="yu-title">{{ carte.nom  | escape }}</span>	
			<span class="yu-subtitle">{{ carte.attaque  | escape }} {{ carte.defense  | escape }}</span>
		</div>
		<p class="yu-align-justify">{{ carte.description  | escape }}</p>
	</div>
	<form method="GET" action="index.php" class="yu-layout-column">
		<input type="hidden" name="carte" value="{{ carte.id }}"/>
		<button name="carteAction" class="btn btn-secondary" value="more">Détail</button>	
		{{ '<button name="carteAction" class="btn" value="update">Modifier</button>' | hideFor : 'USER' | showFor : 'ADMIN' : carte.utilisateur.id}}	
		{{ '<button name="carteAction" class="btn btn-warn" value="delete">Supprimer</button>' | hideFor : 'USER' | showFor : 'ADMIN' : carte.utilisateur.id}}	
	</form>
</li>
