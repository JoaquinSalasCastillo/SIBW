<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  if(isset($_GET['id'])) {
    $idAc = $_GET['id'];
  }
  else {
    $idAc = -1;
  }

  $bd = new Database();

  $actividad = $bd->getActividad($idAc, true);
  
  echo $twig->render('actividad_imprimir.twig', ['actividad' => $actividad]);

?>