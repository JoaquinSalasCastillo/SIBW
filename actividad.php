<?php
  session_start();
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

  $actividad = $bd->getActividad($idAc);
  $imagenes = $bd->getImagenes($idAc);
  $comentarios = $bd->getComentarios($idAc);
  $palabrasProhibidas = $bd->getPalabrasProhibidas();

  $usuarioData = array();

  if(isset($_SESSION['usuario'])) {
    $usuarioData['loggedIn'] = true;
    $usuarioData['usuario'] = $_SESSION['usuario'];
  } else {
    $usuarioData['loggedIn'] = false;
  }
  
  echo $twig->render(
    'actividad.twig', 
    [
      'usuarioData' => $usuarioData,
      'actividad' => $actividad, 
      'imagenes' => $imagenes,
      'comentarios' => $comentarios,
      'palabrasProhibidas' => $palabrasProhibidas,
      'idAc' => $idAc
    ]
  );
?>