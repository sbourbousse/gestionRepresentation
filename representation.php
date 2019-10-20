<?php
session_start();
include 'fix/functions.php';
include_once "fix/connectBDD.php";

$req='SELECT representationIntitule,servicePoleNom,serviceDirectionNom,serviceCGNom,representationDateAssemble,representationNumDelib,representationObservation,representationFondementJuridique,typeAssembleNom 
FROM Representation natural join Type_Assemble 
WHERE representationId='.$_GET['id'];

$res=mysqli_query($maBase,$req);
$row=@mysqli_fetch_row($res);
$waouw=1;

//TRAITEMENT AFFICHAGE ELUS TITULAIRES
$reqTit='SELECT representationId,eluId,eluNom,eluPrenom,eluCivilite 
FROM Titulaire natural join Elu 
WHERE representationId='.$_GET['id'];
$resTit=mysqli_query($maBase,$reqTit);

if (!empty($resTit))
{
    $i=0;

    while ($unTitulaire = mysqli_fetch_assoc($resTit))
    {  
        if ($unTitulaire['eluCivilite']=='H') $civilite='M.';
        else if ($unTitulaire['eluCivilite']=='F')$civilite='Mme';

        if (file_exists ('img/elu/'.$unTitulaire['eluId'].'.jpg'))$img='img/elu/'.$unTitulaire['eluId'].'.jpg';
        else if($unTitulaire['eluCivilite']=='H') $img='img/elu-homme.svg';
        else if($unTitulaire['eluCivilite']=='F') $img='img/elu-femme.svg';

        $afficheUnTitulaire[]='
            <div class="col-3">
                <img src="'.$img.'" class="rounded-circle" height="128" width="128">
                <p>'.$civilite.' '.$unTitulaire['eluNom'].' '.$unTitulaire['eluPrenom'].'</p>
            </div>';

        $i++;
    }
    $afficheAllTitulaire='';
    $h=0;
    for ($i=0 ; $i<ceil(@sizeof(@$afficheUnTitulaire)/4) ; $i++)
    {
        $afficheAllTitulaire=$afficheAllTitulaire.'<div class="row text-center my-4">';
        if (sizeof($afficheUnTitulaire)-$h<4)
        {
            for($j=0 ; $j<=sizeof($afficheUnTitulaire)-$h+1 ; $j++)
            {
                @$afficheAllTitulaire=$afficheAllTitulaire.$afficheUnTitulaire[$h];
                $h++;
            }
        }
        else
        {
            for ($j=0 ; $j<4 ; $j++)
            {
                $afficheAllTitulaire=$afficheAllTitulaire.$afficheUnTitulaire[$h];
                $h++;
            }
        }
        $afficheAllTitulaire=$afficheAllTitulaire.'</div>';
    }
}
//TRAITEMENT AFFICHAGE ELUS SUPPLEANTS
$reqSup='SELECT representationId,eluId,eluNom,eluPrenom,eluCivilite 
FROM Suppleant natural join Elu 
WHERE representationId='.$_GET['id'];
$resSup=mysqli_query($maBase,$reqSup);

if(!empty($resSup))
{
    $i=0;
    
    while ($unSuppleant = mysqli_fetch_assoc($resSup))
    {  
        if ($unSuppleant['eluCivilite']=='H') $civilite='M.';
        else if ($unSuppleant['eluCivilite']=='F')$civilite='Mme';
        
        if (file_exists ('img/elu/'.$unSuppleant['eluId'].'.jpg'))$img='img/elu/'.$unSuppleant['eluId'].'.jpg';
        else if($unSuppleant['eluCivilite']=='H') $img='img/elu-homme.svg';
        else if($unSuppleant['eluCivilite']=='F') $img='img/elu-femme.svg';
        
        $afficheUnSuppleant[]='
        <div class="col-3">
        <img src="'.$img.'" class="rounded-circle" height="128" width="128">
        <p>'.$civilite.' '.$unSuppleant['eluNom'].' '.$unSuppleant['eluPrenom'].'</p>
        </div>';
        
        $i++;
    }
    $afficheAllSuppleant='';
    $h=0;
    for ($i=0 ; $i<ceil(@sizeof(@$afficheUnSuppleant)/4) ; $i++)
    {
        $afficheAllSuppleant=$afficheAllSuppleant.'<div class="row text-center my-4">';
        if (sizeof($afficheUnSuppleant)-$h<4)
        {
            for($j=0 ; $j<=sizeof($afficheUnSuppleant)-$h+1 ; $j++)
            {
                @$afficheAllSuppleant=$afficheAllSuppleant.$afficheUnSuppleant[$h];
                $h++;
            }
        }
        else
        {
            for ($j=0 ; $j<4 ; $j++)
            {
                $afficheAllSuppleant=$afficheAllSuppleant.$afficheUnSuppleant[$h];
                $h++;
            }
        }
        $afficheAllSuppleant=$afficheAllSuppleant.'</div>';
    }
}

