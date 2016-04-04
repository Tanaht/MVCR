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
	<div class="yu-layout-column">
		<input type="hidden" name="carte"/>
		<a href="{{ router.carte.path | path : carte.id }}"><button class="btn btn-secondary">Détail</button></a>
        <a href="{{ router.modifierCarte.path | path : carte.id }}">{{ '<button class="btn">Modifier</button>' | hideFor : 'USER' | showFor : 'ADMIN' : carte.utilisateur.id}}</a>
        <a href="{{ router.supprimerCarte.path | path : carte.id }}">{{ '<button class="btn btn-warn">Supprimer</button>' | hideFor : 'USER' | showFor : 'ADMIN' : carte.utilisateur.id}}</a>
	</div>
</li>