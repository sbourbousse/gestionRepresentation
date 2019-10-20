<?php
    $serveur="localhost";
    $baseName="representation";
    $mdp="";
    $user="sylvain";
    
    @$maBase = new mysqli($serveur,$user,$mdp,$baseName);

    if ($maBase->connect_error) {
        $errorConnect=1;
        die(include_once 'error.php');
    }
    
?>
