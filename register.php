<?php
  require_once "/usr/local/lib/php/vendor/autoload.php"; 
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $bd = new Database();

    // Primer usuario registrado es superusuario
    $usuarios = $bd->getUsers();
    if (count($usuarios) == 0) {
        $rol = 'superusuario';
    } else {
        $rol = 'registrado';
    }

    if ($bd->registrarUsuario($nombre, $password, $email, $rol)) {
        session_start();
        $_SESSION['usuario'] = $bd->getUser($nombre);
        // Redirigir a la página principal
        header("Location: index.php");
    } else {
        // Mostrar mensaje de error
        echo $twig->render('register.twig', ['error' => 'El usuario ya existe']);
    }
  }
  else {
    echo $twig->render('register.twig', ['error' => '']);
  }
?>