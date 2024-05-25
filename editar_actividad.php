<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $bd = new Database();
  

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $actividad = $bd->getActividad($id);

    $actividad['nombre'] = $_POST['nombre'];
    $actividad['fecha'] = $_POST['fecha'];
    $actividad['precio'] = $_POST['precio'];
    $actividad['descripcion'] = $_POST['descripcion'];
    $actividad['material_necesario'] = $_POST['material_necesario'];
    $actividad['informacion_relacionada'] = $_POST['informacion_relacionada'];

    $bd->updateActividad($actividad);

    header("Location: administrar_actividades.php");
    exit;
  }
  else {
    $id = $_GET['id'];
    $actividad = $bd->getActividad($id);
  
    echo $twig->render(
      'editar_actividad.twig', 
      [
        'actividad' => $actividad
      ]
    );
  }

?>