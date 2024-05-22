// Declaración de variables
var calendar;
var Calendar = FullCalendar.Calendar; // Referencia a la clase Calendar de FullCalendar
var events = []; // Array para almacenar los eventos del calendario

$(function () { // Función que se ejecuta cuando se carga la página
    if (!!scheds) { // Si la variable scheds (horarios) existe y no es nula
        Object.keys(scheds).map(k => { // Itera sobre las claves de scheds
            var row = scheds[k] // Obtiene el horario correspondiente a la clave
            // Añade el horario al array de eventos
            events.push({ id: row.id, title: row.title, start: row.start_datetime, end: row.end_datetime });
        })
    }
    var date = new Date() // Obtiene la fecha actual
    var d = date.getDate(), // Día del mes
        m = date.getMonth(), // Mes
        y = date.getFullYear() // Año

    // Crea una nueva instancia de Calendar
    calendar = new Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth', // Vista inicial del calendario
        locale: 'es', // Idioma del calendario
        headerToolbar: { // Configuración de la barra de herramientas del encabezado
            left: 'prev,next today', // Botones a la izquierda
            right: 'dayGridMonth,dayGridWeek,list', // Botones a la derecha
            center: 'title', // Título en el centro
        },
        selectable: true, // Permite seleccionar días
        themeSystem: 'bootstrap', // Sistema de temas
        events: events, // Eventos del calendario
        eventClick: function (info) { // Función que se ejecuta al hacer clic en un evento
            var _details = $('#event-details-modal') // Obtiene el modal de detalles del evento
            var id = info.event.id // Obtiene el id del evento
            if (!!scheds[id]) { // Si el horario correspondiente al id existe
                // Rellena el modal con los detalles del horario
                _details.find('#title').text(scheds[id].title)
                _details.find('#description').text(scheds[id].description)
                _details.find('#start').text(scheds[id].sdate)
                _details.find('#end').text(scheds[id].edate)
                _details.find('#edit,#delete').attr('data-id', id) // Añade el id a los botones de editar y eliminar
                _details.modal('show') // Muestra el modal
            } else {
                alert("Event is undefined"); // Muestra una alerta si el horario no existe
            }
        },
        eventDidMount: function (info) {
            // Aquí puedes hacer algo después de que los eventos se hayan montado
        },
        editable: true // Permite editar los eventos
    });

    calendar.render(); // Renderiza el calendario

    // Listener de restablecimiento de formulario
    $('#schedule-form').on('reset', function () {
        $(this).find('input:hidden').val('') // Vacía los campos ocultos del formulario
        $(this).find('input:visible').first().focus() // Pone el foco en el primer campo visible
    })

    // Botón Editar
    $('#edit').click(function () {
        var id = $(this).attr('data-id') // Obtiene el id del botón
        if (!!scheds[id]) { // Si el horario correspondiente al id existe
            var _form = $('#schedule-form') // Obtiene el formulario
            // Rellena el formulario con los datos del horario
            _form.find('[name="id"]').val(id)
            _form.find('[name="title"]').val(scheds[id].title)
            _form.find('[name="description"]').val(scheds[id].description)
            _form.find('[name="start_datetime"]').val(String(scheds[id].start_datetime).replace(" ", "T"))
            _form.find('[name="end_datetime"]').val(String(scheds[id].end_datetime).replace(" ", "T"))
            $('#event-details-modal').modal('hide') // Oculta el modal
            _form.find('[name="title"]').focus() // Pone el foco en el campo de título
        } else {
            alert("Event is undefined"); // Muestra una alerta si el horario no existe
        }
    })

    $('#delete').click(function () {
        var id = $(this).attr('data-id'); // Obtiene el id del botón
        if (!!scheds[id]) { // Si el horario correspondiente al id existe
            var _conf = confirm("¿Estás seguro de eliminar este evento programado?"); // Muestra un mensaje de confirmación
            if (_conf === true) {
                // Prepara los datos para la solicitud AJAX
                var data = {
                    id: id
                };

                // Realiza la solicitud AJAX
                $.ajax({
                    url: URL + "calendar/delete/" + id, // URL del endpoint para eliminar el evento
                    type: 'POST', // Método HTTP para la solicitud
                    data: data, // Datos a enviar al servidor
                    success: function (response) {
                        alert("Evento eliminado con éxito.");
                        // Refresca el calendario para eliminar el evento visualmente
                        $('#event-details-modal').modal('hide') // Oculta el modal
                        location.reload(); // Recarga la página
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Maneja cualquier error que ocurra durante la solicitud
                        alert("Error al eliminar el evento.");
                    }
                });
            }
        } else {
            alert("Event is undefined"); // Muestra una alerta si el horario no existe
        }
    });

})
