<?php
  require_once "/usr/local/lib/php/vendor/autoload.php"; 
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    $bd = new Database();

    if ($bd->checkLogin($nombre, $password)) {
        session_start();
        $_SESSION['usuario'] = $bd->getUser($nombre);
        // Redirigir a la página principal
        header("Location: index.php");
    } else {
        // Mostrar mensaje de error
        echo $twig->render('login.twig', ['error' => 'Usuario o contraseña incorrectos']);
    }
  } 
  else {
    echo $twig->render('login.twig', ['error' => '']);
  }
?>