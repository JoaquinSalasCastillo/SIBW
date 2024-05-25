<?php
  session_start();
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include("bd.php");

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  $bd = new Database();

  $varsParaTwig = array();

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $material_necesario = $_POST['material_necesario'];
    $informacion_relacionada = $_POST['informacion_relacionada'];
    $publicado = isset($_POST['publicado']) ? 1 : 0;

    $imagenes = ['imagen_portada', 'imagen_contenido1', 'imagen_contenido2'];
    $uploaded_files = array();
    $errors = array();

    foreach ($imagenes as $imagen) {
      if(isset($_FILES[$imagen])){
          $file_name = $_FILES[$imagen]['name'];
          $file_size = $_FILES[$imagen]['size'];
          $file_tmp = $_FILES[$imagen]['tmp_name'];
          $file_type = $_FILES[$imagen]['type'];

          $file_parts = explode('.', $_FILES[$imagen]['name']);
          $file_ext = strtolower(end($file_parts));

          
          $extensions= array("jpeg","jpg","png");
          
          if (in_array($file_ext,$extensions) === false){
            $errors[] = "Extensión no permitida, elige una imagen JPEG o PNG.";
          }
          
          if ($file_size > 2097152){
            $errors[] = 'Tamaño del fichero demasiado grande';
          }
          
          if (empty($errors)==true) {
            move_uploaded_file($file_tmp, "img/" . $file_name);
            $uploaded_files[$imagen] = "img/" . $file_name;
          }
      }
    }

    if (sizeof($errors) > 0) {
      $varsParaTwig['errores'] = $errors;
    }
    else {
        $imagenes_portada = str_replace('img/', '', $uploaded_files['imagen_portada']);
        $imagenes_contenido = array(
            str_replace('img/', '', $uploaded_files['imagen_contenido1']),
            str_replace('img/', '', $uploaded_files['imagen_contenido2'])
        );
        $bd->addActividad($nombre, $fecha, $precio, $descripcion, $material_necesario, $informacion_relacionada, $imagenes_portada, $imagenes_contenido, $publicado);

        header("Location: administrar_actividades.php");
        exit;
    }

    $varsParaTwig['imagenes'] = $uploaded_files;
  }

  echo $twig->render('anadir_actividad.twig', $varsParaTwig);
?>