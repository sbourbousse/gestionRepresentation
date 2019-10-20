<?php
@session_start();
include 'fix/functions.php';
include_once "fix/connectBDD.php";
$recherche='';
$reqFilter='';

if(isset($_GET['filterDate']) || isset($_GET['filterActe']))
{
    if(isset($_GET['filterActe']) && $_GET['filterActe']=='on')
    {

        $reqFilter='typeAssembleId asc , representationNumDelib asc, representationDateAssemble desc';
        $recherche=$recherche.'&filterActe=on';
        $searchFilter=1;

    }
    else if(isset($_GET['filterDate']) && $_GET['filterDate']=='on')
    {
        $reqFilter='representationDateAssemble asc';
        $recherche=$recherche.'&filterDate=on';
        $searchFilter=2;
    }

}
else $reqFilter='representationDateAssemble desc';


if (isset($_GET['elu'])&& (isset($_GET['titre'])&& $_GET['titre']!=''))
{
    $i=0;
    foreach ($_GET['elu'] as $unEluRequete)
    {
        if ($i==0)
        {
            $reqElu='(Representation.representationId in (SELECT representationId 
            FROM Titulaire  
            WHERE eluId='.$unEluRequete.') 
            OR Representation.representationId in (SELECT representationId 
            FROM Suppleant 
            WHERE eluId='.$unEluRequete.'))';
        }
        else
        {
            $reqElu=$reqElu.' AND (Representation.representationId in (SELECT representationId 
            FROM Titulaire  
            WHERE eluId='.$unEluRequete.') 
            OR Representation.representationId in (SELECT representationId 
            FROM Suppleant 
            WHERE eluId='.$unEluRequete.'))';
        }
        $i++;
    }
    $req= 'SELECT DISTINCT Representation.representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib 
    FROM Representation LEFT JOIN Titulaire on Representation.representationId=Titulaire.representationId LEFT JOIN Suppleant on Representation.representationId=Suppleant.representationId natural join Type_Assemble 
    WHERE representationIntitule like  "%'.$_GET['titre'].'%" 
    AND '.$reqElu.' 
    ORDER BY '.$reqFilter;

    $res= mysqli_query($maBase,$req);
    
    foreach ($_GET['elu'] as $unEluRecherche) $rechercheElu=$rechercheElu.'&elu[]='.$unEluRecherche;
    $recherche=$recherche.'&titre='.$_GET['titre'].$rechercheElu;

    $searchType=3;
}
else if(isset($_GET['titre']) && $_GET['titre']!='' && (!isset($_GET['elu'])|| $_GET['elu']==''))
{
    $req = 'SELECT representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib 
    FROM Representation natural join Type_Assemble 
    WHERE representationIntitule like  "%'.$_GET['titre'].'%" 
    ORDER BY '.$reqFilter;
    $res=mysqli_query($maBase,$req);
    $recherche=$recherche.'&titre='.$_GET['titre'];

    $searchType=2;
}
else if(isset($_GET['elu'])&& $_GET['elu']!='')
{
    $i=0;
    foreach ($_GET['elu'] as $unEluRequete)
    {
        if ($i==0)
        {
            $reqElu='(Representation.representationId in (SELECT representationId 
            FROM Titulaire  
            WHERE eluId='.$unEluRequete.') 
            OR Representation.representationId in (SELECT representationId 
            FROM Suppleant 
            WHERE eluId='.$unEluRequete.'))';
        }
        else
        {
            $reqElu=$reqElu.' AND (Representation.representationId in (SELECT representationId 
            FROM Titulaire  
            WHERE eluId='.$unEluRequete.') 
            OR Representation.representationId in (SELECT representationId 
            FROM Suppleant 
            WHERE eluId='.$unEluRequete.'))';
        }
        $i++;
    }

    $req = 'SELECT DISTINCT Representation.representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib 
    FROM Representation LEFT JOIN Titulaire on Representation.representationId=Titulaire.representationId LEFT JOIN Suppleant on Representation.representationId=Suppleant.representationId natural join Type_Assemble 
    WHERE '.$reqElu.' 
    ORDER BY '.$reqFilter;

    $res=mysqli_query($maBase,$req);
    foreach ($_GET['elu'] as $unEluRecherche) @$rechercheElu=$rechercheElu.'&elu[]='.$unEluRecherche;
    $recherche=$recherche.$rechercheElu;

    $searchType=1;
}
else
{
    $req = "SELECT representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib 
    FROM Representation natural join Type_Assemble 
    ORDER BY ".$reqFilter;
    $res=mysqli_query($maBase,$req);
}
while ($uneRepresentation = mysqli_fetch_assoc($res))
{
    $reqTit = "SELECT eluNom,eluPrenom FROM Titulaire natural join Elu WHERE representationId=".$uneRepresentation['representationId'];
    $resTit=mysqli_query($maBase,$reqTit);
    $titulairePresent='<b>Titulaire(s) Présent(s) : </b>';
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
    if ($countTit==0) $titulairePresent = 'Aucun titulaire présent';

    $reqSup = "SELECT eluNom,eluPrenom FROM Suppleant natural join Elu WHERE representationId=".$uneRepresentation['representationId'];
    $resSup=mysqli_query($maBase,$reqSup);
    $suppleantPresent='<b>Suppléant(s) Présent(s) : </b>';
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
    if ($countSup==0) $suppleantPresent = 'Aucun suppléant présent';

    $reqPers = "SELECT personneNom,personnePrenom FROM Personnalite natural join Personne WHERE representationId=".$uneRepresentation['representationId'];
    $resPers=mysqli_query($maBase,$reqPers);
    $personnalitePresente='<b>Personnalité(s) Présente(s) : </b>';
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
    if ($countPers==0) $personnalitePresente = 'Aucune personnalité présente';

    if ($uneRepresentation['typeAssembleNom']=='Arrêté') $assemble='Type d\'acte : arrêté';
    else if ($uneRepresentation['typeAssembleNom']=='Délibération') 
    {
        if($uneRepresentation['representationNumDelib']==0)
        $assemble='Type d\'acte : délibération';
        else
        $assemble='Type d\'acte : délibération numéro '.$uneRepresentation['representationNumDelib'];

    }
    else $assemble='type d\'acte : '.$uneRepresentation['typeAssembleNom'];


    $mediaRepresentation[]='                    
        <li class="media my-4">
        <img class="d-flex mr-3" src="img/cg05logo.png" alt="Generic placeholder image">
            <div class="media-body">
        <a class="black-text" href="representation.php?id='.$uneRepresentation['representationId'].'"><h5 class="mt-0 mb-1 font-weight-bold">'.$uneRepresentation['representationIntitule'].'</h5></a>
        <h6>Date de l\'acte : '.convertDate($uneRepresentation['representationDateAssemble']).'</h6>
        <h6>'.$assemble.'</h6>
        '.$titulairePresent.'<br/>
        '.$suppleantPresent.'<br/>
        '.$personnalitePresente.'<br/>
        <a class="blue-text" href="representation.php?id='.$uneRepresentation['representationId'].'"> plus d\'info ...</a> 
        </div>
    </li>';
}
if(!isset($_GET['page']))
{
    $_GET['page']=1;
}

