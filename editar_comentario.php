<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  $bd = new Database();

  // Editar el comentario
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $comentario = $_POST['comentario'];

    // Añadir el mensaje solo si no está ya en el comentario
    if (strpos($comentario, "\"Mensaje editado por el moderador\"") === false) {
        $comentario .= " \"Mensaje editado por el moderador\".";
    }

    $bd->updateComentario($id, $comentario);

    $_SESSION['mensaje'] = "Comentario editado correctamente.";
    header('Location: administrar_comentarios.php');
    exit;
  }

  // Obtener el comentario a editar
  $id = $_GET['id'];
  $comentario = $bd->getComentario($id);

  echo $twig->render(
    'editar_comentario.twig', 
    [
      'comentario' => $comentario
    ]
  );

?>