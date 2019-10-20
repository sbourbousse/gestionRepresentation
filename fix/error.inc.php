<?php if(!isset($_SESSION)) session_start();include_once "fix/connectBDD.php";?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>Erreur</title>
		<!-- STYLES -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/mdb.min.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet" type="text/css">
		<!-- MDBootstrap Datatables  -->
		<link href="css/addons/datatables.min.css" rel="stylesheet">
		<!-- FontAwesome -->
		<link href="css/all.min.css" rel="stylesheet">
		<?php include "fix/favicon.php" ?>
	</head>
	<body class="bg">
		<?php include "fix/navbar.inc.php"; ?>
		<div class="row h-100">
			<div class="col-sm-12" style="margin-top:15%">
				<div class="container">
					<div class="card ">
						<div class="card-body">
						<div class="row">
							<div class="col-9">
								<blockquote class="blockquote bq-warning ">
									<p class="bq-title">
										Accès Refusé
									</p>
									<p>
										Désolé, vous n'etes pas autorisé à consulter cette page. 
										<br/>
										Verifier que vous êtes bien connecté. 
										<br/>
									</p>
								</blockquote>
							</div>
							<div class="col-2">
								<div>
								<i class="fas fa-ban text-warning fa-10x mt-4"></i>
								</div>
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
			itemSelector: '.grid-item',columnWidth: '.grid-sizer',percentPosition: true
		});
	</script>
</html>
<?php $_SESSION['echecAuth']=0;$_SESSION['demandeAuth']=0;if(!isset($_SESSION['login'])){ $_SESSION['logged']=0;$_SESSION['login']='invite';}if($_SESSION['login']=='invite') $_SESSION['logged']=0;?>
