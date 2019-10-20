<?php
session_start();
include 'fix/functions.php';
include_once "fix/connectBDD.php";
if (@$_SESSION['logged']==true && @$_SESSION['representationRight']==true)
{
    $req = 'SELECT representationId,representationDateAssemble,representationIntitule 
    FROM Representation
    ORDER BY RepresentationDateAssemble';
    $res=mysqli_query($maBase,$req);
    while ($uneInstance = mysqli_fetch_assoc($res))
    {

        $listeInstance[]="<tr>
        <td>".$uneInstance['representationIntitule']."</td>
        <td>".convertDate($uneInstance['representationDateAssemble']).'</td>
        <td>
            <form method="GET" action="action.php">
                <input type="hidden" name="idRepresentation" value="'.$uneInstance['representationId'].'">
                <button type="submit" name="editRepresentation" class="btn btn-warning px-3 fa-md"><i class="fas fa-pen fa-xs"></i></button>
                <button type="submit" name="deleteRepresentation" class="btn btn-danger px-3 fa-md"><i class="fas fa-trash fa-xs"></i></button>
            </form>
        </td>
        </tr>';
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
    <h1 class="my-4 primary-heading white-text text-center">Gestion des représentations</h1>
        <div class="grid">
            <div class="grid-item col-md-12 mb-12">
                <div class="card mb-5">
                    <div class="card-body" id="corps">

                        <table class="table table-bordered table-responsive-md table-striped text-center">
                            <thead>
                                <tr>
                                    <th class="text-center th-sm">
                                        Intitulé
                                    </th>
                                    <th class="text-center th-lg">
                                        Date
                                    </th>
                                    <th class="text-center th-lg">
                                        Supprimer
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="body-add-representation">
                                <?php foreach($listeInstance as $uneInstance){ echo $uneInstance; } ?>
                            </tbody>
                        </table>

                    </div>
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
        var table = $('.table').DataTable({
            select: true
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

    