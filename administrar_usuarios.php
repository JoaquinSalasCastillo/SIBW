<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  $bd = new Database();

  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreUsuario = $_POST['nombreUsuario'];
    $nuevoRol = $_POST['nuevoRol'];

    $resultado = $bd->cambiarRol($nombreUsuario, $nuevoRol);

    if(!$resultado) {
      echo "Error al cambiar el rol del usuario";
    }
  }

  $usuarios = $bd->getUsers();

  echo $twig->render(
    'administrar_usuarios.twig', 
    [
      'usuarios' => $usuarios
    ]
  );
?>