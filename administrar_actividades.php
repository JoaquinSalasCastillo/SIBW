<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $bd = new Database();

  $actividades = $bd->getAllActividades(false);
  $imagenes = $bd->getAllImagenes();

  echo $twig->render(
    'administrar_actividades.twig', 
    [
      'actividades' => $actividades,
      'imagenes' => $imagenes
    ]
  );
?>