//TRAITEMENT AFFICHAGE PERSONNALITE
$reqPers='SELECT representationId,personneId,personneNom,personnePrenom,personneCivilite, personneFonction 
FROM Personnalite natural join Personne 
WHERE representationId='.$_GET['id'];
$resPers=mysqli_query($maBase,$reqPers);
if(!empty($resPers))
{
    $i=0;
    
    while ($unePersonne = mysqli_fetch_assoc($resPers))
    {  
        if ($unePersonne['personneCivilite']=='H') $civilite='M.';
        else if ($unePersonne['personneCivilite']=='F')$civilite='Mme';
        else $civilite='';
        
        if (file_exists ('img/personnalite/'.$unePersonne['personneId'].'.jpg'))$img='img/personnalite/'.$unePersonne['personneId'].'.jpg';
        else if($unePersonne['personneCivilite']=='H') $img='img/personnalite-homme.svg';
        else if($unePersonne['personneCivilite']=='F') $img='img/personnalite-femme.svg';
        else $img='img/personnalite-homme.svg';

        if ($unePersonne['personneFonction']!='') $fonction='('.$unePersonne['personneFonction'].')';
        else $fonction='';
        
        $afficheUnePersonne[]='
        <div class="col-3">
        <img src="'.$img.'" class="rounded-circle" height="128" width="128">
        <p>'.$civilite.' '.$unePersonne['personneNom'].' '.$unePersonne['personnePrenom'].' '.$fonction.'</p>
        </div>';
        
        $i++;
    }
    $afficheAllPersonne='';
    $h=0;
    for ($i=0 ; $i<ceil(@sizeof(@$afficheUnePersonne)/4) ; $i++)
    {
        $afficheAllPersonne=$afficheAllPersonne.'<div class="row text-center my-4">';
        if (sizeof($afficheUnePersonne)-$h<4)
        {
            for($j=0 ; $j<=sizeof($afficheUnePersonne)-$h+1 ; $j++)
            {
                @$afficheAllPersonne=$afficheAllPersonne.$afficheUnePersonne[$h];
                $h++;
            }
        }
        else
        {
            for ($j=0 ; $j<4 ; $j++)
            {
                $afficheAllPersonne=$afficheAllPersonne.$afficheUnePersonne[$h];
                $h++;
            }
        }
        $afficheAllPersonne=$afficheAllPersonne.'</div>';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php include 'fix/head.inc.php'; ?>
        <title><?php echo $row[0] ; ?></title>
    </head>
    <body class="bg">
        <?php include "fix/navbar.inc.php"; ?>
        
        
        
<?php if (@sizeof($row)!=0)
{ ?>        
        <div class="container">
            <h1 class=" primary-heading my-4 white-text text-center">Détails de la représentation</h1>
            
            <div class="grid">
                <div class="grid-item col-md-12 mb-12">
                    <div class="card mb-5 ">
                        <div class="card-body" id="corps">
                            <h1 class="my-4 text-center blue-grey-text"><u><?php echo $row[0] ; ?></u></h1>
                            
                            
                            <div class="row text-center my-4">
                                <div class="col-4 p-3">
                                    <div class="card-body rounded z-depth-1">
                                        <h2 class=" primary-darker-hover mb-3"><i class="fas fa-calendar-day"></i>
                                            <br/>Date de l'acte
                                        </h2>
                                        <p><?php echo convertDate($row[4]); ?></p>
                                    </div>
                                </div>
                                <div class="col-4 py-3 px-2">
                                    <div class="card-body rounded z-depth-1">
                                        <h2 class=" primary-darker-hover mb-3"><i class="fas fa-users-cog"></i> 
                                            <br/>Service gestionnaire
                                        </h2>
                                        <p><?php if($row[1]=='' && $row[2]=='' && $row[3]=='') echo 'Aucun';
                                        echo $row[1]; ?></p>
                                        <p style="opacity:0.8;"><?php echo $row[2]; ?></p>
                                        <p style="opacity:0.7;"><?php echo $row[3]; ?></p>
                                    </div>
                                
                                </div>
                                <div class="col-4 p-3">
                                    <div class="card-body rounded z-depth-1">
                                        <h2 class=" primary-darker-hover mb-3"><i class="fas fa-list-ol"></i> 
                                            <br/>Type d'acte
                                        </h2>
                                        <p><?php echo $row[8]; ?></p>
                                        <p >
                                        <?php if($row[5]==0) echo 'Aucun numéro'; 
                                        else    echo 'Numéro : '.$row[5]; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        
                            
                            <?php if (isset($afficheUnTitulaire)) { ?>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <h2 class="text-center">Titulaires présents</h2>
                                    <?php echo $afficheAllTitulaire; ?>
                                </li>
                                <?php } ?>            

                                <?php if (isset($afficheUnSuppleant)) { ?>
                                <li class="list-group-item">
                                    <h2 class="text-center">Suppléants présents</h2>
                                    <?php echo $afficheAllSuppleant; ?>
                                </li>
                                <?php } ?>

                                <?php if (isset($afficheUnePersonne)) { ?>
                                <li class="list-group-item">
                                    <h2 class="text-center">Personnalités présentes</h2>
                                    <?php echo $afficheAllPersonne; ?>
                                </li>
                                <?php } ?>


                            </ul>
                    
                            <div class="row text-center my-4">
                                <div class="col-6">
                                <div class="card-body rounded z-depth-1">
                                <h2 class=" primary-darker-hover mb-3"><i class="far fa-comment-dots"></i>
                                    <br/>Observations
                                </h2>
                                    <p class="blue-grey-text <?php if ($row[6]=='') echo 'text-center' ?>"><?php if ($row[6]=='') echo 'Aucune';
                                    else echo $row[6]; ?></p>
                                </div>
                                </div>
                                <div class="col-6">
                                <div class="card-body rounded z-depth-1">
                                    <h2 class="primary-darker-hover mb-3"><i class="fas fa-book"></i>
                                        <br/>Fondement juridique
                                    </h2>
                                    <p class="blue-grey-text <?php if ($row[7]=='') echo 'text-center' ?>"><?php if ($row[7]=='') echo 'Aucun';
                                    else echo $row[7]; ?></p>
                                </div>
                                </div>
                            </div>
                            <?php if ($_SESSION['logged']==1 && @$_SESSION['representationRight']==1) { ?>
                            <div class="row my-4">
                                <div class="col-6 text-center my-5">
                                    <form method="GET" action="action.php">
                                        <input type="hidden" name="idRepresentation" value="<?php echo $_GET['id'] ?>">
                                        <button type="submit" name="editRepresentation" class="btn btn-warning">Modifier cette représentation</button>
                                    </form>
                                </form>
                                </div>
                                <div class="col-6 text-center my-5">
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
                                    Supprimer cette représentation
                                    </button>
                                </div>


                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Supprimer la représentation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="text-center">
                                            <i class="fas fa-exclamation-triangle text-danger fa-2x text-center"></i><br/>
                                        </div>
                                        Vous vous apprétez à supprimer une représentation,<br/>
                                        cette action est irréversible.<br/>
                                        Êtes vous sûr de vouloir supprimer : <br/>
                                        <u><?php echo $row[0] ; ?></u> ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-indigo" data-dismiss="modal">Annuler</button>
                                        <form method="GET" action="action.php">
                                            <input type="hidden" name="idRepresentation" value="<?php echo $_GET['id'] ?>" data-toggle="modal" data-target="#myModal">
                                            <button type="submit" name="deleteRepresentation" class="btn btn-danger">Oui Je suis sûr</button>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php } else { ?>

    <div class="row h-100">
			<div class="col-sm-12 my-auto">
				<div class="container">
					<div class="card ">
						<div class="card-body">
							<blockquote class="blockquote bq-warning ">
								<p class="bq-title">
									Désolé
								</p>
								<p>
									La représentation que vous souhaitez consulter à été supprimé ou n'existe pas. 
									<br/>
									Vous pouvez rechercher la représentation dans l'onglet "Représentations". 
									<br/>
								</p>
							</blockquote>
						</div>
					</div>
				</div>
			</div>
		</div>

<?php } ?>

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

        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })

    </script>
</html>
<?php
$_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;
if(!isset($_SESSION['login'])){ $_SESSION['logged']=0;$_SESSION['login']='invite';}
if($_SESSION['login']=='invite') $_SESSION['logged']=0;
?>
