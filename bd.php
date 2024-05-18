<?php
    class Database{
        private $mysqli;

        public function __construct(){
            $this->mysqli = new mysqli("database", "joaquin", "joaquin", "SIBW");
            if ($this->mysqli->connect_errno) {
                throw new Exception("Fallo al conectar: " . $this->mysqli->connect_error);
            }
        }

        /******************** ACTIVIDADES **********************/
        public function getActividad($idAc) {            
            // Preparar la sentencia
            $stmt = $this->mysqli->prepare(
                "SELECT nombre, 
                        fecha, 
                        precio, 
                        descripcion, 
                        material_necesario, 
                        informacion_relacionada, 
                        imagen
                FROM actividades 
                WHERE id = ?"
            );
            // Vincular los parametros
            $stmt->bind_param("i", $idAc);
            // Ejecutar la sentencia
            $stmt->execute();
            // Obtener el resultado
            $res = $stmt->get_result();
    
            $actividad = array(
                'nombre' => 'XXXX', 
                'fecha' => 'YYYY', 
                'precio' => 'ZZZZ',
                'descripcion' => 'AAAA', 
                'material_necesario' => 'BBBB', 
                'informacion_relacionada' => 'CCCC',
                'imagen' => 'DDDD'
            );
            if($res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $actividad = array(
                    'nombre' => $row['nombre'], 
                    'fecha' => $row['fecha'], 
                    'precio' => $row['precio'],
                    'descripcion' => $row['descripcion'], 
                    'material_necesario' => explode(',', $row['material_necesario']), 
                    'informacion_relacionada' => explode(',', $row['informacion_relacionada']),
                    'imagen' => $row['imagen']
                );
            }
    
            return $actividad;
        }
        public function getAllActividades() {
            $stmt = $this->mysqli->prepare(
                "SELECT * FROM actividades"
            );
            $stmt->execute();
            $res = $stmt->get_result();

            $actividades = array();
            while($row = $res->fetch_assoc()) {
                $actividad = array(
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'fecha' => $row['fecha'],
                    'precio' => $row['precio'],
                    'descripcion' => $row['descripcion'],
                    'material_necesario' => $row['material_necesario'],
                    'informacion_relacionada' => $row['informacion_relacionada'],
                    'imagen' => $row['imagen']
                );
                array_push($actividades, $actividad);
            }

            return $actividades;
        }
        public function addActividad($nombre, $fecha, $precio, $descripcion, $material_necesario, $informacion_relacionada, $imagen, $imagenes_contenido) {
            $stmt = $this->mysqli->prepare(
                "INSERT INTO actividades (
                        nombre, 
                        fecha, 
                        precio, 
                        descripcion, 
                        material_necesario,
                        informacion_relacionada,
                        imagen)
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("ssissss", $nombre, $fecha, $precio, $descripcion, $material_necesario, $informacion_relacionada, $imagen);
            $stmt->execute();

            if($stmt->error) {
                echo "Error al insertar actividad: " . $stmt->error; 
            }
            else {
                $actividad_id = $this->mysqli->insert_id;

                foreach($imagenes_contenido as $ruta_imagen) {
                    $this->addImagen($actividad_id, $ruta_imagen);
                }
            }
        }
        public function updateActividad($actividad) {
            $stmt = $this->mysqli->prepare(
                "UPDATE actividades SET 
                    nombre = ?, 
                    fecha = ?, 
                    precio = ?, 
                    descripcion = ?, 
                    material_necesario = ?, 
                    informacion_relacionada = ?, 
                    imagen = ?
                WHERE id = ?"
            );
            $stmt->bind_param("ssissssi", 
                $actividad['nombre'], 
                $actividad['fecha'], 
                $actividad['precio'], 
                $actividad['descripcion'], 
                $actividad['material_necesario'], 
                $actividad['informacion_relacionada'], 
                $actividad['imagen'], 
                $actividad['id']
            );
            $stmt->execute();
        }
        public function deleteActividad($idAc) {
            // Borrar imagenes asociadas a la actividad
            $stmt = $this->mysqli->prepare(
                "DELETE FROM imagenes WHERE actividad_id = ?"
            );
            $stmt->bind_param("i", $idAc);
            $stmt->execute();

            // Borrar comentarios asociados a la actividad
            $stmt = $this->mysqli->prepare(
                "DELETE FROM comentarios WHERE actividad_id = ?"
            );
            $stmt->bind_param("i", $idAc);
            $stmt->execute();

            // Borrar actividad
            $stmt = $this->mysqli->prepare(
                "DELETE FROM actividades WHERE id = ?"
            );
            $stmt->bind_param("i", $idAc);
            $stmt->execute();
        }
        public function getImagenes($idAc) {
            // Preparar la sentencia
            $stmt = $this->mysqli->prepare(
                "SELECT ruta
                FROM imagenes
                WHERE actividad_id = ?"
            );
            // Vincular los parametros
            $stmt->bind_param("i", $idAc);
            // Ejecutar la sentencia
            $stmt->execute();
            // Obtener el resultado
            $res = $stmt->get_result();
    
            $imagenes = array();
            while($row = $res->fetch_assoc()) {
                $imagen = array(
                    'ruta' => $row['ruta']
                );
                array_push($imagenes, $imagen);
            }
    
            return $imagenes;
        }
        public function getAllImagenes() {
            $stmt = $this->mysqli->prepare(
                "SELECT actividad_id, ruta FROM imagenes"
            );
            $stmt->execute();
            $res = $stmt->get_result();

            $imagenes = array();
            while($row = $res->fetch_assoc()) {
                $imagen = array(
                    'actividad_id' => $row['actividad_id'],
                    'ruta' => $row['ruta']
                );
                array_push($imagenes, $imagen);
            }
            return $imagenes;
        }
        public function addImagen($actividad_id, $ruta) {
            $stmt = $this->mysqli->prepare(
                "INSERT INTO imagenes (actividad_id, ruta) VALUES (?, ?)"
            );
            $stmt->bind_param("is", $actividad_id, $ruta);
            $stmt->execute();
        }
    

        /******************** USUARIOS **********************/
        public function registrarUsuario($nombre, $password, $email, $rol) {
            // Comprobar si el usuario ya existe
            $stmt = $this->mysqli->prepare(
                "SELECT * FROM usuarios WHERE nombre = ? OR email = ?"
            );
            $stmt->bind_param("ss", $nombre, $email);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows > 0) {
                // El usuario ya existe
                return false;
            }

            // Registrar el usuario nuevo
            $stmt = $this->mysqli->prepare(
                "INSERT INTO usuarios (nombre, password, email, rol) VALUES (?, ?, ?, ?)"
            );
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("ssss", $nombre, $hashedPassword, $email, $rol);
            $stmt->execute();

            return true;
        }
        public function checkLogin($nombre, $password) {
            // Validar los datos de entrada
            if (empty($nombre) || empty($password)) {
                return false;
            }

            $stmt = $this->mysqli->prepare(
                "SELECT password FROM usuarios WHERE nombre = ?"
            );
            $stmt->bind_param("s", $nombre);
            $stmt->execute();

            $res = $stmt->get_result();
            if($res->num_rows > 0) {
                $usuario = $res->fetch_assoc();
                return password_verify($password, $usuario['password']);
            }
            return false;
        }
        public function getUser($nombre) {
            $stmt = $this->mysqli->prepare(
                "SELECT * FROM usuarios WHERE nombre = ?"
            );
            $stmt->bind_param("s", $nombre);
            $stmt->execute();

            $res = $stmt->get_result();
            if($res->num_rows > 0) {
                return $res->fetch_assoc();
            }
            return null;
        }
        public function getUsers() {
            $stmt = $this->mysqli->prepare(
                "SELECT * FROM usuarios"
            );
            $stmt->execute();
        
            $res = $stmt->get_result();
            $usuarios = [];
            while($usuario = $res->fetch_assoc()) {
                $usuarios[] = $usuario;
            }
            return $usuarios;
        }
        public function editarUsuario($nombre, $nuevoNombre, $nuevoPassword, $nuevoEmail) {
            // Comprobar si el usuario existe
            $stmt = $this->mysqli->prepare(
                "SELECT * FROM usuarios WHERE nombre = ?"
            );
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $res = $stmt->get_result();
            if($res->num_rows == 0) {
                // El usuario no existe
                return false;
            }
        
            // Actualizar los datos del usuario
            $stmt = $this->mysqli->prepare(
                "UPDATE usuarios SET nombre = ?, password = ?, email = ? WHERE nombre = ?"
            );
            $hashedPassword = password_hash($nuevoPassword, PASSWORD_DEFAULT);
            $stmt->bind_param("ssss", $nuevoNombre, $hashedPassword, $nuevoEmail, $nombre);
            $stmt->execute();

            return true;
        }
        public function cambiarRol($nombre, $nuevoRol) {
            // Si el nuevo rol no es 'superusuario', se comprueba si hay otros superusuarios para poder cambiarlo
            if ($nuevoRol !== 'superusuario') {
                $stmt = $this->mysqli->prepare(
                    "SELECT COUNT(*) FROM usuarios WHERE rol = 'superusuario' AND nombre != ?"
                );
                $stmt->bind_param("s", $nombre);
                $stmt->execute();
                $result = $stmt->get_result();
                $count = $result->fetch_array(MYSQLI_NUM)[0];
                // Si no hay otros superusuarios, no se puede cambiar el rol
                if ($count == 0) {
                    return false;
                }
            }

            $stmt = $this->mysqli->prepare(
                "UPDATE usuarios SET rol = ? WHERE nombre = ?"
            );
            $stmt->bind_param("ss", $nuevoRol, $nombre);
            $stmt->execute();
            return true;
        }
        public function esSuperusuario($nombre) {
            $usuario = $this->getUser($nombre);
            return $usuario['rol'] == 'superusuario';
        }

        /******************** COMENTARIOS **********************/
        public function getComentarios($idAc) {            
            // Preparar la sentencia
            $stmt = $this->mysqli->prepare(
                "SELECT id,
                        nombre, 
                        email,
                        fecha_hora, 
                        comentario
                FROM comentarios
                WHERE actividad_id = ?"
            );
            // Vincular los parametros
            $stmt->bind_param("i", $idAc);
            // Ejecutar la sentencia
            $stmt->execute();
            // Obtener el resultado
            $res = $stmt->get_result();
            
            $comentarios = array();
            if($res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    $comentario = array(
                        'id' => $row['id'], 
                        'nombre' => $row['nombre'], 
                        'email' => $row['email'], 
                        'fecha_hora' => $row['fecha_hora'],
                        'comentario' => $row['comentario']
                    );
                    array_push($comentarios, $comentario);
                }
            }
            return $comentarios;
        }
        public function getAllComentarios() {
            // Preparar la sentencia
            $stmt = $this->mysqli->prepare(
                "SELECT id,
                        actividad_id,
                        nombre, 
                        email,
                        fecha_hora, 
                        comentario
                FROM comentarios"
            );
            // Ejecutar la sentencia
            $stmt->execute();
            // Obtener el resultado
            $res = $stmt->get_result();
            
            $comentarios = array();
            if($res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    $comentario = array(
                        'id' => $row['id'],
                        'actividad_id' => $row['actividad_id'],
                        'nombre' => $row['nombre'], 
                        'email' => $row['email'], 
                        'fecha_hora' => $row['fecha_hora'],
                        'comentario' => $row['comentario']
                    );
                    array_push($comentarios, $comentario);
                }
            }
            return $comentarios;
        }
        public function getComentario($idComentario) {
            $stmt = $this->mysqli->prepare("SELECT * FROM comentarios WHERE id = ?");
            $stmt->bind_param("i", $idComentario);
            $stmt->execute();

            $res = $stmt->get_result();
            $comentario = $res->fetch_assoc();
            return $comentario;
        }
        public function updateComentario($idComentario, $comentario) {
            $stmt = $this->mysqli->prepare(
                "UPDATE comentarios SET comentario = ? WHERE id = ?"
            );
            $stmt->bind_param("si", $comentario, $idComentario);
            $stmt->execute();
        }
        public function borrarComentario($idComentario) {
            $stmt = $this->mysqli->prepare(
                "DELETE FROM comentarios WHERE id = ?"
            );
            $stmt->bind_param("i", $idComentario);
            $stmt->execute();
        }

        public function getPalabrasProhibidas() {            
            // Preparar la sentencia
            $stmt = $this->mysqli->prepare(
                "SELECT palabra
                FROM palabras_prohibidas"
            );
            // Ejecutar la sentencia
            $stmt->execute();
            // Obtener el resultado
            $res = $stmt->get_result();
        
            $palabrasProhibidas = array();
            while($row = $res->fetch_assoc()) {
                $palabrasProhibidas[] = $row['palabra'];
            }
        
            return $palabrasProhibidas;
        }
    }

?>