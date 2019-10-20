<?php
session_start();
include 'fix/functions.php';
include "fix/connectBDD.php";

/*--------------------------------+
|       AJOUT D'UN SERVICE        |
+---------------------------------*/
if (isset($_POST['addService']))
{
    if ($_POST['nomPoleService']==''||$_POST['nomPoleService']=='NULL') 
        $_POST['nomPoleService']='""';
    else $_POST['nomPoleService']='"'.skip_accents($_POST['nomPoleService']).'"';

    if ($_POST['nomDirectionService']==''||$_POST['nomDirectionService']=='NULL') 
        $_POST['nomDirectionService']='""';
    else $_POST['nomDirectionService']='"'.skip_accents($_POST['nomDirectionService']).'"';

    if ($_POST['nomService']==''||$_POST['nomService']=='NULL') 
        $_POST['nomService']='""';
    else $_POST['nomService']='"'.skip_accents($_POST['nomService']).'"';

    if ($_POST['actifService']=="on") $actif='true';
    else if (!isset($_POST['actifService'])) $actif='false';
    
    $req='INSERT INTO Service_CG (servicePoleNom,serviceDirectionNom,serviceCGNom,serviceActif) 
    VALUES('.$_POST['nomPoleService'].','
    .$_POST['nomDirectionService'].','
    .$_POST['nomService'].','.$actif.')';

    // si l'ajout reussi
    if(mysqli_query($maBase,$req))
    {
    ?>
    <html>
        <body>
            <form method="POST" action="service.php" name="formAdd">
                <input type="hidden" name="addSuccess">
            </form>
            <script type="text/javascript"> document.formAdd.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
<?php
    }
    // si l'ajout echoue
    else 
    { ?>
    <html>
        <body>
            <form method="POST" action="service.php" name="formAdd">
                <input type="hidden" name="addFail">
            </form>
            <script type="text/javascript"> document.formAdd.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
<?php   
    }
} 
/*--------------------------------+
|         AJOUT D'UN ELU          |
+---------------------------------*/
else  if (isset($_POST['addElu']))
{
    if ($_POST['actifElu']=="on") $actif='true';
    else if (!isset($_POST['actifElu'])) $actif='false';

    $req='INSERT INTO Elu (eluNom,eluPrenom,eluCivilite,eluActif) 
    VALUES (\''.$_POST['nomElu'].'\',\''
    .$_POST['prenomElu'].'\',\''
    .$_POST['eluCivilite'].'\','.$actif.')';

    mysqli_query($maBase,$req);
    
    if( isset($_FILES) ) // si formulaire soumis
    {

        $req='SELECT eluId FROM Elu WHERE eluNom="'.$_POST['nomElu'].'" AND eluPrenom="'.$_POST['prenomElu'].'"';       
        $res=mysqli_query($maBase,$req);
        $row=mysqli_fetch_row($res);

        $content_dir = 'img/elu/'; // dossier où sera déplacé le fichier
        
        $tmp_file = $_FILES['fichier']['tmp_name'];
        
        if( !is_uploaded_file($tmp_file) )
        {
            header('location:elu.php');
        }
        
        // on vérifie maintenant l'extension
        $type_file = $_FILES['fichier']['type'];
        
        if( !strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') )
        {
            header('location:elu.php');
        }
        
        // on copie le fichier dans le dossier de destination
        $name_file = $row[0].'.jpg';
        
        if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
        {
            header('location:elu.php');
        }
        // le fichier à bien été ajouté
        fctredimimage(128, 128, '', '', 'img/elu', $name_file);
    }
    header('location:elu.php');
}
/*--------------------------------+
|      MODIFICATION D'UN ELU      |
+---------------------------------*/
else if (isset($_POST['updateElu']))
{
    if ($_POST['actifElu']=="on") $actif='true';
    else if (!isset($_POST['actifElu'])) $actif='false';

    $req="UPDATE Elu SET eluNom ='".$_POST['nomElu']."', eluPrenom='".$_POST['prenomElu']."', eluActif=".$actif." WHERE eluId=".$_POST['idElu'];
    mysqli_query($maBase,$req);
    header('location:elu.php');
}
/*--------------------------------+
|     AJOUT D'UNE PERSONNALITE    |
+---------------------------------*/
else if (isset($_POST['addPersonne']))
{

    $req='INSERT INTO Personne (personneNom,personnePrenom,personneCivilite,personneFonction) 
    VALUES (\''.$_POST['nomPersonne'].'\',\''
    .$_POST['prenomPersonne'].'\',\''
    .$_POST['personneCivilite'].'\',\''.$_POST['fonctionPersonne'].'\')';

    mysqli_query($maBase,$req);

    header('location:'.$_SERVER["HTTP_REFERER"]);

}
/*--------------------------------+
| MODIFICATION D'UNE PERSONNALITE |
+---------------------------------*/
else if (isset($_POST['updatePersonne']))
{
    $req="UPDATE Personne SET personneNom ='".$_POST['nomPersonne']."', personnePrenom='".$_POST['prenomPersonne']."', personneCivilite='".$_POST['personneCivilite']."', personneFonction='".$_POST['fonctionPersonne']."' WHERE personneId=".$_POST['idPersonne'];
    mysqli_query($maBase,$req);
    header('location:personnalite.php');
}
/*--------------------------------+
|    AJOUT D'UNE REPRESENTATION   |
+---------------------------------*/
else if(isset($_POST['addRepresentation']))
{
    $error=0;
    if($_POST['nomInstance']=='') $error=1;
    if($_POST['assembleType']=='') $error=1;
    if($_POST['dateInstance']=='') $error=1;

    $req='INSERT INTO Representation (representationIntitule,typeAssembleId,representationDateAssemble,representationNumDelib,representationFondementJuridique,representationObservation,servicePoleNom,serviceDirectionNom,serviceCGNom) VALUES ("'.$_POST['nomInstance'].'","'.$_POST['assembleType'].'","'.$_POST['dateInstance'].'","'.$_POST['numDelib'].'","'.$_POST['fondementJuridique'].'","'.$_POST['observation'].'","'.$_POST['servicePole'].'","'.$_POST['serviceDirection'].'","'.$_POST['service'].'");';
    
    //si un champs obligatoire n'est pas rempli
    if ($error==1)
    {
    ?>
    <html>
        <body>
            <form method="POST" action="formulaire.php" name="formFail">
                <input type="hidden" name="fail">
            </form>
            <script type="text/javascript"> document.formFail.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
    <?php       
    }
    //si l'ajout reussi
    else if (mysqli_query($maBase,$req))//s'execute quand on met && $error==1 a côté
    {
        $req='SELECT MAX(representationId) FROM Representation'; 
        if ($res=mysqli_query($maBase,$req))
        {
            $row=mysqli_fetch_row($res);

            foreach ($_POST['titulaire'] as $unTitulaire){
                $req='INSERT INTO Titulaire (representationId,eluId) VALUES ('.$row[0].','.$unTitulaire.')';
                mysqli_query($maBase,$req);
            }
            
            foreach ($_POST['suppleant'] as $unSuppleant){
                $req='INSERT INTO Suppleant (representationId,eluId) VALUES ('.$row[0].','.$unSuppleant.')';
                mysqli_query($maBase,$req);
            }
            foreach ($_POST['personnalite'] as $unePersonne){
                $req='INSERT INTO Personnalite (representationId,personneId) VALUES ('.$row[0].','.$unePersonne.')';
                mysqli_query($maBase,$req);
            }
            $req='SELECT representationIntitule FROM Representation WHERE representationId='.$row[0];
            $res=mysqli_query($maBase,$req);
            $row2=mysqli_fetch_row($res);
            ?>
            <html>
                <body>
                    <form method="POST" action="formulaire.php" name="formAdd">
                        <input type="hidden" value="<?php echo $row2[0]; ?>" name="addIntitule">
                        <input type="hidden" name="add">
                    </form>
                    <script type="text/javascript"> document.formAdd.submit(); //on envoie le formulaire vers service.php </script> 
                </body>
            </html>
            <?php
        }
    }
    //si l'ajout echou
    else
    {
    ?>
    <html>
        <body>
            <form method="POST" action="formulaire.php" name="formFail">
                <input type="hidden" name="fail">
            </form>
            <script type="text/javascript"> document.formFail.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
    <?php
    }
}
/*-------------------------------------+
|   MODIFICATION D'UNE REPRESENTATION  |
+--------------------------------------*/
else if (isset($_POST['updateRepresentation']))
{
    //print_r($_POST);
    if(!isset($_POST['numDelib'])) $_POST['numDelib']=0;
    $req='UPDATE Representation 
    SET representationIntitule="'.$_POST['nomInstance'].'" , typeAssembleId="'.$_POST['assembleType'].'" , 
    representationDateAssemble="'.$_POST['dateInstance'].'" , representationNumDelib='.$_POST['numDelib'].' , 
    representationFondementJuridique="'.$_POST['fondementJuridique'].'" , representationObservation="'.$_POST['observation'].'" , 
    servicePoleNom="'.$_POST['servicePole'].'" , serviceDirectionNom="'.$_POST['serviceDirection'].'" , serviceCGNom="'.$_POST['service'].'"
    WHERE representationId='.$_POST['representationId'];
    //echo $req;
    if (mysqli_query($maBase,$req))
    {
        $req='DELETE FROM Titulaire 
        WHERE representationId='.$_POST['representationId'];
        mysqli_query($maBase,$req);
        $req='DELETE FROM Suppleant
        WHERE representationId='.$_POST['representationId'];
        mysqli_query($maBase,$req);
        $req='DELETE FROM Personnalite
        WHERE representationId='.$_POST['representationId'];
        mysqli_query($maBase,$req);

        if(isset($_POST['titulaire'])) 
        {
            foreach ($_POST['titulaire'] as $unTitulaire){
                $req='INSERT INTO Titulaire (representationId,eluId) VALUES ('.$_POST['representationId'].','.$unTitulaire.')';
                mysqli_query($maBase,$req);
            }
        }
        if(isset($_POST['suppleant'])) 
        {
            foreach ($_POST['suppleant'] as $unSuppleant){
                $req='INSERT INTO Suppleant (representationId,eluId) VALUES ('.$_POST['representationId'].','.$unSuppleant.')';
                mysqli_query($maBase,$req);
            }
        }
        if(isset($_POST['personnalite'])) 
        {
            foreach ($_POST['personnalite'] as $unePersonne){
                $req='INSERT INTO Personnalite (representationId,personneId) VALUES ('.$_POST['representationId'].','.$unePersonne.')';
                mysqli_query($maBase,$req);
            }
        }
    }
    header('location:representation.php?id='.$_POST['representationId']);    
}
/*-------------------------------------------------------------------------+
|  SUPPRESSION DU SERVICE PUIS RENVOIE DES VALEURS DES CHAMPS DU SERVICE   |
+--------------------------------------------------------------------------*/
else if (isset($_GET['editService']))
{
    $req='DELETE FROM Service_CG 
    WHERE servicePoleNom = "'.$_GET['nomPoleService'].'" AND serviceDirectionNom = "'.$_GET['nomDirectionService'].'" AND serviceCGNom = "'.$_GET['nomService'].'" AND serviceActif = '.$_GET['actifService'];
            
    mysqli_query($maBase,$req); ?>
    <html>
        <body>
            <form method="POST" action="service.php" name="formEdit">
                <input type="hidden" value="<?php echo $_GET['nomPoleService']; ?>" name="editPole">
                <input type="hidden" value="<?php echo $_GET['nomDirectionService']; ?>" name="editDirection">
                <input type="hidden" value="<?php echo $_GET['nomService']; ?>" name="editService">
                <input type="hidden" value="<?php echo $_GET['actifService']; ?>" name="actifService">
                <input type="hidden" name="edit">
            </form>
            <script type="text/javascript"> document.formEdit.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
<?php
}
/*--------------------------------+
|      SUPPRESSION DU SERVICE     |
+---------------------------------*/
else if (isset($_GET['deleteService']))
{
    $req='DELETE FROM Service_CG 
    WHERE servicePoleNom = "'.$_GET['nomPoleService'].'" AND serviceDirectionNom = "'.$_GET['nomDirectionService'].'" AND serviceCGNom = "'.$_GET['nomService'].'"';
            
    mysqli_query($maBase,$req);

    header('location:service.php');
}
/*-----------------------------------------+
|    RENVOIE DES VALEURS DU CHAMPS ELU     |
+------------------------------------------*/
else if (isset($_GET['editElu']))
{ ?>
    <html>
        <body>
            <form method="POST" action="elu.php" name="formEdit">
                <input type="hidden" value="<?php echo $_GET['nomElu']; ?>" name="editNom">
                <input type="hidden" value="<?php echo $_GET['prenomElu']; ?>" name="editPrenom">
                <input type="hidden" value="<?php echo $_GET['civiliteElu']; ?>" name="editCivilite">
                <input type="hidden" value="<?php echo $_GET['actifElu']; ?>" name="editActif">
                <input type="hidden" value="<?php echo $_GET['idElu']; ?>" name="editId">
                <input type="hidden" name="edit">
            </form>
            <script type="text/javascript"> document.formEdit.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
<?php   
}
/*--------------------------------+
|       SUPPRESSION D'UN ELU      |
+---------------------------------*/
else if(isset($_GET['deleteElu']))
{
    $req='DELETE FROM Elu
    WHERE eluId="'.$_GET['idElu'].'"';
     
    //si la suppression reussi
    if (mysqli_query($maBase,$req))
    {
        $myFile = "img/elu/".$_GET['idElu'].".jpg";
        @unlink($myFile);
        header('location:elu.php');
    }
    //si la supression echoue
    else
    { ?>
        <html>
            <body>
                <form method="POST" action="elu.php" name="formFail">
                    <input type="hidden" name="delFail">
                </form>
                <script type="text/javascript"> document.formFail.submit(); //on envoie le formulaire vers service.php </script> 
            </body>
        </html>
<?php 
    }
}
/*----------------------------------------------------+
|  RENVOIE DES VALEURS DES CHAMPS DE LA PERSONNALITE  |
+-----------------------------------------------------*/

else if (isset($_GET['editPersonne']))
{
    mysqli_query($maBase,$req); ?>
    <html>
        <body>
            <form method="POST" action="personnalite.php" name="formEdit">
                <input type="hidden" value="<?php echo $_GET['nomPersonne']; ?>" name="editNom">
                <input type="hidden" value="<?php echo $_GET['prenomPersonne']; ?>" name="editPrenom">
                <input type="hidden" value="<?php echo $_GET['civilitePersonne']; ?>" name="editCivilite">
                <input type="hidden" value="<?php echo $_GET['fonctionPersonne']; ?>" name="editFonction">
                <input type="hidden" value="<?php echo $_GET['idPersonne']; ?>" name="editId">
                <input type="hidden" name="edit">
            </form>
            <script type="text/javascript"> document.formEdit.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
<?php   
}
/*---------------------------------+
|  SUPPRESSION DE LA PERSONNALITE  |
+----------------------------------*/
else if(isset($_GET['deletePersonne']))
{
    $req='DELETE FROM Personne
    WHERE personneNom = "'.$_GET['nomPersonne'].'" AND personnePrenom = "'.$_GET['prenomPersonne'].'"';
            
    mysqli_query($maBase,$req);

    header('location:personnalite.php');
}
/*-----------------------------------+
|  SUPPRESSION D'UNE REPRESENTATION  |
+------------------------------------*/
else if(isset($_GET['deleteRepresentation']))
{
    $req='DELETE FROM Titulaire
    WHERE representationId='.$_GET['idRepresentation'];
    mysqli_query($maBase,$req);
    $req='DELETE FROM Suppleant
    WHERE representationId='.$_GET['idRepresentation'];
    mysqli_query($maBase,$req);
    $req='DELETE FROM Personnalite
    WHERE representationId='.$_GET['idRepresentation']; 
    mysqli_query($maBase,$req);

    $req='DELETE FROM Representation
    WHERE representationId='.$_GET['idRepresentation'];
    mysqli_query($maBase,$req);

    header('location:liste.php');
}
/*-------------------------------------------------------+
|   RENVOIE DES VALEURS DES CHAMPS DE LA REPRESENTATION  |
+--------------------------------------------------------*/
else if(isset($_GET['editRepresentation']))
{
?>
    <html>
        <body>
            <form method="POST" action="editer.php" name="formEdit">
                <input type="hidden" value="<?php echo $_GET['idRepresentation']; ?>" name="editId">
                <?php /*<!--<input type="hidden" value="<?php echo $_GET['intituleRepresentation']; ?>" name="editIntitule">
                <input type="hidden" value="<?php echo $_GET['idTypeAssemble']; ?>" name="editTypeAssembleId">
                <input type="hidden" value="<?php echo $_GET['dateAssembleRepresentation']; ?>" name="editDateAssemble">
                <input type="hidden" value="<?php echo $_GET['numDelibRepresentation']; ?>" name="editNumDelib">
                <input type="hidden" value="<?php echo $_GET['fondementJuridiqueRepresentation']; ?>" name="editFondementJuridique">
                <input type="hidden" value="<?php echo $_GET['observationRepresentation']; ?>" name="editObservation">
                <input type="hidden" value="<?php echo $_GET['nomServicePole']; ?>" name="editServicePoleNom">
                <input type="hidden" value="<?php echo $_GET['nomServiceDirection']; ?>" name="editServiceDirectionNom">
                <input type="hidden" value="<?php echo $_GET['nomService']; ?>" name="editServiceNom">
                <input type="hidden" value="<?php echo $_GET['titulaire[]']; ?>" name="editTitulaire">
                <input type="hidden" value="<?php echo $_GET['suppleant[]']; ?>" name="editSuppleant">
                <input type="hidden" value="<?php echo $_GET['personnalite[]']; ?>" name="editPersonnalite">-->*/?>
                <input type="hidden" name="edit">
            </form>
            <script type="text/javascript"> document.formEdit.submit(); //on envoie le formulaire vers service.php </script> 
        </body>
    </html>
<?php  
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Accueil</title>
        <?php include 'fix/head.inc.php'; ?>
    </head>
    <body class="bg">

    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <div class="container">
                <div class="card ">
                    <div class="card-body">
                        <div class="row">
                        <div class="col-9">
                        <blockquote class="blockquote bq-danger ">
                            <p class="bq-title">
                                Erreur
                            </p>
                            <p>
                                <b>Une erreur s'est produite</b>
                            </p>
                            <a class="text-danger" href="/">Retour vers l'acceuil</a>
                        </blockquote>
                        </div>
                        <div class="col-2">
                        <i class="fas fa-exclamation-triangle text-danger fa-10x mt-4 mr-4"></i>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?php if (!isset($_SESSION['login'])||$_SESSION['echecAuth']==1 ||$_SESSION['demandeAuth']==1) include "fix/connectModal.php"; ?>        
        <!-- SCRIPTS -->
        <script type="text/javascript" src="js/jquery-3.4.0.min.js"></script>
        <script type="text/javascript" src="js/popper.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/mdb.min.js"></script>
        <!-- MDBootstrap Datatables  -->
        <script type="text/javascript" src="js/addons/datatables.min.js"></script>
        <script src="js/script.js"></script>
        <!-- MDBootstrap Masonry  -->
        <script type="text/javascript" src="js/addons/masonry.pkgd.min.js"></script>
        <script type="text/javascript" src="js/addons/imagesloaded.pkgd.min.js"></script>
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
