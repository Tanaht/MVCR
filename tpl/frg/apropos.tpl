<div class="yu-block">
    <h3>A propos</h3>
    <h3>Numéros étudiant: 21302782</h3>
</div>
<div class="yu-block">
    <h3>Général</h3>
    <ul>
        <li>Le CSS à été généré avec un préprocesseur CSS: Sass</li>
        <li>L'application fonctionne avec un petit script Autoloader (Que je n'ai pas fait) qui permet d'éviter d'utiliser include ou require à chaque début de fichier</li>
        <li>Le dossier d'upload possède un fichier .htaccess qui désactive toutes les exécutions de scripts</li>
        <li>Le dossier app est protégé en accès web par un .htaccess</li>
        <li>Un dossier Tests est disponible dans le dossier app, il contient des scripts de test/exemple à utiliser avec la commande php</li>
    </ul>
</div>
<div class="yu-block">
    <h3>Le Routeur</h3>
    <p class="yu-align-justify">
        Toutes les routes possibles se trouve dans le fichier de configuration de l'application (Config.php), le routeur est capable d'y acceder, et grace aux types Callable, d'appeler les controlleur correspondant à l'url.
    </p>
</div>
<div class="yu-block">
    <h3>Le Controlleur</h3>
    <p class="yu-align-justify">
        Le controlleur d'une page n'est qu'une seule fonction, qui possède deux paramètre: Request et Response.
        Ces 2 paramètres sont des containers qui contiennent entre autre le template à afficher (qui peut être modifié par le controlleur), ou encore le type de requète envoyé au serveur, (GET ou POST), en s'il s'agit d'une requete POST, la Request peut demander à la Response de rediriger la page web (POST redirect GET),
    </p>
</div>
<div class="yu-block">
    <h3>La Vue</h3>
    <p class="yu-align-justify">
        Ce site web dispose d'un moteur de template qui intègre la gestion de variables, ces variables peuvent soit contenir un autre template, ce que j'appelle inflation, ou contenir une donnée à afficher.
        Le moteur de template possède aussi une technologie de filtre, c'est à dire que en fonction du type de filtre, la variable filtré sera modifié.
    </p>
</div>