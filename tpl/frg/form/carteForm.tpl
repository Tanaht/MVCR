<form nom="{{ form.name }}" class="yu-block" method="post" enctype="multipart/form-data">
	<h1>Créer une carte</h1>
	<div class="yu-layout-column">
		<div class="yu-input-container">
			<label for="{{ form.inputs.nom.name }}">Nom</label>
			<input type="text" id="{{ form.inputs.nom.name }}" name="{{ form.inputs.nom.name }}" {{ form.inputs.nom.required }} {{ form.inputs.nom.value }}/>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.attaque.name }}">Attaque</label>
			<input type="text" id="{{ form.inputs.attaque.name }}" name="{{ form.inputs.attaque.name }}" {{ form.inputs.attaque.required }} {{ form.inputs.attaque.value }}/>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.defense.name }}">Défense</label>
			<input type="text" id="{{ form.inputs.defense.name }}" name="{{ form.inputs.defense.name }}" {{ form.inputs.defense.required }} {{ form.inputs.defense.value }}/>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.image.name }}">Nom</label>
			<input accept="image/jpeg" type="file" id="{{ form.inputs.image.name }}" name="{{ form.inputs.image.name }}" {{ form.inputs.image.required }} />
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.niveau.name }}">Niveau/Rang</label>
			{{niveaux | input : 'select' : form.inputs.niveau.name}}
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Catégorie</legend>
				{{categories | input : 'radio' : form.inputs.categorie.name : 'required'}}
			</fieldset>		
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Effet</legend>
				{{effets | input : 'radio' : form.inputs.effet.name : 'required'}}
			</fieldset>					
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Attribut</legend>
				{{attributs | input : 'radio' : form.inputs.attribut.name : 'required'}}
			</fieldset>
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Type</legend>
				<div class="yu-container">{{types | input : 'checkbox' : 'types[]'}}</div>
			</fieldset>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.description.name }}">Description</label>
			<textarea name="{{ form.inputs.description.name }}" id="{{ form.inputs.description.name }}">{{ form.inputs.description.value }}</textarea>
		</div>
		<a href="{{ router.ajouterCarte.path | path : '' }}"><button class="btn">Créer la carte</button></a>
	</div>
</form>