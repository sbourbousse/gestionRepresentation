<?php
    include_once "connectBDD.php";

    //Ajout des directions
    if (isset($_GET['pole']))
    {
        $req="SELECT DISTINCT serviceDirectionNom FROM Service_CG WHERE serviceDirectionNom != '' AND servicePoleNom = '".$_GET['pole']."' ORDER BY serviceDirectionNom";
        $res=mysqli_query($maBase,$req);
        while ($unServiceDirection = mysqli_fetch_assoc($res))
        {
            echo '<option value="'.$unServiceDirection['serviceDirectionNom'].'">'.$unServiceDirection['serviceDirectionNom'].'</option>';
        }
    }
    else if (isset($_GET['direction']))
    {
        if ($_GET['direction']!="")
        {
            //Ajout des service avec direction
            $req='SELECT DISTINCT serviceCGNom FROM Service_CG WHERE serviceCGNom != "" AND serviceDirectionNom = "'.$_GET['direction'].'" ORDER BY serviceCGNom';
            $res=mysqli_query($maBase,$req);
            while ($unService = mysqli_fetch_assoc($res))
            {
                echo '<option value="'.$unService['serviceCGNom'].'">'.$unService['serviceCGNom'].'</option>';
            }
        }
        else if ($_GET['direction']=="")
        {
            //Ajout des services sans directions
            $req='SELECT serviceCGNom FROM Service_CG WHERE serviceCGNom != "" AND serviceDirectionNom = "" AND servicePoleNom="'.$_GET['pole2'].'" ORDER BY serviceCGNom';
            $res=mysqli_query($maBase,$req);
            while ($unService = mysqli_fetch_assoc($res))
            {
                echo '<option value="'.$unService['serviceCGNom'].'">'.$unService['serviceCGNom'].'</option>';
            }
        }
    }
    //echo $req;
?>