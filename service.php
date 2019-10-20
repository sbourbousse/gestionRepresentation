<?php
include 'fix/functions.php';
session_start();
include_once "fix/connectBDD.php";
if (@$_SESSION['logged']==true && @$_SESSION['serviceRight']==true)
{
    $req = 'SELECT servicePoleNom,serviceDirectionNom,serviceCGNom,serviceActif
    FROM Service_CG
    ORDER BY serviceCGNOM';
    $res=mysqli_query($maBase,$req);
    while ($unService = mysqli_fetch_assoc($res))
    {
        if($unService['serviceActif']==1) $actif = '<span class="badge badge-pill green"><i class="far fas fa-check fa-lg ml-2 mr-2"></i></span>';
        else if ($unService['serviceActif']==0) $actif = '<span class="badge badge-pill orange"><i class="fas fa-times fa-lg ml-2 mr-2"></i></span>';

        $listeService[]="<tr>
        <td>".$unService['servicePoleNom']."</td>
        <td>".$unService['serviceDirectionNom']."</td>
        <td>".$unService['serviceCGNom']."</td>
        <td>".$actif.'</td>
        <td>
        <form method="GET" action="action.php">
        <input type="hidden" name="nomPoleService" value="'.$unService['servicePoleNom'].'">
        <input type="hidden" name="nomDirectionService" value="'.$unService['serviceDirectionNom'].'">
        <input type="hidden" name="nomService" value="'.$unService['serviceCGNom'].'">
        <input type="hidden" name="actifService" value="'.$unService['serviceActif'].'">
        <button type="submit" name="editService" class="btn btn-warning px-3 fa-md"><i class="fas fa-pen fa-xs"></i></button>
        <button type="submit" name="deleteService" class="btn btn-danger px-3 fa-md"><i class="fas fa-trash fa-xs"></i></button>
        </form></td>
        </tr>';
    }
    
    $req = 'SELECT DISTINCT servicePoleNom
    FROM Service_CG
    WHERE serviceActif = true
    ORDER BY serviceCGNOM';
   
    $res=mysqli_query($maBase,$req);
    while ($unServicePole = mysqli_fetch_assoc($res))
    {

        $inputServicePole[]='<option>'.$unServicePole['servicePoleNom'].'</option>';
        
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fix/head.inc.php'; ?>
        <title>Gestion des services</title>
    </head>
    <body class="bg">
        <?php include "fix/navbar.inc.php"; ?>

    <div class="container">

        <h1 class="my-4 primary-heading white-text text-center">Gestion des services</h1>

        <div class="grid">
        <div class="grid-item col-md-12 mb-12">
            <div class="card mb-5 ">
            <div class="card-body" id="corps">
                <form method="POST" action="action.php">
                <?php if (isset($_POST['edit'])) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Vous modifiez un service</h4>
                    <p>Le service est en modification, rentrez les nouvelles informations et enregistrez</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row" id="inputAdd">
                    <div class="col-md-3" id="colInputPole">
                        <select name="nomPoleService" id="inputServicePole" onchange="afficheServiceDirection()" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option selected ><?php echo $_POST['editPole']; ?></option>
                            <option value="NULL">POLE</option>
                            <?php foreach ($inputServicePole as $servicePole) { echo $servicePole;} ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputDirection">
                    
                        <select name="nomDirectionService" id="inputServiceDirection" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option selected ><?php echo $_POST['editDirection']; ?></option>
                            <option   value="NULL">DIRECTION</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputService">
                        <input name="nomService" type="text" id="inputService" class="form-control inputForms" value="<?php echo $_POST['editService']; ?>" placeholder="SERVICE" style="margin:0.375rem;">
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="actifService" style="margin:0.375rem;" <?php if ($_POST['actifService']==true) echo 'checked';?>>
                            <label class="custom-control-label" for="customSwitch1" style="margin:0.375rem;">Actif</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-5" id="inputAdd2">
                    <div class="col-md-3 text-center">
                        <button class="btn btn-md btn-info" type="button" onclick="permuteInputSelectPole()" id="buttonPole">Nouveau Pôle</button>
                    </div>
                    <div class="col-md-3 text-center">                   
                        <button class="btn btn-md btn-info" type="button"  onclick="permuteInputSelectDirection()" id="buttonDirection">Nouvelle Direction</button>
                    </div>
                    <div class="col-md-3 text-center">                   
                    </div>
                    <div class="col-md-3 text-center">                   
                        <button type="submit" name="addService" class="btn btn-md btn-warning "><i class="fas fa-pen fa-lg pr-2"></i>Modifier le service</button>
                    </div>
                </div>
                </form>
                <?php  } else if (isset($_POST['addFail'])){?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Erreur !</h4>
                    <p>Le service n'a pas pu être ajouté, verifiez que celui ci n'existe pas déjà.</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row" id="inputAdd">
                    <div class="col-md-3" id="colInputPole">
                        <select name="nomPoleService" id="inputServicePole" onchange="afficheServiceDirection()" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option selected value="NULL">POLE</option>
                            <?php foreach ($inputServicePole as $servicePole) { echo $servicePole;} ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputDirection">
                    
                        <select name="nomDirectionService" id="inputServiceDirection" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option  selected value="NULL">DIRECTION</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputService">
                        <input name="nomService" type="text" id="inputService" class="form-control inputForms" placeholder="SERVICE" style="margin:0.375rem;">
                    </div>
                    <div class="col-md-3 text-center">
                        <input type="hidden" value="on" name="actifService">
                        <button type="submit" class="btn btn-md btn-success" name="addService"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter le service</button>
                    </div>
                </div>
                </form>
                <div class="row mb-5" id="inputAdd2">
                    <div class="col-md-3 text-center">
                        <button class="btn btn-md btn-info" onclick="permuteInputSelectPole()" id="buttonPole">Nouveau Pôle</button>
                    </div>
                    <div class="col-md-3 text-center">                   
                        <button class="btn btn-md btn-info" onclick="permuteInputSelectDirection()" id="buttonDirection">Nouvelle Direction</button>
                    </div>
                </div>
                <?php } else if(isset($_POST['addSuccess'])){ ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h4 class="alert-heading">Service enregistré !</h4>
                    <p>Le service a bien été ajouté dans la liste.</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row" id="inputAdd">
                    <div class="col-md-3" id="colInputPole">
                        <select name="nomPoleService" id="inputServicePole" onchange="afficheServiceDirection()" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option selected value="NULL">POLE</option>
                            <?php foreach ($inputServicePole as $servicePole) { echo $servicePole;} ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputDirection">
                    
                        <select name="nomDirectionService" id="inputServiceDirection" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option  selected value="NULL">DIRECTION</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputService">
                        <input name="nomService" type="text" id="inputService" class="form-control inputForms" placeholder="SERVICE" style="margin:0.375rem;">
                    </div>
                    <div class="col-md-3 text-center">
                        <input type="hidden" value="on" name="actifService">
                        <button type="submit" class="btn btn-md btn-success" name="addService"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter le service</button>
                    </div>
                </div>
                </form>
                <div class="row mb-5" id="inputAdd2">
                    <div class="col-md-3 text-center">
                        <button class="btn btn-md btn-info" onclick="permuteInputSelectPole()" id="buttonPole">Nouveau Pôle</button>
                    </div>
                    <div class="col-md-3 text-center">                   
                        <button class="btn btn-md btn-info" onclick="permuteInputSelectDirection()" id="buttonDirection">Nouvelle Direction</button>
                    </div>
                </div>
                <?php } else {?>
                <div class="row" id="inputAdd">
                    <div class="col-md-3" id="colInputPole">
                        <select name="nomPoleService" id="inputServicePole" onchange="afficheServiceDirection()" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option selected value="NULL">POLE</option>
                            <?php foreach ($inputServicePole as $servicePole) { echo $servicePole;} ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputDirection">
                    
                        <select name="nomDirectionService" id="inputServiceDirection" class="browser-default custom-select inputForms" style="margin:0.375rem;font-size:14px;">
                            <option  selected value="NULL">DIRECTION</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="colInputService">
                        <input name="nomService" type="text" id="inputService" class="form-control inputForms" placeholder="SERVICE" style="margin:0.375rem;">
                    </div>
                    <div class="col-md-3 text-center">
                        <input type="hidden" value="on" name="actifService">
                        <button type="submit" class="btn btn-md btn-success" name="addService"><i class="fas fa-plus-circle fa-lg pr-2"></i>Ajouter le service</button>
                    </div>
                </div>
                </form>
                <div class="row mb-5" id="inputAdd2">
                    <div class="col-md-3 text-center">
                        <button class="btn btn-md btn-info" onclick="permuteInputSelectPole()" id="buttonPole">Nouveau Pôle</button>
                    </div>
                    <div class="col-md-3 text-center">                   
                        <button class="btn btn-md btn-info" onclick="permuteInputSelectDirection()" id="buttonDirection">Nouvelle Direction</button>
                    </div>
                </div>
                <?php } ?>
                <table class="table table-bordered table-responsive-md table-striped text-center">
                    <thead>
                    <tr>
                        <th class="text-center th-sm">Pole</th>
                        <th class="text-center th-sm">Direction</th>
                        <th class="text-center th-sm">Service </th>
                        <th class="text-center th-sm">Actif</th>
                        <th class="text-center th-lg">Editer</th>
                    </tr>
                    </thead>
                    <tbody id="body-add-service">
                        <?php foreach($listeService as $unService){ echo $unService; } ?>
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

unset($_POST['edit']);
$_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;
if(!isset($_SESSION['login'])){ $_SESSION['logged']=0;$_SESSION['login']='invite';}
if($_SESSION['login']=='invite') $_SESSION['logged']=0;
?>
