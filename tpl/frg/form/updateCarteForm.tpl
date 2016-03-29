<form class="yu-block" method="post" action="index.php?carte={{carte.id}}" enctype="multipart/form-data">
	<h1>Modifier une carte</h1>
	<div class="yu-layout-column">
		<div class="yu-input-container">
			<label for="nom">Nom</label>
			<input type="text" id="nom" name="nom" value="{{carte.nom}}" required />
		</div>
		<div class="yu-input-container">
			<label for="attaque">Attaque</label>
			<input type="text" id="attaque" name="attaque" value="{{carte.attaque}}"/>
		</div>
		<div class="yu-input-container">
			<label for="defense">Défense</label>
			<input type="text" id="defense" name="defense" value="{{carte.defense}}"/>
		</div>
		<div class="yu-input-container">
			<label for="image">Image</label>
			<input type="file" id="image" name="image" accept="image/jpeg" />
		</div>
		<div class="yu-input-container">
			<label for="niveau">Niveau/Rang</label>
			{{niveaux | selectedKey : carte.niveau | input : 'select' : 'niveau': 'required'}}
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Catégorie</legend>
				{{categories | selectedKey : carte.categorie.id | input : 'radio' : 'categorie'}}
			</fieldset>		
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Effet</legend>
				{{effets | selectedKey : carte.effet.id | input : 'radio' : 'effet'}}
			</fieldset>					
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Attribut</legend>
				{{attributs | selectedKey : carte.attribut.id | input : 'radio' : 'attribut' : 'required'}}
			</fieldset>
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Type</legend>
				<div class="yu-container">{{types | input : 'checkbox' : 'types[]'}}</div>
			</fieldset>
		</div>
		<div class="yu-input-container">
			<label for="description">Description</label>
			<textarea name="description" id="description">{{carte.description}}</textarea>
		</div>
		<button name="update" class="btn" value="card">Modifier la carte</button> 
	</div>
</form>