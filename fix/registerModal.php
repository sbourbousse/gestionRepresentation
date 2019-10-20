<?php
include 'connectBDD.php';
$req='UPDATE Type_Assemble SET typeAssembleNom="Arrêté" WHERE typeAssembleId=1';
mysqli_query($maBase,$req);
?>