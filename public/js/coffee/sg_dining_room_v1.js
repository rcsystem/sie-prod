document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
      locale: 'es', 
      initialView: 'dayGridMonth',
      selectable: true,
      editable: true,
      
      events: `${urls}comedor/obtener_eventos`,

      dateClick: function(info) {
          var selectedDate = info.dateStr;

          // Usar SweetAlert para obtener el nombre del usuario
          Swal.fire({
              title: `Ingresa el nombre del usuario para el ${selectedDate}`,
              input: 'text',
              inputLabel: 'Nombre del Usuario',
              inputPlaceholder: 'Escribe el nombre aquí',
              showCancelButton: true,
              confirmButtonText: 'Guardar',
              cancelButtonText: 'Cancelar'
          }).then((result) => {
              if (result.isConfirmed && result.value) {
                  var user = result.value;

                  // Enviar los datos a guardar en CodeIgniter
                  $.ajax({
                      url: `${urls}comedor/guardar_evento`,
                      method: 'POST',
                      data: {
                          fecha: selectedDate,
                          usuario: user
                      },
                      success: function() {
                          calendar.addEvent({
                              title: user,
                              start: selectedDate,
                              allDay: true
                          });
                          Swal.fire('Guardado', 'Usuario agendado correctamente.', 'success');
                      },
                      error: function() {
                          Swal.fire('Error', 'Hubo un error al guardar el evento.', 'error');
                      }
                  });
              }
          });
      }
  });

  calendar.render();
});

