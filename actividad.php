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

  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $comentario = $_POST['comentario'];

    $bd->addComentario($idAc, $nombre, $email, $comentario);
  }

  $actividad = $bd->getActividad($idAc, true);
  $imagenes = $bd->getImagenes($idAc);
  $comentarios = $bd->getComentarios($idAc);
  $palabrasProhibidas = $bd->getPalabrasProhibidas();

  $usuarioData = array();

  if(isset($_SESSION['usuario'])) {
    $usuarioData['loggedIn'] = true;
    $usuarioData['usuario'] = $_SESSION['usuario'];
    $usuarioData['rol'] = $_SESSION['usuario']['rol'];
    if($usuarioData['rol'] === 'gestor') {
      $actividad = $bd->getActividad($idAc, false);
    }
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