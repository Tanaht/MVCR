<form name="{{ form.name }}" class="yu-block" method="post">
	<h1>Modifier une carte</h1>
	<div class="yu-layout-column">
		<div class="yu-input-container">
			<label for="{{ form.inputs.nom.name }}">Nom</label>
			<input type="text" id="{{ form.inputs.nom.name }}" name="{{ form.inputs.nom.name }}" {{ form.inputs.nom.required }} value="{{ form.inputs.nom.value }}"/>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.attaque.name }}">Attaque</label>
			<input type="text" id="{{ form.inputs.attaque.name }}" name="{{ form.inputs.attaque.name }}" {{ form.inputs.attaque.required }} value="{{ form.inputs.attaque.value }}"/>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.defense.name }}">Défense</label>
			<input type="text" id="{{ form.inputs.defense.name }}" name="{{ form.inputs.defense.name }}" {{ form.inputs.defense.required }} value="{{ form.inputs.defense.value }}"/>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.niveau.name }}">Niveau/Rang</label>
			{{niveaux | selectedKey : form.inputs.niveau.value | input : 'select' : form.inputs.niveau.name}}
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Catégorie</legend>
				{{categories | selectedKey : form.inputs.categorie.value | input : 'radio' : form.inputs.categorie.name : 'required'}}
			</fieldset>
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Effet</legend>
				{{effets | selectedKey : form.inputs.effet.value | input : 'radio' : form.inputs.effet.name : 'required'}}
			</fieldset>
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Attribut</legend>
				{{attributs | selectedKey : form.inputs.attribut.value | input : 'radio' : form.inputs.attribut.name : 'required'}}
			</fieldset>
		</div>
		<div class="yu-input-container">
			<fieldset>
				<legend>Type</legend>
				<div class="yu-container">{{types | selectedKey : form.inputs.types.value | input : 'checkbox' : 'types[]'}}</div>
			</fieldset>
		</div>
		<div class="yu-input-container">
			<label for="{{ form.inputs.description.name }}">Description</label>
			<textarea name="{{ form.inputs.description.name }}" id="{{ form.inputs.description.name }}">{{ form.inputs.description.value }}</textarea>
		</div>
		<a href="{{ router.modifierCarte.path | path : carte.id }}"><button class="btn">Modifier la carte</button></a>
	</div>
</form>