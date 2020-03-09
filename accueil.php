<?php session_start();

$titre="accueil";
include("includes/identifiants.php");
include("includes/debut.php");
include("includes/menu.php");

?>
<img class="main-image" src="css/images/BIENVENUE.png" alt="Main image">
<h1 class="text-center main-titre">Retrouvez les différents espaces à votre disposition ci-dessous</h1>
<div class="container-fluid">
    <div class="row d-flex justify-content-center">
    <a href="./informations.php">
   <div class="card col-12 col-md-3 mt-5 mb-5 mr-md-5"> 
    <h3 class="decoration-none text-center my-4">Informations</h3>
  <img class="card-img-top" src="css/images/Canva - Man and Woman Standing Alongside With Their Children.jpg" alt="Card image cap">
  <div class="card-body">
    <p class="card-text decoration-none">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
   </div> </a>
  
</div>
<a href="./index.php">
<div class="card col-12 col-md-3 mt-5 mb-5 mr-md-5" style="width: 18rem;">
<h3 class="decoration-none text-center my-4">Forum</h3>
  <img class="card-img-top" src="css/images/Canva - Man and Woman Standing Alongside With Their Children.jpg" alt="Card image cap">
  <div class="card-body">
    <p class="card-text decoration-none">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div></a>
  
</div>
<a href="./partage.php">
<div class="card col-12 col-md-3 mt-5 mb-5" style="width: 18rem;">
<h3 class="decoration-none text-center my-4">News</h3>
  <img class="card-img-top" src="css/images/Canva - Man and Woman Standing Alongside With Their Children.jpg" alt="Card image cap">
  <div class="card-body">
    <p class="card-text decoration-none">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div></a>
  
</div>
    </div>

</div>
<?php include('includes/footer.php') ?>