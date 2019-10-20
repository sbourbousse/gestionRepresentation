<?php
session_start();
include_once "fix/connectBDD.php";
if (@$_SESSION['logged']==true && @$_SESSION['representationRight']==true)
{
if (@isset($_POST['edit']))
{
    $req = 'SELECT representationId,representationIntitule,typeAssembleId,representationDateAssemble,representationNumDelib,representationFondementJuridique,representationObservation,servicePoleNom,serviceDirectionNom,serviceCGNom
    FROM Representation
    WHERE representationId='.$_POST['editId'];
    $res=mysqli_query($maBase,$req);
    $row = mysqli_fetch_array($res, MYSQLI_ASSOC);


    $req = 'SELECT eluId,eluNom,eluPrenom,eluCivilite,eluActif
    FROM Elu
    ORDER BY eluNom';
   
    $res=mysqli_query($maBase,$req);
    $i=0;
    while ($unElu = $res->fetch_assoc())
    {
        $indexElu[$unElu['eluId']]=$i;
        $i++;
    }
    
    $req = 'SELECT personneId,personneNom,personnePrenom,personneCivilite,personneFonction
    FROM Personne
    ORDER BY personneNom';
   
    $res=mysqli_query($maBase,$req);
    $i=0;
    while ($unePersonne = $res->fetch_assoc())
    {
        $indexPersonne[$unePersonne['personneId']]=$i;
        $i++;
    }

    $reqTit = 'SELECT Titulaire.eluId,eluNom,eluPrenom,eluCivilite
    FROM Titulaire natural join Elu
    WHERE representationId='.$_POST['editId'];   
    $res=mysqli_query($maBase,$reqTit);
    $i=0;
    while ($unTitulaire = $res->fetch_assoc())
    {
        if($unTitulaire['eluCivilite']=='H')
        $civilite='M.';
        else if ($unTitulaire['eluCivilite']=='F')
        $civilite='Mme';
        $listeTitulaireSuppleant[]=$unTitulaire['eluId'];
        $tableTitulaire[]='<tr id="tableEluTit'.$unTitulaire['eluId'].'"><td>'.$civilite.' '.strtoupper($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'].'</td><td><span class="table-remove"><button type="button" onclick="removeTit(tableEluTit'.$unTitulaire['eluId'].',\''.$indexElu[$unTitulaire['eluId']].'\')" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Supprimer</button></span></td></tr>';
        $inputTitulaire[]='<input type="hidden" id="inputEluTit'.$indexElu[$unTitulaire['eluId']].'" name="titulaire[]" value="'.$unTitulaire['eluId'].'">';
        $i++;
    }

    $reqSup = 'SELECT Suppleant.eluId,eluNom,eluPrenom,eluCivilite
    FROM Suppleant natural join Elu
    WHERE representationId='.$_POST['editId'];   
    $res=mysqli_query($maBase,$reqSup);
    $i=0;
    while ($unSuppleant = $res->fetch_assoc())
    {
        if($unSuppleant['eluCivilite']=='H')
        $civilite='M.';
        else if ($unSuppleant['eluCivilite']=='F')
        $civilite='Mme';

        $listeTitulaireSuppleant[]=$unSuppleant['eluId'];

        $tableSuppleant[]='<tr id="tableEluSup'.$unSuppleant['eluId'].'"><td>'.$civilite.' '.strtoupper($unSuppleant['eluNom']).' '.$unSuppleant['eluPrenom'].'</td><td><span class="table-remove"><button type="button" onclick="removeSup(tableEluSup'.$unSuppleant['eluId'].',\''.$indexElu[$unSuppleant['eluId']].'\')" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Supprimer</button></span></td></tr>';
        $inputSuppleant[]='<input type="hidden" id="inputEluSup'.$indexElu[$unSuppleant['eluId']].'" name="suppleant[]" value="'.$unSuppleant['eluId'].'">';

        $i++;
    }

    $req = 'SELECT eluId,eluNom,eluPrenom,eluCivilite,eluActif
    FROM Elu
    ORDER BY eluNom';
   
    $res=mysqli_query($maBase,$req);
    $i=0;
    while ($unElu = $res->fetch_assoc())
    {
        $disabled='';
        if($unElu['eluCivilite']=='H') $civilite = 'M';
        else if ($unElu['eluCivilite']=='F') $civilite = 'Mme';

        if(isset($listeTitulaireSuppleant)) foreach ($listeTitulaireSuppleant as $unTitulaireSuppleant)
        {
            if ($unElu['eluId']==$unTitulaireSuppleant)
            $disabled='disabled="true"';


        }

        $listeElu[$i]='<option '.$disabled.' id="selectElu'.$unElu['eluId'].'" value="'.$unElu['eluId'].'">'.$civilite.' '.strtoupper($unElu['eluNom']).
        ' '.$unElu['eluPrenom'].'</option>';

        $i++;
    }

    $reqPers = 'SELECT Personnalite.personneId,personneNom,personnePrenom,personneCivilite,personneFonction
    FROM Personnalite natural join Personne
    WHERE representationId='.$_POST['editId'];   
    $res=mysqli_query($maBase,$reqPers);
    $i=0;
    while ($unePersonne = $res->fetch_assoc())
    {
        if($unePersonne['personneCivilite']=='H')
        $civilite='M.';
        else if ($unePersonne['personneCivilite']=='F')
        $civilite='Mme';

        if($unePersonne['personneFonction']!='') $fonction='('.$unePersonne['personneFonction'].')';
        else $fonction='';

        $listeIdPersonne[]=$unePersonne['personneId'];

        $tablePersonne[]='<tr id="tablePers'.$unePersonne['personneId'].'"><td>'.$civilite.' '.strtoupper($unePersonne['personneNom']).' '.$unePersonne['personnePrenom'].' '.$fonction.'</td><td><span class="table-remove"><button type="button" onclick="removePers(tablePers'.$unePersonne['personneId'].',\''.$indexPersonne[$unePersonne['personneId']].'\')" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Supprimer</button></span></td></tr>';
        $inputPersonne[]='<input type="hidden" id="inputPers'.$indexPersonne[$unePersonne['personneId']].'" name="personnalite[]" value="'.$unePersonne['personneId'].'">';

        $i++;
    }

    $req = 'SELECT personneId,personneNom,personnePrenom,personneFonction,personneCivilite
    FROM Personne
    ORDER BY personneNom';
   
    $res=mysqli_query($maBase,$req);
    $i=0;
    while ($unePersonne= $res->fetch_assoc())
    {
        $disabled='';
        if($unElu['eluCivilite']=='H') $civilite = 'M';
        else if ($unElu['eluCivilite']=='F') $civilite = 'Mme';

        if($unePersonne['personneFonction']=='') $fonction = '';
        else $fonction = '('.$unePersonne['personneFonction'].')';

        if (isset($listeIdPersonne)) foreach ($listeIdPersonne as $unePers)
        {
            if ($unePersonne['personneId']==$unePers)
            $disabled='disabled="true"';


        }

        $listePersonne[$i]='<option '.$disabled.' value="'.$unePersonne['personneId'].'">'.$civilite.' '.strtoupper($unePersonne['personneNom']).
        ' '.$unePersonne['personnePrenom'].' '.$fonction.'</option>';

        $i++;
    }

    $req = 'SELECT servicePoleNom,serviceDirectionNom,serviceCGNom
    FROM Service_CG 
    WHERE serviceCGNom="'.$row['serviceCGNom'].'" AND serviceDirectionNom="'.$row['serviceDirectionNom'].'" AND servicePoleNom="'.$row['servicePoleNom'].'"
    ORDER BY servicePoleNom';
   
    $res=mysqli_query($maBase,$req);
    $rowService = mysqli_fetch_array($res, MYSQLI_ASSOC);

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
        if($unType['typeAssembleId']==$row['typeAssembleId'])
        $listeType[]='<option selected value="'.$unType['typeAssembleId'].'">'.$unType['typeAssembleNom'].'</option>';
        else
        $listeType[]='<option value="'.$unType['typeAssembleId'].'">'.$unType['typeAssembleNom'].'</option>';

    }

?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fix/head.inc.php'; ?>
        <title>Modification de la représentation</title>
    </head>
    <body class="bg">

    <?php include "fix/navbar.inc.php"; ?>

    <div class="container">

        <h1 class="my-4 primary-heading white-text text-center">Modifier la représentation</h1>

        <div class="grid">
        <div class="grid-item col-md-12 mb-12">
            <div class="card mb-5">
            <div class="card-body" id="bodyForm">

                    <?php if(isset($_POST['update'])) { ?>
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
                                <input value="<?php echo $row['representationIntitule']; ?>" type="text" id="nomInstance" name="nomInstance" class="form-control" maxlength="200" required>
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
                                <input value="<?php echo $row['representationNumDelib']; ?>" type="number" id="numDelib" name="numDelib" class="form-control" max="65535">
                            </div>
                            <div class="col-md-4">
                            <label for="dateInstance" class="grey-text font-weight-light">Date de l'acte</label>
                                <input type="date" value="<?php echo $row['representationDateAssemble']; ?>" id="dateInstance" name="dateInstance" class="form-control" required>
                                </select>
                            </div>
                            </div>
                            <div class="col-md-2">
                        </div>
                        <div class="row mb-5" id="rowAfficheService">
                        <div class="col-md-4">
                            <label class="grey-text font-weight-light">Pôle</label>
                                <input disabled type="text" class="form-control" value="<?php echo $rowService['servicePoleNom']; ?>">
                                <input type="hidden" name="servicePole" value="<?php echo $rowService['servicePoleNom']; ?>">
                                <div class="text-center">
                                    <button type="button" id="changeService" class="btn btn-primary" onclick="modifService()">Modifier</button>
                                </div>
                            </div>                            
                            <div class="col-md-4 ">
                            <label class="grey-text font-weight-light">Direction</label>
                                <input disabled type="text" class="form-control" value="<?php echo $rowService['serviceDirectionNom']; ?>">
                                <input type="hidden" name="serviceDirection" value="<?php echo $rowService['serviceDirectionNom']; ?>">
                            </div>
                            <div class="col-md-4">  
                            <label  class="grey-text font-weight-light" >Service</label>
                                <input disabled type="text" class="form-control" value="<?php echo $rowService['serviceCGNom']; ?>">
                                <input type="hidden" name="service" value="<?php echo $rowService['serviceCGNom']; ?>">
                            </div>
                        </div>
                        <div class="row mb-5" id="rowModifService">
                        <div class="col-md-4">
                            <label for="inputServicePole" class="grey-text font-weight-light">Pôle</label>
                                <select id="inputServicePole" class="browser-default custom-select" name="servicePole" onchange="afficheServiceDirection()">
                                    <option value="">Choisir un pôle</option>
                                    <?php foreach($listeService as $unService){ echo $unService; } ?>
                                </select>
                                <div class="text-center">
                                    <button type="button" id="changeService" class="btn btn-primary" onclick="modifService()">Laisser</button>
                                </div>
                            </div>                            
                            <div class="col-md-4 ">
                            <label for="inputServiceDirection" class="grey-text font-weight-light">Direction</label>
                                <select id="inputServiceDirection" class="browser-default custom-select" name="serviceDirection" onchange="afficheService()">
                                    <option selected value=""></option>
                                </select>
                                <div class="text-center">
                                    <button type="button" id="selectDirection" class="btn btn-warning" onclick="noDirection()">Voir les services sans directions</button>
                                </div>
                            </div>
                            <div class="col-md-4">
                            <label for="inputService" class="grey-text font-weight-light" >Service</label>
                                <select id="inputService" class="browser-default custom-select" name="service">
                                    <option selected value=""></option>
                                </select>
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
                                    <?php if(isset($tableTitulaire)) foreach(@$tableTitulaire as $ligneTitulaire) echo $ligneTitulaire; ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            <div class="col-md-4 text-center">
                            <p><b>Suppléant(s)</b><p>
                            <div id="tableSuppleant" class="table-editable">
                                <div class="row mb-3" style="padding-left:15px;padding-right:15px;">
                                    <select id="listeEluSuppleant" class="browser-default custom-select col-8">
                                        <?php foreach($listeElu as $unElu){ echo @$unElu; } ?>
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
                                    <?php if(isset($tableSuppleant)) foreach(@$tableSuppleant as $ligneSuppleant) echo @$ligneSuppleant; ?>
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
                                        <?php if(isset($tablePersonne)) foreach(@$tablePersonne as $lignePersonne) echo @$lignePersonne; ?>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="form-group col-md-6">
                                <label for="exampleFormControlTextarea1" class="grey-text font-weight-light" >Fondement Juridique</label>
                                <textarea class="form-control rounded-0" id="exampleFormControlTextarea1" rows="5" name="fondementJuridique"><?php echo $row['representationFondementJuridique']; ?></textarea>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleFormControlTextarea1" class="grey-text font-weight-light">Observations</label>
                                <textarea class="form-control rounded-0" id="exampleFormControlTextarea1" rows="5" name="observation"><?php echo $row['representationObservation']; ?></textarea>
                            </div>
                        </div>
                        <div class="text-center py-4 mt-3">
                            <input type="hidden" name="updateRepresentation">
                            <input type="hidden" name="representationId" value="<?php echo $row['representationId'] ?>">
                            <button class="btn btn-outline-orange" type="button" onclick="validation()">Modifier<i class="fa fa-paper-plane-o ml-2"></i></button>
                        </div>
                        <?php if(isset($inputTitulaire)) foreach ($inputTitulaire as $inputunTitulaire) echo $inputunTitulaire; 
                        if(isset($inputSuppleant)) foreach ($inputSuppleant as $inputunSuppleant) echo $inputunSuppleant;
                        if(isset($inputPersonne)) foreach ($inputPersonne as $inputunePersonne) echo $inputunePersonne; ?>
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
else include "fix/error.inc.php";
}
else
{
    include "fix/error.inc.php";
}
$_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;
if(!isset($_SESSION['login'])) $_SESSION['login']='invite';
?>
