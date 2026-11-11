# Web de actividades de montaña (SIBW)

Proyecto de la asignatura *Sistemas de Información Basados en Web*, Grado en Ingeniería Informática, Universidad de Granada, primavera de 2024.

Una web para consultar, comentar y gestionar actividades de montaña, hecha en PHP sin framework, con plantillas Twig y MySQL.

## Funcionalidades

- **Parte pública:** portada con el listado de actividades, una página por actividad con galería de imágenes y comentarios, y una versión para imprimir.
- **Comentarios:** validados en el cliente con JavaScript y filtrados con una lista de palabras prohibidas.
- **Buscador en vivo:** peticiones AJAX con jQuery a un endpoint PHP que devuelve JSON.
- **Usuarios:** registro e inicio de sesión con contraseñas hasheadas (`password_hash` / `password_verify`) y sesiones de PHP.
- **Roles:** usuario registrado, moderador, gestor y superusuario (el primer usuario que se registra). Cada rol ve distintas opciones de administración.
- **Panel de administración:** crear, editar, borrar, publicar y despublicar actividades (solo los gestores ven las no publicadas), subir imágenes, moderar comentarios y gestionar usuarios y sus roles.

## Cómo está hecho

- Un script PHP por página (`index.php`, `actividad.php`, `login.php`, ...). Cada uno lee la petición, llama a la capa de base de datos y renderiza una plantilla Twig.
- `bd.php`: una clase `Database` que encapsula `mysqli`. Todas las consultas son sentencias preparadas, para evitar inyección SQL.
- `templates/`: plantillas Twig con layouts base compartidos (`base.twig`, `base_administrar.twig`, ...).
- `scripts/`: JavaScript del cliente (validación del formulario de comentarios y buscador en vivo).
- `styles/`: CSS de cada página.

Se ejecutaba en local con contenedores Docker (PHP y MySQL). La configuración de Docker, el esquema de la base de datos y las imágenes no están en este repositorio.

## Qué haría distinto hoy

- Leer las credenciales de la base de datos de variables de entorno en lugar de dejarlas en el código (eran solo para los contenedores locales de la asignatura).
- Escapar los resultados del buscador antes de insertarlos en el HTML del cliente.
- Usar un framework como Symfony para el enrutado y los controladores, en lugar de un script por página.
- Añadir tests automatizados y hacer commits poco a poco, trabajando con Pull Requests.
