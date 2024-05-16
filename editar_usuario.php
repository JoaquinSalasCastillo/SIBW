<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $bd = new Database();

    if ($bd->editarUsuario($_SESSION['usuario']['nombre'], $nombre, $password, $email)) {
        $_SESSION['usuario'] = $bd->getUser($nombre);
        
        // Redirigir a la página principal
        header("Location: index.php");
    } else {
        // Mostrar mensaje de error
        echo $twig->render('editar_usuario.twig', ['error' => 'Error al editar el usuario']);
    }
  }
  else {
    echo $twig->render('editar_usuario.twig', ['error' => '']);
  }
  
?>