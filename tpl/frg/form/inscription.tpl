<div class="yu-block">
    <form method="post" class="yu-layout-column">
        <label for="{{ form.inputs.login.name }}">Nom d'utilisateur:</label>
        <input id="{{ form.inputs.login.name }}" type="text" name="{{ form.inputs.login.name }}" placeholder="{{ form.inputs.login.name }}" {{ form.inputs.login.required }} value="{{ form.inputs.login.value }}"/>
        <span class="yu-warn">{{ form.errors.login}}</span>
        <label for="{{ form.inputs.mail.name }}">Adresse Email:</label>
        <input id="{{ form.inputs.mail.name }}" type="text" name="{{ form.inputs.mail.name }}" placeholder="{{ form.inputs.mail.name }}" {{ form.inputs.mail.required }} value="{{ form.inputs.mail.value }}"/>
        <span class="yu-warn">{{ form.errors.mail}}</span>
        <label for="{{ form.inputs.password.name }}">Mot de passe:</label>
        <input id="{{ form.inputs.password.name }}" type="password" name="{{ form.inputs.password.name }}" placeholder="{{ form.inputs.password.name }}" {{ form.inputs.password.required }}/>
        <button class="btn">Valider</button>
    </form>
</div>