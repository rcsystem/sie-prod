/**
 * ARCHIVO MODULO LIBERACIÓN
 * AUTOR:RAFAEL CRUZ AGUILAR
 * EDITOR:HORUS RIVAS PEDRAZA
 * EMAIL:RAFAEL.CRUZ.AGUILAR1@GMAIL.COM
 * CEL:5565429649
 */
$(document).ready(function () {
  tbl_viaticos = $("#tabla_items_liberation").dataTable({
    processing: true,
    ajax: {
        method: "post",
        url: `${urls}liberacion/todos_los_items`,
        dataSrc: function (json) {
            return json;
        },
    },
    lengthChange: true,
    ordering: true,
    responsive: true,
    autoWidth: false,
    rowId: "id",
    dom: "lfrtip",
    buttons: [
    ],
    language: {
      url: "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json",
    },
    columns: [
      {
        data: "id",
        title: "ID",
        className: "text-center"
      },
      {
        data: "name",
        title: "Equipo",
        className: "text-center"
      },
      {
        data: "description",
        title: "DESCRIPCIÓN",
        className: "text-center"
      },
      {
        data: "department_name",
        title: "DEPARTAMENTO",
        className: "text-center"
      },
      {
        data: null,
        render: function (data) {
            return `
            <div class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm" title="Desactivar" onclick="desactivarItem(${data.id})">
                <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            `;
        },
        title: "ACCIONES",
        className: "text-center"
      }
    ],
    destroy: "true",
    order: [[0, "DESC"]],

    createdRow: (row, data) => {
      $(row).attr("id", "liberation_" + data.id);
    },
  }).DataTable();
  $('#tabla_items_liberation thead').addClass('thead-dark text-center');
});

$("#formCreateItem").on("submit", function (e) {
  e.preventDefault();

  const formData = $(this).serialize();
  console.log(formData);
  $.ajax({
    type: "POST",
    url: `${urls}liberacion/crear_item`, // Asegúrate de que esta URL es correcta
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.success) {
        $("#modalCreateItem").modal("hide");
        $("#formCreateItem")[0].reset();
        tbl_viaticos.ajax.reload(null, false); // refresca sin reiniciar paginación
        Swal.fire("¡Éxito!", "Ítem agregado correctamente", "success");
      } else {
        Swal.fire("Error", response.message || "Hubo un problema al guardar.", "error");
      }
    },
    error: function () {
      Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
    }
  });
});


function abrirSolicitudModal() {
  $('#modalCreateItem').modal('show');
}

function desactivarItem(id_item) {
    if (!confirm('¿Seguro que deseas desactivar este item y todos sus registros relacionados?')) return;

    $.ajax({
        method: "POST",
        url: `${urls}liberacion/desactivar_items`,
        data: { id_item },
        success: function (res) {
            if (res.success) {
              tbl_viaticos.ajax.reload(null, false); // refresca sin reiniciar paginación
              Swal.fire("¡Éxito!", "se completo la acción correctamente", "success");
            } else {
                Swal.fire("Error", res.error || "Hubo un problema al guardar.", "error");
            }
        },
        error: function () {
            alert('Error en la petición para desactivar la solicitud.');
        }
    });
}