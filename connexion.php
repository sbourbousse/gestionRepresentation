<?php
session_start();
include 'fix/functions.php';
include_once "fix/connectBDD.php";

//DECONNEXION
if(!isset($_POST['connectNav'])&& $_SESSION['logged']==true)
{
    session_destroy();
}
else
{
    if(!isset($_SESSION['echecAuth'])) $_SESSION['echecAuth']=0;
    if(isset($_POST['connectNav'] )&& $_SESSION['demandeAuth']==0)
    {
        $_SESSION['demandeAuth']=1;
    }
    else if ($_SESSION['logged']==true)
    {
//        echo "c'est deja bon";
    }
    //SI la variable POST existe
    else if(isset($_POST))
    {
        $req = 'SELECT utilisateurIdentifiant,utilisateurMotDePasse
        FROM Utilisateur 
        WHERE utilisateurIdentifiant = \''.$_POST['login'].'\' 
        AND utilisateurMotDePasse = \''.md5($_POST['password']).'\' ';
//        echo $req;
		$res=mysqli_query($maBase,$req);
        @$row=mysqli_fetch_row($res);

        if(!($row) && isset($_POST['connect']))
        {
            $_SESSION['echecAuth']=1;
            $_SESSION['login']='invite';
            $_SESSION['logged']=false;
//            echo "mauvais mdp ou nom d'utilisateur";
        }
        else 
        {
            if ((!isset($_POST['login']) || !isset($_POST['password']))||($_POST['login']=='' && $_POST['password']==''))
            {
                $_SESSION['login']='invite';
                $_SESSION['echecAuth']=0;
                $_SESSION['logged']=false;
//                echo "connexion invité";
            }
            else
            {
                if($_POST['login']==$row[0] && md5($_POST['password'])==$row[1])
                {
                    
                    $_SESSION['login']=$row[0];
                    $_SESSION['echecAuth']=0;
                    $_SESSION['logged']=true;
//                    echo "connexion en tant que ".$_SESSION['login'];

                    $req = 'SELECT utilisateurGestionService,utilisateurGestionRepresentation,
                    utilisateurGestionElu, utilisateurGestionPersonne
                    FROM Utilisateur 
                    WHERE utilisateurIdentifiant = \''.$_POST['login'].'\' 
                    AND utilisateurMotDePasse = \''.md5($_POST['password']).'\' ';
//                    echo $req;
                    $res=mysqli_query($maBase,$req);
                    $row=mysqli_fetch_row($res);

                    if($row[0]==true) $_SESSION['serviceRight']=true; else $_SESSION['serviceRight']=false;
                    if($row[1]==true) $_SESSION['representationRight']=true; else $_SESSION['RepresentationRight']=false;
                    if($row[2]==true) $_SESSION['eluRight']=true; else $_SESSION['eluRight']=false;
                    if($row[3]==true)$_SESSION['personneRight']=true; else $_SESSION['personneRight']=false;

                }
            }
        }
    }
}
$_POST['login']='';$_POST['password']=''; 
header('location:'.$_SERVER["HTTP_REFERER"]);
?>

