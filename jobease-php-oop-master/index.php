
<?php 
include '../controls/userinfo.php';
session_start();
if( $_SESSION['email'] == NULL  ){
	header("Location:login.php");
	exit();
}

echo'-------------------------->'.$_SESSION['role'];
echo'-------------------------->'.$_SESSION['userid'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		JobEase
	</title>

	<link rel="stylesheet" href="styles/style.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
	<header>


		<nav class="navbar navbar-expand-md navbar-dark">
			<div class="container">
				<!-- Brand/logo -->
				<a class="navbar-brand" href="#">
					<i class="fas fa-code"></i>
					<h1>JobEase &nbsp &nbsp</h1>
				</a>

				<!-- Toggler/collapsibe Button -->
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- Navbar links -->
				<div class="collapse navbar-collapse" id="collapsibleNavbar">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item active">
							<a class="nav-link" href="#">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Features</a>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="Nontification"  onclick="showNontification()"  role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Nontification
							</a>
						</li>
						<span class="nav-item active">
							<a class="nav-link" href="#">EN</a>
						</span>
						<li class="nav-item">
							<a class="nav-link" href="login.php">Login</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</header>

	<div id="N-card"  style ="position :absolute; right:80px; display:none ; width:400px; background-color:#2a2b81 ; z-index:100; padding:10px; top:140px;">
	<h4 style="text-align:center; color:#dedede;" class ="mb-5">====== Nontification ======</h4>
		<?php 
			$N_Offer = new DatabaseOffer;
			$N_jobOffers = $N_Offer->getUserNontification($_SESSION['userid']);

			foreach($N_jobOffers as $Nonti):
				echo '<p style="color:#dedede;">Congradulation  your Offer Job '.$Nonti['title'].'  On '.$Nonti['company'].' Is accepted.</p>';
				echo'  <hr style="border-color: gray;">';
			endforeach;
		
		?>

	</div> 

	<section action="#" method="get" class="search container">
		
		<div class="search d-flex gap-3 align-items-center">
		<h3 class="mr-3">Find Your Dream Job</h3>
			<div class="form-group mb-2">
				<input type="text" name="keywords"  id="keywords" placeholder="Keywords,location,company..">
			</div>
			
			<button type="submit" onclick="search()" class="btn btn-primary mb-2">Search</button>
			</div>
		
	</section>

	<!--------------------------  card  --------------------->
	<section class="light">
		<h2 class="text-center py-3">Latest Job Listings</h2>
		<div class="container py-2">

		<div id="result">

		</div>
			
		</div>
	</section>

	


	<footer>
		<p>© 2023 JobEase </p>
	</footer> 
	<script>

function showNontification() {
    var Nontif = document.getElementById('N-card');

    if (Nontif.style.display == "block") {
        Nontif.style.display = "none";
    } else {
        Nontif.style.display = "block";
    }
}

        function search() {
			var searchTerm = document.getElementById('keywords').value;
			var xhr = new XMLHttpRequest();
			xhr.open('GET', '../controls/userinfo.php?term=' + searchTerm, true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
				document.getElementById('result').innerHTML = xhr.responseText;
			
				}
				};
				xhr.send();
				}
				search();

		function apply(id){
			var xhr = new XMLHttpRequest();
			xhr.open('GET', '../controls/userinfo.php?applyid=' + id, true);
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
								
			        console.log(xhr.responseText);
				}
				};
				xhr.send();
				
				}
		

    </script>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</html>