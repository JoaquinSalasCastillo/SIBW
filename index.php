<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  $bd = new Database();

  $usuarioData = array();
  $actividades = $bd->getAllActividades(true);

  if(isset($_SESSION['usuario'])) {
    $usuarioData['loggedIn'] = true;
    $usuarioData['usuario'] = $_SESSION['usuario'];
  } else {
    $usuarioData['loggedIn'] = false;
  }

  echo $twig->render(
    'portada.twig', 
    [
      'usuarioData' => $usuarioData,
      'actividades' => $actividades
    ]
  );
?>