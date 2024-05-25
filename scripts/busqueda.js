$(document).ready(function() {
    $("#busqueda").on("keyup", function() {
        let query = $(this).val();
        if (query.length > 2) {
            $.ajax({
                url: "buscar_actividades.php",
                method: "GET",
                data: { q: query },
                success: function(data) {
                    let resultados = $("#resultados");
                    resultados.empty();
                    data.forEach(function(actividad) {
                        resultados.append(
                            '<div><a href="actividad.php?id=' + actividad.id + '">' + actividad.nombre + '</a></div>'
                        );
                    });
                }
            });
        } else {
            $("#resultados").empty();
        }
    });
});
