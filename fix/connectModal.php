<div class="modal fade " id="modalSubscriptionForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"  aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content rounded ">
			<div class="modal-header text-center" style="background-color:#102940;">
				<h4 class="modal-title w-100 font-weight-bold white-text">Connexion</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<form method="POST" action="connexion.php">
				<div class="modal-body mx-3">
					<div class="md-form mb-5">
						<i class="fas fa-user prefix grey-text"></i>
						<input type="text" id="form3" name="login" class="form-control validate" required>
						<label data-error="erreur de saisie" data-success="correct" for="form3">Nom d'utilisateur</label>
					</div>
					<div class="md-form mb-4">
						<i class="fas fa-lock prefix grey-text"></i>
						<input type="password" id="form2" name="password" class="form-control validate">
						<label data-error="erreur de saisie" data-success="correct" for="form2" required>Mot de passe</label>
					</div>
					<?php if(@$_SESSION['echecAuth']==1){ echo '<div class="alert alert-warning" role="alert">Nom d\'utilisateur ou mot de passe incorrect</div>'; }?></div>
					<div class="modal-footer d-flex justify-content-center">
						<button name="connect" class="btn btn-success">se connecter <i class="fas fa-paper-plane-o ml-1"></i></button>
					</div>
					<!--<div><a onclick="register()">Creer un compte</a><a>Mot de passe oublié ? </a></div>-->
					<div class="text-center mb-3">
						Cliquez sur la croix pour continuer en tant qu'invité                
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
