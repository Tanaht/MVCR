<li class="yu-carte-list-item list-item">
	<div class="yu-carte-list-item-content-container">
		<img class="yu-carte-list-item-img" src="assets/cartes/{{ carte.id }}_thumb.jpg" width="138" height="200" alt="{{carte.nom}}"/>
		<div class="yu-carte-list-item-content">
		<div class="yu-carte-list-item-content-header">
			<h4 class="yu-carte-list-item-nom yu-title">{{ carte.nom }}</h4>	
			<span class="yu-carte-list-item-atk yu-subtitle">{{ carte.attaque }}</span>
			<span class="yu-carte-list-item-def yu-subtitle">{{ carte.defense }}</span>
		</div>
			<span class="yu-carte-list-item-desc">{{ carte.description }}</span>
		</div>
		<div class="yu-carte-list-item-action">
			<form method="GET" action="index.php">
				
				<button name="carte" class="btn" value="{{ carte.id }}">Détail</button>	
			</form>
		</div>
	</div>
</li>