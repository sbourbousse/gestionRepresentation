<?php
session_start();
include 'fix/functions.php';
include_once "fix/connectBDD.php";

$reqFilter='';

if(isset($_POST['searchFilter']))
{
    if($_POST['searchFilter']=1)
    {

        $reqFilter='typeAssembleId asc , representationNumDelib asc, representationDateAssemble desc';
        $recherche=$recherche.'&filterActe=on';
        $searchFilter=1;

    }
    else if($_POST['searchFilter'])
    {
        $reqFilter='representationDateAssemble asc';
        $recherche=$recherche.'&filterDate=on';
        $searchFilter=2;
    }

}
else $reqFilter='representationDateAssemble desc';

if(isset($_POST['searchElu'])&& isset($_POST['searchTitre']))
{
    $i=0;
    $arrayElu=explode(",", $_POST['searchElu']);

    foreach ($arrayElu as $unEluRequete)
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
    WHERE representationIntitule like  "%'.$_POST['searchTitre'].'%" 
    AND '.$reqElu.' 
    ORDER BY '.$reqFilter;

    $res= mysqli_query($maBase,$req);


    $searchType=3;
}
else if(isset($_POST['searchTitre']))
{
    $req = 'SELECT representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib, typeAssembleId, representationFondementJuridique, representationObservation, servicePoleNom, serviceDirectionNom, serviceCGNom 
    FROM Representation natural join Type_Assemble 
    WHERE representationIntitule like  "%'.$_POST['searchTitre'].'%" 
    ORDER BY '.$reqFilter;
    $res=mysqli_query($maBase,$req);

    $titreRecherche='Résultat de la recherche de la représentation \''.$_POST['searchTitre'].'\'';

    $searchType=2;
}
else if (isset($_POST['searchElu']))
{
    $arrayElu=explode(",", $_POST['searchElu']);

    $i=0;
    foreach ($arrayElu as $unEluRequete)
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

    $req = 'SELECT distinct Representation.representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib 
    FROM Representation LEFT JOIN Titulaire on Representation.representationId=Titulaire.representationId LEFT JOIN Suppleant on Representation.representationId=Suppleant.representationId natural join Type_Assemble     WHERE '.$reqElu.' 
    ORDER BY '.$reqFilter;

    $res=mysqli_query($maBase,$req);

    $searchType=1;
    
    //$titreRecherche='Résultats de la recherche du représentant \''.$_POST['search'].'\'';
}
else
{
    $req = "SELECT representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib, typeAssembleId, representationFondementJuridique, representationObservation, servicePoleNom, serviceDirectionNom, serviceCGNom 
    FROM Representation natural join Type_Assemble 
    ORDER BY ".$reqFilter;
    $res=mysqli_query($maBase,$req);

    $titreRecherche='Liste des représentations';
}

$csvCount=0;

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


    $mediaRepresentation[]='                    
        <li class="media my-4 representation">
        <div class="media-body">
        <h5 class="mt-0 mb-1 font-weight-bold">'.$uneRepresentation['representationIntitule'].'</h5>
        <h6>Date de l\'acte : '.convertDate($uneRepresentation['representationDateAssemble']).'</h6>
        <h6>'.$assemble.'</h6>
        '.$titulairePresent.'<br/>
        '.$suppleantPresent.'<br/>
        '.$personnalitePresente.'<br/>
        </div>
    </li>';

    $csvFile[$csvCount][]=$uneRepresentation['representationId'];
    $csvFile[$csvCount][]=$uneRepresentation['representationIntitule'];
    $csvFile[$csvCount][]=$uneRepresentation['typeAssembleNom'];
    $csvFile[$csvCount][]=$uneRepresentation['representationDateAssemble'];
    $csvFile[$csvCount][]=$uneRepresentation['representationNumDelib'];
    $csvFile[$csvCount][]=$uneRepresentation['representationFondementJuridique'];
    $csvFile[$csvCount][]=$uneRepresentation['representationObservation'];
    $csvFile[$csvCount][]=$uneRepresentation['servicePoleNom'];
    $csvFile[$csvCount][]=$uneRepresentation['serviceDirectionNom'];
    $csvFile[$csvCount][]=$uneRepresentation['serviceCGNom'];
    $csvCount++;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fix/head.inc.php'; ?>
        <title>Export des représentations</title>
        <style type="text/css" media="print" >
            body {visibility:hidden;}
            .print {visibility:visible;}
            .no-print {display: none;}
            div .representation{
                page-break-inside:avoid;
                page-break-after:auto;
                page-break-before:auto;
            }
        </style>
    </head>
    <body>
        <div class="text-center no-print mx-5">
            <h1>Apercu de votre export</h1>
            <div class="row mt-4">
                <div class="col-2 text-left">
                    <a href="liste.php"><button type="submit" class="btn btn-outline-blue btn-md waves-effect "><i class="fas fa-caret-left fa-2x pr-3"></i>Retour</button></a>
                </div>
                <div class="col-8">
                    <button type="submit" class="btn btn-outline-blue btn-md waves-effect px-5" onclick="window.print();return false;">Imprimer</button>
                </div>
                <div class="col-2">
                    <form method="POST" action="fix/csvExport.php">
                        <?php if ($searchType==1 || $searchType==3) { ?>
                        <input type="hidden" name="searchElu" value="<?php echo $_POST['searchElu']; ?>">
                        <?php } ?>
                        <?php if ($searchType==2 || $searchType==3) { ?>
                        <input type="hidden" name="searchTitre" value="<?php echo $_POST['searchTitre']; ?>">
                        <?php } ?>

                        <?php if (isset($searchFilter)) { ?>
                        <input type="hidden" name="searchFilter" value="<?php echo $searchFilter; ?>">
                        <?php } ?>

                        <button type="submit" class="btn btn-outline-green btn-md waves-effect px-5" ><i class="fas fa-file-csv fa-2x pr-3"></i></button>
                    </form>
                </div>
            </div>
            <input id="exportTitle" type="text" class="form-control mt-3" 
            placeholder="Donner un titre au document" onkeyup="donnerTitre()" value="<?php echo $titreRecherche; ?>">
        </div>
        <div class="m-5" style="border: 1px solid black;">
        <?php if (!isset($mediaRepresentation)) { ?>
            <p class="text-center">Aucun résultat</p>
        <?php } else { ?>
        <div id="pdfContent">
        <h2 id="titre" class="print text-center"><?php echo $titreRecherche; ?></h2>
        <ul class="list-unstyled print">
            <?php foreach ($mediaRepresentation as $uneRepresentation) echo $uneRepresentation ; ?>
        </ul>
        </div>
        <?php }?>
        </div>
    </body>
    <script src='js\jsPDF\dist\jspdf.min.js'></script>
    <script>
        function donnerTitre ()
        {
            var input = document.getElementById('exportTitle');
            var titre = document.getElementById('titre');
            
            titre.innerHTML=input.value;

        }
        // Default export is a4 paper, portrait, using milimeters for units
        /*var doc = new jsPDF()
        var pdfContent = document.getElementById('pdfContent');

        doc.fromHTML(pdfContent, 10, 10)
        doc.save('export.pdf')*/
    </script>
</html>
<?php
//else include 'error.inc.php';
?>