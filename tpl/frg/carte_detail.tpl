<div class="yu-layou-column">
	<div class="yu-xl-layout-row yu-xs-layout-column yu-block">
		<img sizes="(max-width: 690px) 70vh, (min-width: 690px) 100vh" class="yu-center" src="assets/cartes/{{ carte.id }}.jpg"
			srcset="assets/cartes/{{ carte.id }}_thumb.jpg 138w,
					assets/cartes/{{ carte.id }}.jpg 342w" 
			width="342" height="492" alt="{{carte.nom | escape }}">
		<div class="yu-layout-column yu-padding yu-medium">
			<form method="GET" action="index.php" class="yu-layout-row yu-align-right">
				<input type="hidden" name="carte" value="{{ carte.id }}"/>
				<button name="carteAction" class="btn btn-secondary" value="more">Détail</button>	
				{{ '<button name="carteAction" class="btn" value="update">Modifier</button>' | hideFor : 'USER' | showFor : 'ADMIN' : carte.utilisateur.id}}	
				{{ '<button name="carteAction" class="btn btn-warn" value="delete">Supprimer</button>' | hideFor : 'USER' | showFor : 'ADMIN' : carte.utilisateur.id}}	
			</form>
			<h1 class="yu-title">{{carte.nom | escape }}</h1>
			<span>Créateur: {{ carte.utilisateur.username | escape }} (<a href="mailto:{{ carte.utilisateur.email }}">{{ carte.utilisateur.email }}</a>)</span>
			<span>Le {{ carte.dateCreation }}</span>
			<span>Catégorie: {{ carte.categorie.nom }}</span>
			<span>Attribut: {{ carte.attribut.nom }}</span>
			<h3>Description: </h3>
			<p class="yu-align-justify">{{ carte.description | escape }}</p>
		</div>
	</div>
	<div class="yu-layout-row yu-block">
		<div class="yu-layout-column yu-flex">
			<h3>Informations sur la carte: </h3>
			<span>{{'Niveau ' | empty : true : carte.niveau : '' }} {{carte.niveau | escape }}</span>
			<span>{{carte.attaque | escape }} {{'points d'attaque' | empty : true : carte.attaque : '' }}</span>
			<span>{{carte.defense | escape }} {{'points de défense' | empty : true : carte.defense : '' }}</span>
			<span>Effet: {{ carte.effet.nom }}</span>
		</div>
		<div class="yu-flex">
			<h3>Types: </h3>
			{{ carte.types | list : 'nom' | empty : true : 'this' : 'La carte ne possède aucun type.'}}
		</div>
	</div>
</div>
