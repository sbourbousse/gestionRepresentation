<?php
session_start();
include_once "fix/connectBDD.php";
if (@$_SESSION['logged']==true && @$_SESSION['personneRight']==true)
{ 
    $req = 'SELECT personneNom,personnePrenom,personneCivilite,personneFonction,personneId
    FROM Personne
    ORDER BY personneNom';

    $res=mysqli_query($maBase,$req);
    while ($unPersonne = mysqli_fetch_assoc($res))
    {
        if($unPersonne['personneFonction']!='') $fonction = '('.$unPersonne['personneFonction'].')';
        else if ($unPersonne['personneFonction']=='') $fonction = $unPersonne['personneFonction'];

        if($unPersonne['personneCivilite']=='H') $civilite = 'Monsieur';
        else if ($unPersonne['personneCivilite']=='F') $civilite = 'Madame';
        else $civilite='';

        $listePersonne[]="<tr>
        <td>".$civilite."</td>
        <td>".strtoupper($unPersonne['personneNom'])."</td>
        <td>".$unPersonne['personnePrenom']."</td>
        <td>".$fonction.'</td>
        <td>
        <form method="GET" action="action.php">
        <input type="hidden" name="nomPersonne" value="'.$unPersonne['personneNom'].'">
        <input type="hidden" name="prenomPersonne" value="'.$unPersonne['personnePrenom'].'">
        <input type="hidden" name="civilitePersonne" value="'.$unPersonne['personneCivilite'].'">
        <input type="hidden" name="fonctionPersonne" value="'.$unPersonne['personneFonction'].'">
        <input type="hidden" name="idPersonne" value="'.$unPersonne['personneId'].'">
        <button type="submit" name="editPersonne" class="btn btn-warning px-3 fa-md"><i class="fas fa-pen fa-xs"></i></button>
        <button type="submit" name="deletePersonne" class="btn btn-danger px-3 fa-md"><i class="fas fa-trash fa-xs"></i></button>
        </form>
        </td>
        </tr>';
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Gestion des personnes</title>
        <?php include 'fix/head.inc.php'; ?>
    </head>
    <body class="bg">
    <?php include "fix/navbar.inc.php"; ?>


    <div class="container">

<h1 class="my-4 primary-heading white-text text-center">Gestion des personnes</h1>

<div class="grid">
<div class="grid-item col-md-12 mb-12">
    <div class="card mb-5 ">
    <div class="card-body" id="corps">
        <form method="POST" action="action.php">
            <?php if (isset($_POST['edit'])){ ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Vous modifiez les informations d'un personne</h4>
                <p>Modification en cours, rentrez les nouvelles informations et enregistrez</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row" id="inputAdd">
                <div class="col-md-3" id="colInputCivilite">
                    <select name="personneCivilite" id="inputPersonneCivilite" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                        <option  <?php if ($_POST['editCivilite']=="H") echo 'selected'; ?> value="H">Monsieur</option>
                        <option  <?php if ($_POST['editCivilite']=="F") echo 'selected'; ?> value="F">Madame</option>
                        <option <?php if ($_POST['editCivilite']=='') echo 'selected';?> value=''>Non renseigné</option>
                    </select>
                </div>
                <div class="col-md-3" id="colInputNom">
                    <input value="<?php echo $_POST['editNom'] ?>" type="text" name="nomPersonne" id="inputPersonneNom" class="form-control inputForms" placeholder="Nom" style="margin:0.375rem;font-size:14px;">
                </div>
                <div class="col-md-3" id="colInputPrenom">
                    <input value="<?php echo $_POST['editPrenom'] ?>" type="text" name="prenomPersonne" id="inputPersonnePrenom" class="form-control inputForms" placeholder="Prenom" style="margin:0.375rem;font-size:14px;">
                </div>
                <div class="col-md-3 text-center">
                    <input value="<?php echo $_POST['editFonction'] ?>" type="text" name="fonctionPersonne" id="inputPersonneFonction" class="form-control inputForms" placeholder="Fonction" style="margin:0.375rem;font-size:14px;">                </div>
                    <input type="hidden" value="<?php echo $_POST['editId']?>" name="idPersonne">
                </div>
            <?php } else { ?>
            <div class="row" id="inputAdd">
                <div class="col-md-3" id="colInputCivilite">
                    <select name="personneCivilite" id="inputPersonneCivilite" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                        <option  selected value="H">Monsieur</option>
                        <option  value="F">Madame</option>
                        <option value=''>Non renseigné</option>
                    </select>
                </div>
                <div class="col-md-3" id="colInputNom">
                    <input type="text" name="nomPersonne" id="inputPersonneNom" class="form-control inputForms" placeholder="Nom" style="margin:0.375rem;font-size:14px;">
                </div>
                <div class="col-md-3" id="colInputPrenom">
                    <input type="text" name="prenomPersonne" id="inputPersonnePrenom" class="form-control inputForms" placeholder="Prenom" style="margin:0.375rem;font-size:14px;">
                </div>
                <div class="col-md-3 text-center">
                    <input type="text" name="fonctionPersonne" id="inputPersonneFonction" class="form-control inputForms" placeholder="Fonction" style="margin:0.375rem;font-size:14px;">
                </div>
            </div>
            <?php } ?>
            <div class="text-center">
            <?php if (isset($_POST['edit'])) { ?>
            <button value="<?php echo $_POST['editNom'] ?>" type="submit" class="btn btn-md btn-success" name="updatePersonne"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter la personne</button>
            <?php }else { ?>
                <button type="submit" class="btn btn-md btn-success" name="addPersonne"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter la personne</button>
            <?php } ?>
            </div>
        </form>
        <table class="table table-bordered table-responsive-md table-striped text-center">
            <thead>
            <tr>
                <th class="text-center th-sm">Civilité</th>
                <th class="text-center th-lg">Nom</th>
                <th class="text-center th-lg">Prenom</th>
                <th class="text-center th-sm">Fonction</th>
                <th class="text-center th-lg">Editer</th>
            </tr>
            </thead>
            <tbody id="body-add-service">
                <?php foreach($listePersonne as $unPersonne){ echo $unPersonne; } ?>
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
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        percentPosition: true
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