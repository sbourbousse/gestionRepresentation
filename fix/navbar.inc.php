<!--Navbar-->
<nav class="navbar navbar-expand-lg navbar-dark scrolling-navbar rgba-black-light py-4" style="padding-top:12px!important;padding-bottom:12px!important;">
	<div class="container">
		<!-- Navbar brand -->
		<a class="navbar-brand" href="/"><img src="img/logoNavBar.png" height="64" class="mr-4"></a>
		<!-- Collapse button -->
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
		<!-- Collapsible content -->
		<div class="collapse navbar-collapse" id="basicExampleNav">
			<!-- Links -->
			<ul class="navbar-nav mr-auto ">
				<li class="nav-item <?php if($_SERVER['REQUEST_URI']=='/index.php'||$_SERVER['REQUEST_URI']=='/') echo 'active';?>"><a class="nav-link" href="index.php">Accueil</a></li>
				<li class="nav-item <?php if($_SERVER['REQUEST_URI']=='/liste.php') echo 'active';?>"><a class="nav-link" href="liste.php">Représentations</a></li>
				<?php if (@$_SESSION['logged']==true) {?>
				<?php if (@$_SESSION['representationRight']==true) { ?> <li class="nav-item <?php if($_SERVER['REQUEST_URI']=='/formulaire.php') echo 'active';?>"><a class="nav-link " href="formulaire.php">Nouvelle instance</a></li> <?php } ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-555" data-toggle="dropdown"aria-haspopup="true" aria-expanded="false">Gestion</a>
					<div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
						<?php if (@$_SESSION['representationRight']==true) { ?> <a class="dropdown-item" href="gestion.php">Gérer les représentations</a> <?php } ?>
						<?php if (@$_SESSION['eluRight']==true) { ?> <a class="dropdown-item" href="elu.php">Gérer les représentants</a> <?php } ?>
						<?php if (@$_SESSION['personneRight']==true) { ?> <a class="dropdown-item" href="personnalite.php">Gérer les personnalités</a> <?php } ?>
						<?php if (@$_SESSION['serviceRight']==true) { ?> <a class="dropdown-item" href="service.php">Gérer les services</a> <?php }?>
						<!-- 
						<a class="dropdown-item" href="#">Gerer les représentation</a>
						 -->
					</div>
					<?php } ?>
				</li>
			</ul>
			<!-- Links -->
			<ul class="navbar-nav ml-auto nav-flex-icons">
				<?php if (@$_SESSION['logged']==true){ ?>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle mr-5" id="navbarDropdownMenuLink-555" data-toggle="dropdown"aria-haspopup="true" aria-expanded="false">
					<i class="fas fa-user-circle fa-lg mx-2"></i>
					</a>
					<div class="dropdown-menu dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
						<a  id="disconnectLink" onclick="document.getElementById('actionForm').submit()" class="dropdown-item"><i class="fas fa-sign-out-alt fa-lg pr-2"></i><b>Deconnexion</b></a>
					</div>
				</li>
				<form method="POST" action="connexion.php" id="actionForm">
					<?php }else  { ?>
					<li class="nav-item"><a  class="nav-link" id="connectLink" onclick="document.getElementById('actionForm').submit()" class="dropdown-item"><i class="fas fa-sign-in-alt pr-2"></i><b>Connexion</b></a></li>
					<form method="POST" action="connexion.php" id="actionForm">
						<input type="hidden" name="connectNav"/>
						<?php } ?>
					</form>
				</ul>
				<!--<form class="form-inline">
					<div class="md-form my-0">
						<input class="form-control mr-sm-2" id="inputNavSearch" type="text" placeholder="Rechercher" aria-label="Rechercher">
					</div>
				</form>-->
			</div>
			<!-- Collapsible content -->
		</div>
	</nav>
	<!--/.Navbar-->
