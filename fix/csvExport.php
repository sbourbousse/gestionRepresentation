<?php
include './functions.php';
session_start();
include_once "./connectBDD.php";

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
    $req= 'SELECT distinct Representation.representationId, representationIntitule, representationDateAssemble, typeAssembleNom, representationNumDelib 
    FROM Representation LEFT JOIN Titulaire on Representation.representationId=Titulaire.representationId LEFT JOIN Suppleant on Representation.representationId=Suppleant.representationId natural join Type_Assemble     WHERE representationIntitule like  "%'.$_POST['searchTitre'].'%" 
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
if ($csvCount==0)
{
    $csvFile[$csvCount][]='Identifiant';
    $csvFile[$csvCount][]='Intitule';
    $csvFile[$csvCount][]='Type d\'acte';
    $csvFile[$csvCount][]='Date de l\'acte';
    $csvFile[$csvCount][]='Numero de deliberation';
    $csvFile[$csvCount][]='Titulaires';
    $csvFile[$csvCount][]='Suppleants';
    $csvFile[$csvCount][]='Personnalitees';


    $csvCount++;
}

while ($uneRepresentation = mysqli_fetch_assoc($res))
{
    


    $reqTit = "SELECT eluNom,eluPrenom FROM Titulaire natural join Elu WHERE representationId=".$uneRepresentation['representationId'];
    $resTit=mysqli_query($maBase,$reqTit);
    $countTit=0;
    while ($unTitulaire = mysqli_fetch_assoc($resTit))
    {
        if ($countTit==0)
        $titulairePresent=$titulairePresent.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];
        else
        $titulairePresent=$titulairePresent.', '.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];

        $countTit++;
    }
    if ($countTit==0) $titulairePresent = 'Aucun';

    $reqSup = "SELECT eluNom,eluPrenom FROM Suppleant natural join Elu WHERE representationId=".$uneRepresentation['representationId'];
    $resSup=mysqli_query($maBase,$reqSup);
    $countSup=0;
    while ($unTitulaire = mysqli_fetch_assoc($resSup))
    {
        if ($countSup==0)
        $suppleantPresent=$suppleantPresent.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];
        else
        $suppleantPresent=$suppleantPresent.', '.strtoupper ($unTitulaire['eluNom']).' '.$unTitulaire['eluPrenom'];

        $countSup++;
    }
    if ($countSup==0) $suppleantPresent = 'Aucun';

    $reqPers = "SELECT personneNom,personnePrenom FROM Personnalite natural join Personne WHERE representationId=".$uneRepresentation['representationId'];
    $resPers=mysqli_query($maBase,$reqPers);
    $countPers=0;
    while ($unePersonne = mysqli_fetch_assoc($resPers))
    {
        if ($countPers==0)
        $personnalitePresente=$personnalitePresente.strtoupper ($unePersonne['personneNom']).' '.$unePersonne['personnePrenom'];
        else
        $personnalitePresente=$personnalitePresente.', '.strtoupper ($unePersonne['personneNom']).' '.$unePersonne['personnePrenom'];

        $countPers++;
    }
    if ($countPers==0) $personnalitePresente = 'Aucune';

    if ($uneRepresentation['typeAssembleNom']=='Arrêté') $assemble='Type d\'acte : arrêté';
    else if ($uneRepresentation['typeAssembleNom']=='Délibération') 
    {
        if($uneRepresentation['representationNumDelib']==0)
        $assemble='Type d\'acte : délibération';
        else
        $assemble='Type d\'acte : délibération numéro '.$uneRepresentation['representationNumDelib'];

    }


    $csvFile[$csvCount][]=skip_accents($uneRepresentation['representationId']);
    $csvFile[$csvCount][]=skip_accents($uneRepresentation['representationIntitule']);
    $csvFile[$csvCount][]=skip_accents($uneRepresentation['typeAssembleNom']);
    $csvFile[$csvCount][]=skip_accents($uneRepresentation['representationDateAssemble']);
    $csvFile[$csvCount][]=skip_accents($uneRepresentation['representationNumDelib']);
    $csvFile[$csvCount][]=skip_accents($titulairePresent);
    $csvFile[$csvCount][]=skip_accents($suppleantPresent);
    $csvFile[$csvCount][]=skip_accents($personnalitePresente);

    $titulairePresent='';
    $suppleantPresent='';
    $personnalitePresente='';
    $csvCount++;
}
array_to_csv_download($csvFile);
