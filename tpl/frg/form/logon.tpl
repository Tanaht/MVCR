<div class="yu-layout-row">
	<form method="post" name="{{ form.name }}">
		<input type="text" name="{{ form.inputs._username.name }}" placeholder="username" {{ form.inputs._username.required }} value = "{{ form.inputs._username.value }}"/>
		<input type="password" name="{{ form.inputs._password.name }}" placeholder="password" {{ form.inputs._password.required }} value="{{ form.inputs._password.value }}"/>
		<button class="btn" name="logon">OK</button>
	</form>
	<a href="{{ router.inscription.path | path : '' }}"><button class="btn">S'inscrire</button></a>
</div>