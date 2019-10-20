<?php
session_start();
include_once "fix/connectBDD.php";
if (@$_SESSION['logged']==true && @$_SESSION['representationRight']==true)
{

    $req = 'SELECT eluId,eluNom,eluPrenom,eluCivilite,eluActif
    FROM Elu
    ORDER BY eluNom';
   
    $res=mysqli_query($maBase,$req);
    $i=0;
    while ($unElu = $res->fetch_assoc())
    {
        if($unElu['eluCivilite']=='H') $civilite = 'M';
        else if ($unElu['eluCivilite']=='F') $civilite = 'Mme';

        if($i==0) 
        {
            $listeElu[$i]='<option selected id="selectElu'.$unElu['eluId'].'" value="'.$unElu['eluId'].'">'.$civilite.' '.strtoupper($unElu['eluNom']).
            ' '.$unElu['eluPrenom'].'</option>';
        }
        else 
        {
            $listeElu[$i]='<option id="selectElu'.$unElu['eluId'].'" value="'.$unElu['eluId'].'">'.$civilite.' '.strtoupper($unElu['eluNom']).
            ' '.$unElu['eluPrenom'].'</option>';
        }
        $i++;
    }

    $req = 'SELECT personneId,personneNom,personnePrenom,personneFonction,personneCivilite
    FROM Personne
    ORDER BY personneNom';
   
    $res=mysqli_query($maBase,$req);
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);
    $i=0;
    while ($unePersonne= $res->fetch_assoc())
    {
        if($unePersonne['personneCivilite']=='H') $civilite = 'M';
        else if ($unePersonne['personneCivilite']=='F') $civilite = 'Mme';

        if($unePersonne['personneFonction']=='') $fonction = '';
        else $fonction = '('.$unePersonne['personneFonction'].')';

        if($i==0) 
        {
            $listePersonne[$i]='<option selected value="'.$unElu['personneId'].'">'.$civilite.' '.strtoupper($unePersonne['personneNom']).
            ' '.$unePersonne['personnePrenom'].' '.$fonction.'</option>';
        }
        else 
        {
            $listePersonne[$i]='<option value="'.$unePersonne['personneId'].'">'.$civilite.' '.strtoupper($unePersonne['personneNom']).
            ' '.$unePersonne['personnePrenom'].' '.$fonction.'</option>';
        }
        $i++;
    }

    $req = 'SELECT DISTINCT servicePoleNom
    FROM Service_CG

    WHERE servicePoleNom!=\'\' 
    ORDER BY servicePoleNom';
   
    $res=mysqli_query($maBase,$req);
    $i=0;
    while ($unService = $res->fetch_assoc())
    {
        $listeService[$i]='<option value="'.$unService['servicePoleNom'].'">'.$unService['servicePoleNom'].'</option>';
        $i++;
    }

    $req = 'SELECT typeAssembleNom,typeAssembleId
    FROM Type_Assemble
    ORDER BY typeAssembleNom';
   
    $res=mysqli_query($maBase,$req);
    while ($unType = $res->fetch_assoc())
    {
        $listeType[]='<option value="'.$unType['typeAssembleId'].'">'.$unType['typeAssembleNom'].'</option>';
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fix/head.inc.php'; ?>
        <title>Ajout d'instance</title>
    </head>
    <body class="bg">

    <?php include "fix/navbar.inc.php"; ?>

    <div class="container">

        <h1 class="my-4 primary-heading white-text text-center">Nouvelle instance</h1>

        <div class="grid">
        <div class="grid-item col-md-12 mb-12">
            <div class="card mb-5">
            <div class="card-body">

                    <?php if(isset($_POST['add'])) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Instance enregistrée !</h4>
                        <p><?php echo $_POST['addIntitule'] ; ?> a bien été ajouté dans la liste.</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <?php } else if (isset($_POST['fail'])) {?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h4 class="alert-heading">Erreur !</h4>
                        <p>Une erreur s'est produite, l'instance n'a pas pu être ajouté.</p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php } ?>
                    <form method="POST" action="action.php" id="representationForm">
                        <div class="row mb-5">
                            <div class="col-md-8">
                                <label for="nomInstance" class="grey-text font-weight-light test">Nom de l'instance</label>
                                <input type="text" id="nomInstance" name="nomInstance" class="form-control" maxlength="200" required>
                            </div>
                            <div class="col-md-4">
                            <label for="assembleType" class="grey-text font-weight-light">Type d'acte</label>
                                <select id="assembleType" name="assembleType" class="browser-default custom-select" required>
                                    <?php foreach ($listeType as $unType) { echo $unType; } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-4">
                                <label for="numDelib" class="grey-text font-weight-light">Numéro délibération</label>
                                <input type="number" id="numDelib" name="numDelib" class="form-control" max="65535">
                            </div>
                            <div class="col-md-4">
                            <label for="dateInstance" class="grey-text font-weight-light">Date de l'acte</label>
                                <input type="date" value="<?php echo date('Y').'-'.date('m').'-'.date('d')?>" id="dateInstance" name="dateInstance" class="form-control" required>
                                </select>
                            </div>
                            </div>
                            <div class="col-md-2">
                        </div>
                        <div class="row mb-5">
                        <div class="col-md-4">
                            <label for="inputServicePole" class="grey-text font-weight-light">Pôle</label>
                                <select id="inputServicePole" class="browser-default custom-select" name="servicePole" onchange="afficheServiceDirection()">
                                    <option value="">Choisir un pôle</option>
                                    <?php foreach($listeService as $unService){ echo $unService; } ?>
                                </select>
                            </div>                            
                            <div class="col-md-4 ">
                            <label for="inputServiceDirection" class="grey-text font-weight-light">Direction</label>
                                <select id="inputServiceDirection" class="browser-default custom-select" name="serviceDirection" onchange="afficheService()">
                                    <option selected value=""></option>
                                </select>
                            </div>
                            <div class="col-md-4">
                            <label for="inputService" class="grey-text font-weight-light" >Service</label>
                                <select id="inputService" class="browser-default custom-select" name="service">
                                    <option selected value=""></option>
                                </select>
                                <div class="text-center">
                                    <button type="button" id="selectDirection" class="btn btn-info" onclick="noDirection()">Voir les services sans directions</button>
                                </div>
                            </div>
                        </div>




                        <div class="row mb-5" >
                            <div class="col-md-4 text-center ">
                            <p><b>Titulaire(s)</b></p>
                            <div id="tableTitulaire" class="table-editable">
                                <div class="row mb-3" style="padding-left:15px;padding-right:15px;">
                                    <select id="listeEluTitulaire" class="browser-default custom-select col-8">
                                    <?php foreach($listeElu as $unElu){ echo $unElu; } ?>
                                    </select>
                                    <span id="table-add-Titulaire" class="table-add float-right col-4"><a href="#!" class="text-success"><i
                                        class="fas fa-plus-circle fa-2x" aria-hidden="true"></i></a></span>
                                </div>
                                <table class="table table-bordered table-responsive-md table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center col-md-8">Nom et prenom </th>
                                        <th class="text-center col-md-4">Supprimer</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-add-titulaire">
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <div class="col-md-4 text-center">
                            <p><b>Suppléant(s)</b><p>
                            <div id="tableSuppleant" class="table-editable">
                                <div class="row mb-3" style="padding-left:15px;padding-right:15px;">
                                    <select id="listeEluSuppleant" class="browser-default custom-select col-8">
                                        <?php foreach($listeElu as $unElu){ echo $unElu; } ?>
                                    </select>
                                    <span id="table-add-Suppleant" class="table-add float-right col-4"><a href="#!" class="text-success"><i
                                            class="fas fa-plus-circle fa-2x" aria-hidden="true"></i></a></span>
                                </div>
                                <table class="table table-bordered table-responsive-md table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center col-md-8">Nom et prenom </th>
                                        <th class="text-center col-md-4">Supprimer</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-add-suppleant">
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <div class="col-md-4 text-center">
                            <p><b>Personnalité(s)</b></p>
                            <div id="tablePersonnalite" class="table-editable">
                                <div class="row mb-3" style="padding-left:15px;padding-right:15px;">
                                    <select id="listePersonne" class="browser-default custom-select col-8">
                                        <?php foreach($listePersonne as $unePersonne){ echo $unePersonne; } ?>
                                    </select>
                                    <span id="table-add-Personnalite" class="table-add float-right col-4"><a href="#!" class="text-success"><i
                                            class="fas fa-plus-circle fa-2x" aria-hidden="true"></i></a></span>
                                </div>
                                <table class="table table-bordered table-responsive-md table-striped text-center">
                                    <thead>
                                    <tr>
                                        <th class="text-center col-md-8">Nom et prenom </th>
                                        <th class="text-center col-md-4">Supprimer</th>
                                    </tr>
                                    </thead>
                                    <tbody id="body-add-personnalite">
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="form-group col-md-6">
                                <label for="exampleFormControlTextarea1" class="grey-text font-weight-light" >Fondement Juridique</label>
                                <textarea class="form-control rounded-0" id="exampleFormControlTextarea1" rows="5" name="fondementJuridique"></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleFormControlTextarea1" class="grey-text font-weight-light">Observations</label>
                                <textarea class="form-control rounded-0" id="exampleFormControlTextarea1" rows="5" name="observation"></textarea>
                            </div>
                        </div>
                        <div class="text-center py-4 mt-3">
                            <input type="hidden" name="addRepresentation">
                            <button class="btn btn-outline-success" type="button" onclick="validation()">Valider<i class="fa fa-paper-plane-o ml-2"></i></button>
                        </div>
                    </form>
                    
                    

            </div>
            </div>
        </div>
        </div>







    <?php if ($_SESSION['echecAuth']==1 ||$_SESSION['demandeAuth']==1) include "fix/connectModal.php"; ?>   
    <?php include 'fix/scripts.inc.php'; ?>
    <script type="text/javascript" src="js/formulaire.js"></script>

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
else
{
    include "fix/error.inc.php";
}
$_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;
if(!isset($_SESSION['login'])) $_SESSION['login']='invite';
?>
