<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  $bd = new Database();

  $comentarios = $bd->getAllComentarios();

  echo $twig->render(
    'administrar_comentarios.twig', 
    [
      'comentarios' => $comentarios
    ]
  );

?>