// Obtener comentarios y boton
const comentarios = document.getElementById('seccion-comentarios');
const boton = document.getElementById('boton-comentarios');

// Evento de click en el boton para mostrar/ocultar comentarios
boton.addEventListener('click', () => {
    // Alternar la clase 'oculto' y 'visible' en la seccion comentarios
    comentarios.classList.toggle('oculto');
    comentarios.classList.toggle('visible');
});

// Agregar evento de envio de formulario
const formulario = document.getElementById('formulario-comentarios');
formulario.addEventListener('submit', (evento) => {
    // Evitar la recarga de la página
    evento.preventDefault();

    // Obtener valores de los campos del formulario
    let nombre = document.getElementById('nombre').value;
    let email = document.getElementById('email').value;
    let comentario = document.getElementById('comentario').value;
    let actividadId = document.getElementById('actividad-id').value;

    // Validar que todos los campos han sido rellenados
    if (!nombre || !email || !comentario) {
        alert('Por favor, rellene todos los campos.');
        return;
    }

    // Validar que el email es valido con expresion regular
    let emailValido = /^\w+@\w+\.\w+$/;
    if(!emailValido.test(email)){
        alert('Introduce un email valido.');
        return;
    }

    // Objeto FormData para enviar los datos del formulario
    let datos = new FormData();
    datos.append('nombre', nombre);
    datos.append('email', email);
    datos.append('comentario', comentario);

    // Hacer solicitud AJAX
    fetch(`actividad.php?id=${actividadId}`, {
        method: 'POST',
        body: datos
    })
    .then(response => response.text())
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
    });

    // // Obtener la fecha y hora actual
    // let fecha = new Date();
    // let minutos = fecha.getMinutes().toString().padStart(2, '0');
    // let fechaYHora = `${fecha.getDate()}/${fecha.getMonth()}/${fecha.getFullYear()} ${fecha.getHours()}:${minutos}`;
    
    // // Crear nuevo elemento de comentario
    // let nuevoComentario = document.createElement('div');
    // nuevoComentario.classList.add('comentario');
    // nuevoComentario.innerHTML = `
    //     <h4>${nombre}</h4>
    //     <p><strong>Email:</strong> ${email}</p>
    //     <p><strong>Fecha y hora:</strong> ${fechaYHora}</p>
    //     <p>${comentario}</p>
    // `;

    // // Agregar el comentario a la seccion de comentarios
    // document.getElementById('comentarios-subidos').appendChild(nuevoComentario);

    // Limpiar los campos del formulario
    document.getElementById('nombre').value = '';
    document.getElementById('email').value = '';
    document.getElementById('comentario').value = '';

});

// Comprobación de palabras prohibidas
const comentarioTexto = document.getElementById('comentario');

comentarioTexto.addEventListener('keyup', () => {
    let texto = comentarioTexto.value;
        palabrasProhibidas.forEach((palabra) => {
        texto = texto.replace(palabra, '*'.repeat(palabra.length));
    });
    comentarioTexto.value = texto;
});