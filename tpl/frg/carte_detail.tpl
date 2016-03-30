<div class="yu-layou-column">
	<div class="yu-layout-row yu-block">
		<img src="assets/cartes/{{ carte.id }}.jpg" alt="{{carte.nom | escape }}">
		<div class="yu-layout-column">
			<h1 class="yu-title">{{carte.nom | escape }}</h1>
			<span>Créateur: {{ carte.utilisateur.nom | escape }}</span>
			<span>Catégorie: {{ carte.categorie.nom }}</span>
			<span>Attribut: {{ carte.attribut.nom }}</span>
			<span>{{'Niveau ' | empty : 'true' : carte.niveau : '' }} {{carte.niveau | escape }}</span>
			<span>{{carte.attaque | escape }} {{'points d'attaque' | empty : 'true' : carte.attaque : '' }}</span>
			<span>{{carte.defense | escape }} {{'points dde défense | empty : 'true' : carte.defense : '' }}</span>
			<span>Effet: {{ carte.effet.nom }}</span>
		</div>
	</div>
	<div class="yu-layout-row" class="yu-block">
		<div>
			<h3>Description: </h3>
			<p class="yu-align-justify">{{ carte.description | escape }}</p>
		</div>
		<div>
			<h3>Types: </h3>
			{{ carte.types | list : 'nom' | empty : true : 'this' : 'La carte ne possède aucun type.'}}
		</div>
	</div>
</div>
