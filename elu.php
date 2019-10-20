<?php
session_start();
include_once "fix/connectBDD.php";
if (@$_SESSION['logged']==true && @$_SESSION['eluRight']==true)
{ 
    $req = 'SELECT eluNom,eluPrenom,eluCivilite,eluActif,eluId
    FROM Elu
    ORDER BY eluNom';
    $res=mysqli_query($maBase,$req);
    while ($unElu = mysqli_fetch_assoc($res))
    {
        if($unElu['eluActif']==1) $actif = '<span class="badge badge-pill green"><i class="far fas fa-check fa-lg ml-2 mr-2"></i></span>';
        else if ($unElu['eluActif']==0) $actif = '<span class="badge badge-pill orange"><i class="fas fa-times fa-lg ml-2 mr-2"></i></span>';

        if($unElu['eluCivilite']=='H') $civilite = 'Monsieur';
        else if ($unElu['eluCivilite']=='F') $civilite = 'Madame';

        $listeElu[]="<tr>
        <td>".$civilite."</td>
        <td>".strtoupper($unElu['eluNom'])."</td>
        <td>".$unElu['eluPrenom']."</td>
        <td>".$actif.'</td>
        <td>
        <form method="GET" action="action.php">
        <input type="hidden" name="nomElu" value="'.$unElu['eluNom'].'">
        <input type="hidden" name="prenomElu" value="'.$unElu['eluPrenom'].'">
        <input type="hidden" name="civiliteElu" value="'.$unElu['eluCivilite'].'">
        <input type="hidden" name="actifElu" value="'.$unElu['eluActif'].'">
        <input type="hidden" name="idElu" value="'.$unElu['eluId'].'">
        <button type="submit" name="editElu" class="btn btn-warning px-3 fa-md"><i class="fas fa-pen fa-xs"></i></button>
        <button type="submit" name="deleteElu" class="btn btn-danger px-3 fa-md"><i class="fas fa-trash fa-xs"></i></button>
        </form></td>
        </tr>';
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<?php include 'fix/head.inc.php'; ?>
		<title>Gestion des représentants</title>
	</head>
	<body class="bg">
		<?php include "fix/navbar.inc.php"; ?>
		<div class="container">
			<h1 class="my-4 primary-heading white-text text-center">Gestion des représentants</h1>
			<div class="grid">
				<div class="grid-item col-md-12 mb-12">
					<div class="card mb-5">
						<div class="card-body" id="corps">
							<form method="POST" action="action.php" enctype="multipart/form-data">
								<?php if (isset($_POST['edit'])){ ?>
								<div class="alert alert-warning alert-dismissible fade show" role="alert">
									<h4 class="alert-heading">Vous modifiez les informations d'un repésentant</h4>
									<p>
										Modification en cours, rentrez les nouvelles informations et enregistrez
									</p>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								</div>
								<div class="row" id="inputAdd">
									<div class="col-md-3" id="colInputCivilite">
										<select name="eluCivilite" id="inputEluCivilite" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
											<option  <?php if ($_POST['editCivilite']=="H") echo 'selected'; ?> value="H">Monsieur</option>
										    <option <?php if ($_POST['editCivilite']=="F") echo 'selected'; ?> value="F">Madame</option>
								        </select>
							        </div>
							<div class="col-md-3" id="colInputNom">
								<input value="<?php echo $_POST['editNom'] ?>" type="text" name="nomElu" id="inputEluNom" class="form-control inputForms" placeholder="Nom" style="margin:0.375rem;font-size:14px;">
							</div>
							<div class="col-md-3" id="colInputPrenom">
								<input value="<?php echo $_POST['editPrenom'] ?>" type="text" name="prenomElu" id="inputEluPrenom" class="form-control inputForms" placeholder="Prenom" style="margin:0.375rem;font-size:14px;">
							</div>
							<div class="col-md-3 text-center">
								<div class="custom-control custom-switch">
									<input type="checkbox" class="custom-control-input" id="customSwitch1" name="actifElu" style="margin:0.375rem;"
									<?php if ($_POST['editActif']=="1") echo 'checked';?>>
									<label class="custom-control-label" for="customSwitch1" style="margin:0.375rem;">Actif</label>
								</div>
								<input type="hidden" value="<?php echo $_POST['editId']?>" name="idElu">
								<button value="<?php echo $_POST['editNom'] ?>" type="submit" class="btn btn-md btn-success" name="updateElu"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter l'elu</button>
							</div>
						</div>
						<?php } else if (isset($_POST['delFail'])){?> 
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<h4 class="alert-heading">La supression du représentant à echoué</h4>
								<p>
									Verifiez que l'elu n'est pas présent dans des instances en le recherchant dans l'onglet "Représentation"
								</p>
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							</div>
							<div class="row" id="inputAdd">
							<div class="col-md-3" id="colInputCivilite">
								<select name="eluCivilite" id="inputEluCivilite" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
									<option  selected value="H">Monsieur</option>
									<option  value="F">Madame</option>
								</select>
							</div>
							<div class="col-md-3" id="colInputNom">
								<input type="text" name="nomElu" id="inputEluNom" class="form-control inputForms" placeholder="Nom" style="margin:0.375rem;font-size:14px;">
							</div>
							<div class="col-md-3" id="colInputPrenom">
								<input type="text" name="prenomElu" id="inputEluPrenom" class="form-control inputForms" placeholder="Prenom" style="margin:0.375rem;font-size:14px;">
							</div>
							<div class="col-md-3">
								<div class="custom-file" style="margin:0.375rem;font-size:14px;">
									<input type="file" class="custom-file-input inputForms" id="inputGroupFile01" 
									aria-describedby="inputGroupFileAddon01" name="fichier" accept=".jpg">
									<label class="custom-file-label" for="inputGroupFile01">Image de profil</label>
								</div>
								<input type="hidden" name="actifElu" value="on">
							</div>
						</div>
						<div class="text-center">
							<button type="submit" class="btn btn-md btn-success " name="addElu"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter le représentant</button>
						</div>	
						<?php } else { ?>
						<div class="row" id="inputAdd">
							<div class="col-md-3" id="colInputCivilite">
								<select name="eluCivilite" id="inputEluCivilite" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
									<option  selected value="H">Monsieur</option>
									<option  value="F">Madame</option>
								</select>
							</div>
							<div class="col-md-3" id="colInputNom">
								<input type="text" name="nomElu" id="inputEluNom" class="form-control inputForms" placeholder="Nom" style="margin:0.375rem;font-size:14px;">
							</div>
							<div class="col-md-3" id="colInputPrenom">
								<input type="text" name="prenomElu" id="inputEluPrenom" class="form-control inputForms" placeholder="Prenom" style="margin:0.375rem;font-size:14px;">
							</div>
							<div class="col-md-3">
								<div class="custom-file" style="margin:0.375rem;font-size:14px;">
									<input type="file" class="custom-file-input inputForms" id="inputGroupFile01" 
									aria-describedby="inputGroupFileAddon01" name="fichier" accept=".jpg">
									<label class="custom-file-label" for="inputGroupFile01">Image de profil</label>
								</div>
								<input type="hidden" name="actifElu" value="on">
							</div>
						</div>
						<div class="text-center">
							<button type="submit" class="btn btn-md btn-success " name="addElu"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter le représentant</button>
						</div>
						<?php } ?>
					</form>
					<table class="table table-bordered table-responsive-md table-striped text-center">
						<thead>
							<tr>
								<th class="text-center th-sm">
									Civilité
								</th>
								<th class="text-center th-lg">
									Nom
								</th>
								<th class="text-center th-lg">
									Prenom
								</th>
								<th class="text-center th-sm">
									Actif
								</th>
								<th class="text-center th-lg">
									Editer
								</th>
							</tr>
						</thead>
						<tbody id="body-add-service">
							<?php foreach($listeElu as $unElu){ echo $unElu; } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($_SESSION['echecAuth']==1 ||$_SESSION['demandeAuth']==1) include "fix/connectModal.php"; ?>
<?php include 'fix/scripts.inc.php'; ?>
<script type="text/javascript" src="js/service.js"></script>
</body>
<script type="text/javascript">
	new WOW().init();
	$(window).on('load',function(){
		$('#modalSubscriptionForm').modal('show');
	});
	$('.grid').masonry({
		itemSelector: '.grid-item',columnWidth: '.grid-sizer',percentPosition: true
	});
</script>
</html>

<?php
}
else include "fix/error.inc.php";

$_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;
if(!isset($_SESSION['login'])){ $_SESSION['logged']=0;$_SESSION['login']='invite';}
if($_SESSION['login']=='invite') $_SESSION['logged']=0;
?>