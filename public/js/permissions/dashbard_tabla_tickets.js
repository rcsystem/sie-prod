<?php if (session()->id_user == 1 || session()->id_user == 852 || session()->id_user == 1386 || session()->id_user == 356 || session()->id_user == 1334 || session()->id_user == 31 || session()->id_user == 1189) { ?>
  <!-- Left col -->
  <section class="col-lg-12 connectedSortable">
      <!-- Custom tabs (Charts with tabs)-->
      <div class="card">
          <div class="card-header">
              <h3 id="tickets-card" class="card-title sie-font-bold">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Tickets Generados y Atendidos - <?= date('Y'); ?>
              </h3>

              <div id="tools-card-tickets" class="card-tools content-select">


                  <div id="tickets-reportes">
                      <button class="btn btn-success" onclick="reportTickets()"><i class="far fa-file-excel"></i> Generar Reporte </button>
                  </div>
              </div>
          </div><!-- /.card-header -->
          <div class="card-body">
              <div id="content-permisos" class="tab-content p-0">

              </div>
          </div><!-- /.card-body -->
      </div>
      <!-- /.card -->
  </section>
  <!-- /.Left col -->
<?php } ?>



$.ajax({
    url: `${urls}permisos/total_permisos`, //archivo que recibe la peticion
    type: "post", //método de envio
    processData: false, // dile a jQuery que no procese los datos
    contentType: false, // dile a jQuery que no establezca contentType
    async: true,
    dataType: "json",
    success: function (data) {
      var datasetsF = [];

      // var active = (index > 0) ? "" : "active";

      $("#content-permisos")
        .append(` <div class="chart tab-pane active" id="revenue-chart-1" style="position: relative; height: 300px;">
        <canvas id="chart-canvas-1" height="300" style="height: 300px;"></canvas>
      </div>`);

      var bordercolor = [
        "rgba(39, 229, 42)",
        "rgb(63, 195, 238)",
        "rgba(43, 67, 198)",
        "rgb(63, 195, 238)",
        "rgba(54, 162, 235, 1)",
        "rgba(255, 206, 86, 1)",
        "rgba(75, 192, 192, 1)",
        "rgba(153, 102, 255, 1)",
        "rgba(255, 159, 64, 1)",
        "rgba(231, 76, 60, 1)",
        "rgb(63, 195, 238, 1)",
        "rgba(19, 141, 117,1)",
        "rgba(40, 126, 196,1)",
        "rgba(255,99,132,1)",
      ];
      var color = [
        "rgba(39, 229, 42, 0.3)",
        "rgba(54, 162, 235, 0.3)",
        "rgba(43, 67, 198, 0.3)",
        "rgba(54, 162, 235, 0.3)",
        "rgba(255, 206, 86, 0.3)",
        "rgba(75, 192, 192, 0.3)",
        "rgba(153, 102, 255, 0.3)",
        "rgba(255, 159, 64, 0.3)",
        "rgba(231, 76, 60, 0.3)",
        "rgba(19, 141, 117, 0.3)",
        "rgba(40, 126, 196, 0.3)",
        "rgba(255,99,132, 0.3)",
      ];

      data.forEach(function (resp, index) {
        var salesChartCanvas = document
          .getElementById(`chart-canvas-1`)
          .getContext("2d");

        datasetsF.push(
          (data = {
            label: ["Atendidos"], // column name
            backgroundColor: color[0],
            borderColor: bordercolor[0],
            hoverBackgroundColor: bordercolor[0],
            hoverBorderColor: color[0],
            borderWidth: 1.5,
            fill: true, // `true` for area charts, `false` for regular line charts
            data: [
              resp.Atendidos_Ene,
              resp.Atendidos_Feb,
              resp.Atendidos_Mar,
              resp.Atendidos_Abr,
              resp.Atendidos_May,
              resp.Atendidos_Jun,
              resp.Atendidos_Jul,
              resp.Atendidos_Ago,
              resp.Atendidos_Sep,
              resp.Atendidos_Oct,
              resp.Atendidos_Nov,
              resp.Atendidos_Dic,
            ], // data in that column
          }),
          {
            label: ["Generados"], // column name
            backgroundColor: color[1],
            borderColor: bordercolor[1],
            hoverBackgroundColor: bordercolor[1],
            hoverBorderColor: color[1],
            borderWidth: 1.5,
            fill: true, // `true` for area charts, `false` for regular line charts
            data: [
              resp.Total_Ene,
              resp.Total_Feb,
              resp.Total_Mar,
              resp.Total_Abr,
              resp.Total_May,
              resp.Total_Jun,
              resp.Total_Jul,
              resp.Total_Ago,
              resp.Total_Sep,
              resp.Total_Oct,
              resp.Total_Nov,
              resp.Total_Dic,
            ], // data in that column
          }
        );

        var meses = [
          "Ene",
          "Feb",
          "Mar",
          "Abr",
          "Mayo",
          "Jun",
          "Jul",
          "Ago",
          "Sep",
          "Oct",
          "Nov",
          "Dic",
        ];
        var depto = [index]; // column name
        let canvas = document.getElementById(`chart-canvas-${index}`);

        var salesChartData = {
          deptos: index,
          labels: meses,
          datasets: datasetsF,
        };

        //console.log(salesChartData);

        var salesChartOptions = {
          maintainAspectRatio: false,
          responsive: true,
          hover: {
            onHover: function (event, chartElement) {
              /*  var point = this.getElementAtEvent(e);
               if (point.length) e.target.style.cursor = 'pointer';
               else e.target.style.cursor = 'default'; */
              event.target.style.cursor = chartElement[0]
                ? "pointer"
                : "default";
            },
          },
          legend: {
            display: true,
            labels: {
              filter: (legendItem, data) =>
                typeof legendItem.text !== "undefined",
            },
          },

          scales: {
            xAxes: [
              {
                gridLines: {
                  display: true,
                },
                //stacked: true,
                //maxBarThickness: 14,barPercentage: 2,
              },
            ],
            yAxes: [
              {
                gridLines: {
                  display: false,
                },
                //stacked: true,
              },
            ],
          },
        };

        var salesChart = new Chart(salesChartCanvas, {
          // lgtm[js/unused-local-variable]
          type: "bar",
          data: salesChartData,
          options: salesChartOptions,
        });

        /* canvas.addEventListener('click', function (evt) {
          var firstPoint = salesChart.getElementAtEvent(evt)[0];
          // console.log("Rca: ", firstPoint);
          if (firstPoint) {
            // var deptos = salesChart.data.deptos[firstPoint._index];
            // var value = salesChart.data.datasets[firstPoint._datasetIndex].data[firstPoint._index];
            // alert('Mes: ' + mes + "\nValue: " + value + "\nDeptos: " + deptos + "\nTipos: " + tipo);
            var mes = salesChart.data.labels[firstPoint._index];
            var tipo = salesChart.data.datasets[firstPoint._datasetIndex].label[0];
            var color_permiso = "";
            switch (tipo) {
              case "Personales":
                color_permiso = "bg-success";
                break;
              case "Laborales":
                color_permiso = "bg-primary";
                break;
              case "Medicos":
                color_permiso = "bg-info";
                break;

              default:
                color_permiso = "bg-warning";
                break;
            }
            let data = new FormData();

            data.append("mes", mes);
            data.append("depto", index);
            data.append("tipo_permiso", tipo);

             $.ajax({
              data: data, //datos que se envian a traves de ajax
              url: urls + "permisos/total_permisos", //archivo que recibe la peticion
              type: "post", //método de envio
              processData: false, // dile a jQuery que no procese los datos
              contentType: false, // dile a jQuery que no establezca contentType
              dataType: "json",
              success: function (data) {
                // console.log(data);
                $("#lista_usuarios").empty();
                data.forEach(function (valor, indice) {

                  $("#lista_usuarios").append(`<div class="card profile-header">
                                                  <div class="row">
                                                          <div class="">
                                                              <div class="profile-image float-md-right"> <img class="img-circle image-user shadow-4-strong" src="${valor.profile_img}" alt=""> </div>
                                                          </div>
                                                          <div class="" style="margin-top:0.5rem;">
                                                          <span class="job_post sie-font-bold"><strong>${valor.nombre_solicitante}</strong></span>
                                                              <p class="m-t-0 m-b-0"><small>${valor.job}</small></p>   
                                                          </div>  
                                                          <div class="total_permisos">
                                                          <span class="badge ${color_permiso}">Permisos: <b>${valor.total}</b></span>
                                                          </div>                 
                                                      </div>
                                                  </div>`);
                });

                $('#exampleModal').modal('show');

              },
              error: function (jqXHR, status, error) {
                Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: "Algo salió Mal! Contactar con el Administrador",
                });
                console.log(`Mal Revisa entro en el Error: ${error} Estatus: ${status} JqXHR: ${jqXHR}`);
              },
            }); 

          }
        });*/
        //console.log("sala1: "+datasetsF);
        datasetsF = [];
        salesChartData = "";
        salesChartCanvas = "";
        salesChartOptions = "";
        depto = "";
        //console.log("sala2: "+datasetsF);
      });

      Chart.defaults.global.defaultFontSize = 14;
    },
    error: function (jqXHR, status, error) {
      Swal.fire({
        icon: "error",
        title: "Oops...",
        text: "Algo salió Mal! Contactar con el Administrador",
      });
      console.log(
        `Mal Revisa entro en el Error: ${error} Estatus: ${status} JqXHR: ${jqXHR}`
      );
    },
  });