if($_GET['page']==1)
{
    $first=$_GET['page']-1;
    $last=$_GET['page']*10-1;
}
else if($_GET['page']>1)
{
    $first=$_GET['page']*10-10;
    $last=$_GET['page']*10-1;
}
@$nbPage=ceil(count($mediaRepresentation)/10);

$req = "SELECT eluId, eluNom, eluPrenom, eluCivilite FROM Elu ORDER BY eluNom";
$res = mysqli_query($maBase,$req);

while ($unElu = mysqli_fetch_assoc($res))
{
    if($unElu['eluCivilite']=='H') $civilite='M.';
    else if ($unElu['eluCivilite']=='F') $civilite='Mme';

    $multiSelectElu[]='<option value="'.$unElu['eluId'].'">'.$civilite.' '.strtoupper($unElu['eluNom']).' '.$unElu['eluPrenom'].'</option>';

}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fix/head.inc.php'; ?>
        <title>Liste des représentations</title>
    </head>
    <body class="bg">
    <?php include "fix/navbar.inc.php"; ?>




        <div class="container">
        <h1 class="my-4 white-text text-center primary-heading">Liste des représentations</h1>

        <div class="grid">
        <div class="grid-item col-md-12 mb-12">
            
            <div class="card mb-5 rounded ">
                <div class=" rgba-indigo-slight rounded">
                <div class="row px-4 pb-2">
                <div class="col-10">
                <!-- Search form -->
                    <form  action="" method="GET" id="searchIntituleForm">
                    <div class="ml-5">
                        <div class="row">
                        <div class="col-5">
                            <div class="md-form">
                            <input class="form-control form-control-sm mt-1" type="text" name="titre" placeholder="Rechercher une instance"
                                aria-label="Rechercher une instance">
                            </div>
                            <!-- Default unchecked -->
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="dateCheckBox" name="filterDate"
                                <?php if(@$_GET['filterDate']=='on') echo 'checked'; ?> onclick="onCheckDate()">
                                <label class="custom-control-label" for="dateCheckBox">Du plus ancien au plus récent</label>
                            </div>
                        </div>
                        <div class="col-5 pt-3">
                            <select class="btn-indigo" id="example-getting-started" name="elu[]" multiple="multiple">
                                <?php foreach($multiSelectElu as $optionElu) echo $optionElu ; ?>                               
                            </select>
                            <!-- Default unchecked -->
                            <div class="custom-control custom-checkbox mt-4">
                                <input type="checkbox" class="custom-control-input" id="acteCheckBox" name="filterActe"
                                <?php if(@$_GET['filterActe']=='on') echo 'checked'; ?> onclick="onCheckActe()">
                                <label class="custom-control-label" for="acteCheckBox">Trié par type d'acte</label>
                            </div>
                        </div>
                                                
                        <!-- Default unchecked -->
                        <div class="col-2 pt-4">
                            <a onclick="document.getElementById('searchIntituleForm').submit()"><i class="fas fa-search fa-2x prefix indigo-text " aria-hidden="true"></i></a>
                        </div>
                        </div>
                    </div>
                </div>
                    </form>
                

                <div class="col-2">
                    <form method="POST" action="export.php">
                        <?php if (@$searchType==1 || @$searchType==3) { ?>
                        <input type="hidden" name="searchElu" value="<?php echo implode(",", $_GET['elu']); ?>">
                        <?php } ?>
                        <?php if (@$searchType==2 || @$searchType==3) { ?>
                        <input type="hidden" name="searchTitre" value="<?php echo $_GET['titre']; ?>">
                        <?php } ?>
                        <?php if (isset($searchFilter)) { ?>
                        <input type="hidden" name="searchFilter" value="<?php echo $searchFilter; ?>">
                        <?php } ?>

                        <button type="submit" class="btn btn-outline-blue btn-block btn-sm waves-effect px-1 mt-4"><i class="fas fa-print fa-2x pl-2 pr-2"></i></button>
                    </form>
                </div>

                </div>
                </div>


                <div class="card-body" id="corps">  
                <?php if ($nbPage>1)
                    { ?>
                    <nav aria-label="Page navigation example">
                    <ul class="pagination pg-blue justify-content-center">
                        <li class="page-item <?php if ($_GET['page']==1) echo 'disabled' ?>">
                        <a href="liste.php?page=<?php echo $_GET['page']-1;echo $recherche; ?>" class="page-link" tabindex="-1">Précedent</a>
                        </li>
                        <?php 
                        if($nbPage>=3)    
                        {
                            if ($_GET['page']==1)
                            {
                                if ($_GET['page']+2>$nbPage)
                                {
                                    for ($i = 1 ; $i<3 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }
                                else
                                {
                                    for ($i = $_GET['page'] ; $i<$_GET['page']+3 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }   
                            }
                            else if ($_GET['page']>1)
                            {
                                if($_GET['page']==$nbPage)
                                {
                                    for ($i = $_GET['page']-2 ; $i<$_GET['page']+1 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }
                                else
                                {
                                    for ($i = $_GET['page']-1 ; $i<$_GET['page']+2 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }
                            }
                        }
                        else 
                        {
                            for($i=1 ; $i<3 ; $i++)
                            {
                                if($i==$_GET['page'])
                                echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                else
                                echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                            }
                        }
                            ?>
                        
                        <li class="page-item <?php if ($_GET['page']==$nbPage) echo 'disabled' ?>">
                            <a href="liste.php?page=<?php echo $_GET['page']+1;echo $recherche; ?>" class="page-link">Suivant</a>
                        </li>
                    </ul>
                    </nav>
                    
            <?php } ?>

                <?php if (!isset($mediaRepresentation)) { ?>
                    <p class="text-center">Aucun résultat</p>
                <?php } else { ?>
                <ul class="list-unstyled">
                   <?php for ($i = $first ; $i<$last+1 ; $i++) echo @$mediaRepresentation[$i] ; ?>
                </ul>
                <?php }?>
                
                    <?php if ($nbPage>1)
                    { ?>
                    <nav aria-label="Page navigation example">
                    <ul class="pagination pg-blue justify-content-center">
                        <li class="page-item <?php if ($_GET['page']==1) echo 'disabled' ?>">
                        <a href="liste.php?page=<?php echo $_GET['page']-1;echo $recherche; ?>" class="page-link" tabindex="-1">Précedent</a>
                        </li>
                        <?php 
                        if($nbPage>=3)    
                        {
                            if ($_GET['page']==1)
                            {
                                if ($_GET['page']+2>$nbPage)
                                {
                                    for ($i = 1 ; $i<3 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }
                                else
                                {
                                    for ($i = $_GET['page'] ; $i<$_GET['page']+3 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }   
                            }
                            else if ($_GET['page']>1)
                            {
                                if($_GET['page']==$nbPage)
                                {
                                    for ($i = $_GET['page']-2 ; $i<$_GET['page']+1 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }
                                else
                                {
                                    for ($i = $_GET['page']-1 ; $i<$_GET['page']+2 ; $i++)
                                    {
                                        if($i==$_GET['page'])
                                        echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                        else
                                        echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                    }
                                }
                            }
                        }
                        else 
                        {
                            for($i=1 ; $i<3 ; $i++)
                            {
                                if($i==$_GET['page'])
                                echo '<li class="page-item active"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                                else
                                echo '<li class="page-item"><a href="liste.php?page='.$i.$recherche.'" class="page-link">'.$i.'</a></li>' ; 
                            }
                        }
                            ?>
                        
                        <li class="page-item <?php if ($_GET['page']==$nbPage) echo 'disabled' ?>">
                            <a href="liste.php?page=<?php echo $_GET['page']+1;echo $recherche; ?>" class="page-link">Suivant</a>
                        </li>
                    </ul>
                    </nav>
            <?php } ?>

                </div>
            </div>
        </div>
        </div>
        </div>

        <?php if ($_SESSION['echecAuth']==1 ||$_SESSION['demandeAuth']==1) include "fix/connectModal.php"; ?>   
        <?php include 'fix/scripts.inc.php'; ?>

    </body>
    <script type="text/javascript">

    function onCheckDate () {
        acteCheckBox.checked=false;

    }

    function onCheckActe () {

        dateCheckBox.checked=false;

    }

    new WOW().init();
    $(window).on('load',function(){
        $('#modalSubscriptionForm').modal('show');
    });


    $(document).ready(function() {
        $('#example-getting-started').multiselect({
            buttonWidth: '300px',
            enableFiltering: true,
            filterBehavior: 'text',
            filterPlaceholder: 'Rechercher',   
            enableCaseInsensitiveFiltering: true, 
            maxHeight: 200,
            buttonContainer: '<div class="btn-group" />',
            templates: {
                button: '<span class="multiselect dropdown-toggle btn-indigo" data-toggle="dropdown">Rechercher par représentant</span>',
                ul: '<ul class="multiselect-container dropdown-menu"></ul>',
                filter: '<li class="multiselect-item filter"><div class="m-1"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control form-control-sm multiselect-search inputForms" type="text"></div></li>',
                filterClearBtn: '<!--<button class="btn pb-2 btn-indigo multiselect-clear-filter" type="button"><i class="fas fa-times fa-xs"></i></button>-->',
                li: '<li><a href="javascript:void(0);"><label></label></a></li>',
                divider: '<li class="multiselect-item divider"></li>',
                liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
            }
        });
        
        var dateCheckBox = document.getElementById('dateCheckBox');
        var acteCheckBox = document.getElementById('acteCheckBox');


    });

    </script>
</html>
<?php
$_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;
if(!isset($_SESSION['login'])){ $_SESSION['logged']=0;$_SESSION['login']='invite';}
if($_SESSION['login']=='invite') $_SESSION['logged']=0;
?>
