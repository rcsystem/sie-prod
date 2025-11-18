$(document).ready(function () {
  // Cargar lista de directorios
  function loadDirectories(query = "") {
    $.get(`${urls}directorio/listar`, function (data) {
      let tableContent = "";
      let sidebarContent = "";

      // Filtrar datos, asegurando que no haya valores null
      let filteredData = data.filter(dir => {
        return Object.values(dir).some(value => {
          return value !== null && value !== undefined && value.toString().toLowerCase().includes(query.toLowerCase());
        });
      });

      filteredData.forEach((dir, index) => {
        tableContent += `<tr>
                          <td>${index + 1}</td>
                          <td>${dir.nombre || ''}</td>
                          <td>${dir.apellido || ''}</td>
                          <td style="text-align:center;">${dir.extension || ''}</td>
                          <td>${dir.email || ''}</td>
                          <td>${dir.departamento || ''}</td>
                          <td>${dir.numero_directo || ''}</td>
                         </tr>`;

        sidebarContent += `<li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>${dir.nombre || ''}</p>
                    </a>
                </li>`;
      });

      $("#directoryTable").html(tableContent);
      $("#directoryList").html(sidebarContent);
    });
  }

  loadDirectories(); // Cargar la lista al inicio

  // Evento de búsqueda en tiempo real
  $("#searchInput").on("keyup", function () {
    let query = $(this).val();
    loadDirectories(query);
  });


    // Enviar formulario para crear directorio
    $("#directoryForm").submit(function (e) {
      e.preventDefault();
      let dirName = $("#directoryName").val();
  
      $.post(
        "<?= base_url('directorio/crear') ?>",
        { nombre: dirName },
        function (response) {
          alert(response.message);
          loadDirectories();
          $("#directoryName").val("");
        },
        "json"
      );
    });


});







