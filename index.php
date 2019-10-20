<?php
session_start();
include 'fix/functions.php';
include_once "fix/connectBDD.php";
date_default_timezone_set('Europe/Paris');


$req = "SELECT representationId, representationIntitule, representationNumDelib, representationDateAssemble, servicePoleNom, serviceDirectionNom, serviceCGNom, typeAssembleNom 
FROM Representation natural join Type_Assemble
ORDER BY representationDateAssemble desc";
$res=mysqli_query($maBase,$req);
while ($uneInstance = mysqli_fetch_assoc($res))
{  
    $instanceNom[]=$uneInstance['representationIntitule'];
    $instanceDate[]=$uneInstance['representationDateAssemble'];
    $instanceId[]=$uneInstance['representationId'];

    $reqTit = "SELECT eluNom,eluPrenom FROM Titulaire natural join Elu WHERE representationId=".$uneInstance['representationId'];
    $resTit=mysqli_query($maBase,$reqTit);
    $titulairePresent='<b>Titulaire(s) Présent(s) : </b><br/>';
    $countTit=0;
    while ($unTitulaire = mysqli_fetch_assoc($resTit))
    {
        if ($countTit==0)
        $titulairePresent=$titulairePresent.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];
        else
        $titulairePresent=$titulairePresent.', '.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];

        $countTit++;
    }
    $titulairePresent=$titulairePresent.'.';
    if ($countTit==0) $titulairePresent = '<strike>Aucun titulaire présent</strike>';

    $reqSup = "SELECT eluNom,eluPrenom FROM Suppleant natural join Elu WHERE representationId=".$uneInstance['representationId'];
    $resSup=mysqli_query($maBase,$reqSup);
    $suppleantPresent='<b>Suppléant(s) Présent(s) : </b><br/>';
    $countSup=0;
    while ($unTitulaire = mysqli_fetch_assoc($resSup))
    {
        if ($countSup==0)
        $suppleantPresent=$suppleantPresent.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];
        else
        $suppleantPresent=$suppleantPresent.', '.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];

        $countSup++;
    }
    $suppleantPresent=$suppleantPresent.'.';
    if ($countSup==0) $suppleantPresent = '<strike>Aucun suppléant présent</strike>';

    $reqPers = "SELECT personneNom,personnePrenom FROM Personnalite natural join Personne WHERE representationId=".$uneInstance['representationId'];
    $resPers=mysqli_query($maBase,$reqPers);
    $personnalitePresente='<b>Personnalité(s) Présente(s) : </b><br/>';
    $countPers=0;
    while ($unePersonne = mysqli_fetch_assoc($resPers))
    {
        if ($countPers==0)
        $personnalitePresente=$personnalitePresente.strtoupper ($unePersonne['personneNom']).' '.$unePersonne['personnePrenom'];
        else
        $personnalitePresente=$personnalitePresente.', '.strtoupper ($unePersonne['personneNom']).' '.$unePersonne['personnePrenom'];

        $countPers++;
    }
    $personnalitePresente=$personnalitePresente.'.';
    if ($countPers==0) $personnalitePresente = '<strike>Aucune personnalité présente</strike>';



    if ($uneInstance['typeAssembleNom']=='Arrêté') $assemble='Arrêté';
    else if ($uneInstance['typeAssembleNom']=='Délibération') 
    {
        if($uneInstance['representationNumDelib']==0)
        $assemble='Délibération';
        else
        $assemble='Délibération numéro '.$uneInstance['representationNumDelib'];

    }
    else $assemble=$uneInstance['typeAssembleNom'];



    
    $instanceCollapse[]='
        <div class="media-body">
            <div class="row">
                <div class="col-6">
                <h3><i class="fas fa-calendar-day blue-text pr-2"></i> '.convertDate($uneInstance['representationDateAssemble']).'</h3>
                </div>
                <div class="col-6 text-right">
                <h3><i class="fas fa-list-ol blue-text pr-2"></i> '.$assemble.'</h3>
                </div>
            </div>
            <div class="ml-2 text-center">
        '.$titulairePresent.'<br/>
        '.$suppleantPresent.'<br/>
        '.$personnalitePresente.'<br/>
            </div>
        <a class="blue-text" href="representation.php?id='.$uneInstance['representationId'].'"><i class="fas fa-info-circle"></i> Plus d\'info ...</a> 
        </div>';

}
$reqCountInstance='SELECT count(*) FROM Representation';
$resCountInstante=mysqli_query($maBase,$reqCountInstance);
$rowCountInstance=mysqli_fetch_row($resCountInstante);
$reqCountElu='SELECT count(*) FROM Elu';
$resCountElu=mysqli_query($maBase,$reqCountElu);
$rowCountElu=mysqli_fetch_row($resCountElu);
$reqCountPersonnalite='SELECT count(*) FROM Personne';
$resCountPersonnalite=mysqli_query($maBase,$reqCountPersonnalite);
$rowCountPersonnalite=mysqli_fetch_row($resCountPersonnalite);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fix/head.inc.php'; ?>
        <title>Accueil</title>
    </head>
    <body class="bg">
    <?php include "fix/navbar.inc.php"; ?>

        <div class="container">

            <h1 class="my-4 font-weight-bold white-text">Gestion des representations</h1>

            <div class="grid">
            <div class="grid-sizer col-md-3"></div>
            <div class="grid-item col-md-6 mb-4">
                <div class="card ">
                <div class="card-body">
                    <h5 class="card-title">Bienvenue sur le portail de gestion des représentations</h5>
                    <p class="card-text">Vous pouvez consulter les représentations passées ainsi que rechercher une représentation en particulier dans la rubrique "Représentations" </p>
                    <!--<a class="card-link">Card link</a>
                    <a class="card-link">Another link</a>-->
                </div>
                </div>
            </div>
            <div class="grid-item col-md-3 mb-4 ">
                <div class="card">
                <div class="card-body">
                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active text-center">
                            <i class="fas fa-users indigo-text fa-3x"></i><br/>
                            <b><?php echo $rowCountInstance[0]; ?></b><br/>
                            Représentations renseignées
                        </div>
                        <div class="carousel-item text-center">
                            <i class="fas fa-user-tie indigo-text fa-3x"></i><br/>
                            <b><?php echo $rowCountElu[0]; ?></b><br/>
                            Elus renseignés
                        </div>
                        <div class="carousel-item text-center">
                        <i class="far fa-user indigo-text fa-3x"></i><br/>
                            <b><?php echo $rowCountPersonnalite[0]; ?></b><br/>
                            Personnalités renseignées
                        </div>
                    </div>
                    <a class="carousel-control-prev " href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    </div>
                    </div>
                </div>
            </div>
            <div class="grid-item col-md-3 mb-4">
                <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informations</h5>
                    <h6 class="card-subtitle mb-2 text-muted">Dernière Instance</h6>
                    <p class="card-text"><?php echo $instanceNom[0]; if (date('Y-d-m', time())>$instanceDate[0]) echo' a eu lieu le '; else echo ' aura lieu le ';echo convertDate($instanceDate[0]);?></p>
                </div>
                </div>
            </div>
            <div class="grid-item col-md-9 mb-4">
                <div class="card">
                <div class="card-header">
                    Representations récentes
                </div>
                <div class="card-body">
                    <blockquote class="blockquote mb-0">
                    <div id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <?php echo $instanceNom[0];?>
                                </button>
                            </h5>
                            </div>

                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="media-body">
                                    <?php echo $instanceCollapse[0]; ?>
                                </div>                            
                            </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <?php echo $instanceNom[1];?>
                                </button>
                            </h5>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                            <div class="media-body">
                                    <?php echo $instanceCollapse[1]; ?>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <?php echo $instanceNom[2];?>
                                </button>
                            </h5>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">
                            <div class="media-body">
                                    <?php echo $instanceCollapse[2]; ?>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>   
                    </blockquote>                 
                    <footer class="blockquote-footer"><a href="liste.php" class="white-text"><button class="btn btn-blue" href="liste.php"><i class="fas fa-eye pr-2"></i>Voir Toute les représentations</button></a></footer>
                </div>
                </div>
            </div>

            <div class="grid-item col-md-3 mb-4 ">
                <div class="card">
                <div class="card-body">
                    <h5 class="font-weight-bold mb-3">Votre compte</h5>
                    <p class="mb-0 "><?php if(@$_SESSION['logged']==false ) echo "Vous êtes connecté en tant qu'invite, vous ne disposez pas d'accès aux options de gestion"; ?> </p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center font-weight-bold"><?php if (isset($_SESSION['login'])) echo $_SESSION['login'];else echo "invite"; ?></li>
                    <?php if (@$_SESSION['serviceRight']==true) {?> <li class="list-group-item"><a href="service.php" class="card-link mr-3 text-default">Gerer les services</a></li><?php }?>
                    <?php if (@$_SESSION['eluRight']==true) {?> <li class="list-group-item"><a href="elu.php" class="card-link mr-3 text-default">Gerer les représentants</a></li><?php }?>
                    <?php if (@$_SESSION['personneRight']==true) {?> <li class="list-group-item"><a href="personnalite.php" class="card-link mr-3 text-default">Gerer les personnalités</a></li><?php }?>
                    <?php if (@$_SESSION['representationRight']==true) {?> <li class="list-group-item"><a href="gestion.php" class="card-link mr-3 text-default">Gerer les représentations</a></li><?php }?>
                </ul>
                <div class="row text-center">
                    <?php if (@$_SESSION['logged']==true) { ?><a href="formulaire.php" class="card-link mr-3 mx-auto"><button type="button" class="btn btn-success px-3 my-3 "><i class="fas fa-plus pr-2"></i>Nouvelle Instance</button></a>  <?php }
                    else { ?> <button class="btn btn-outline-info waves-effect px-3 my-3 mx-auto" onclick="document.getElementById('actionForm').submit()"><i class="fas fa-sign-in-alt pr-2"></i>Se connecter <?php } ?>
                </div>
                </div>
            </div>
        </div>




    <?php if ($_SESSION['echecAuth']==1 ||$_SESSION['demandeAuth']==1) include "fix/connectModal.php"; ?>        
    <?php include 'fix/scripts.inc.php'; ?>
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
$_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;
if(!isset($_SESSION['login'])){ $_SESSION['logged']=0;$_SESSION['login']='invite';}
if($_SESSION['login']=='invite') $_SESSION['logged']=0;
?>
