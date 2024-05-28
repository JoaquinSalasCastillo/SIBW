<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $bd = new Database();

  if(isset($_GET['busqueda']) && $_GET['busqueda'] !== ''){
    $actividades = $bd->buscarActividadesPorNombreDescripcion($_GET['busqueda']);
  }
  else {
    $actividades = $bd->getAllActividades(false);
  }


  echo $twig->render(
    'administrar_actividades.twig', 
    [
      'actividades' => $actividades,
    ]
  );
?>