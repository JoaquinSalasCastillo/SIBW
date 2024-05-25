<?php
  include("bd.php");

  $bd = new Database();

  $id = $_GET['id'];

  $bd->despublicarActividad($id);

  header("Location: administrar_actividades.php");
  exit;
?>