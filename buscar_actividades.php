<?php
  session_start();
  include("bd.php");
  

  $bd = new Database();

  if (isset($_GET['q'])) {
    $query = $_GET['q'];

    $esGestor = isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'gestor';

    $actividades = $bd->buscarActividades($query, $esGestor);

    header('Content-Type: application/json');
    echo json_encode($actividades);
  }
?>