<div class="yu-layout-row">
	<div class="yu-layout-column yu-padding-h">
		<span class="yu-title yu-align-right">{{user.utilisateur.username}}</span>
		<span class="yu-subtext">{{user.utilisateur.email}}</span>
	</div>
	<a href="{{ router.logout.path  | path : '' }}"><button class="btn">Déconnexion</button></a>
</div>