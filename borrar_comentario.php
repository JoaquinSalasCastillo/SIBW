<?php
  include("bd.php");

  $bd = new Database();

  $id = $_GET['id'];

  $bd->borrarComentario($id);

  header('Location: administrar_comentarios.php');
  exit;
?>