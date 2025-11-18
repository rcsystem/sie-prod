<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php

/* $colorStatus = ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'color:white;background:#28a745;' : 'color:black;background:#efef2f;';
$txtStatus = ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'Autorizado' : 'Pendiente'; */

if ($notify["num_permiso_mes"] == 4 && $notify["tipo_permiso"] == "PERSONAL") {
  $tipo_permiso = "PERSONAL | PERMISO EXTRA";
  $link = "https://sie.grupowalworth.com/permisos/autorizar_new";
  $color = "color:white;background:#F65E0A;";
} elseif ($notify["num_permiso_mes"] == 5 && $notify["tipo_permiso"] == "PERSONAL") {
  $tipo_permiso = "PERSONAL | QUINTO PERMISO";
  $link = "https://sie.grupowalworth.com/permisos/autorizar-direcion-general";
  $color = "color:white;background:#F7304F;";
} else {
  $tipo_permiso = ($notify["id_tipo_permiso"] == 4) ? 'DÍA POR: ' . $notify["tipo_permiso"] : $notify["tipo_permiso"];
  $link = "https://sie.grupowalworth.com/permisos/autorizar";
  // $color = "color:white;background:" . $color->color . ";";

  $color = ($notify["tipo_permiso"] == "ATENCIÓN PSICOLÓGICA") ? "color:white;background:#16C9BA;" :"color:". $color->color.";";
}
?>

<body style="font-family: 'Segoe UI', Arial, sans-serif; margin: 0; padding: 20px; background-color: #f5f7fa; color: #333;">
  <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden;">
    <!-- Encabezado -->
    <div style="background: #11344b; padding: 20px; text-align: center;">
      <h2 style="color: white; margin: 0; font-size: 22px;">Nueva Solicitud de Permiso</h2>
    </div>
    
    <!-- Cuerpo -->
    <div style="padding: 25px;">
      <p style="font-size: 16px; margin-bottom: 25px;">
        El usuario <strong style="color: #11344b;"><?= $notify["user"] ?></strong> ha generado una nueva solicitud.
      </p>
      
      <!-- Tarjeta de información -->
      <div style="border: 1px solid #e1e5eb; border-radius: 6px; margin-bottom: 25px;">
        <!-- Estado destacado -->
        <div style="background: <?= ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? '#28a745' : '#ffc107' ?>; padding: 10px; text-align: center;">
          <strong style="color: <?= ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'white' : 'black' ?>; font-size: 16px;">
            <?= ($notify["id_tipo_permiso"] == 4 || $notify["id_tipo_permiso"] == 6) ? 'AUTORIZADO' : 'PENDIENTE' ?>
          </strong>
        </div>
        
        <!-- Detalles -->
        <div style="padding: 15px;">
          <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
            <div style="flex: 1; font-weight: bold; color: #666;">Fecha Creación:</div>
            <div style="flex: 2;"><?= $notify["fecha_creacion"] ?></div>
          </div>
          
          <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
            <div style="flex: 1; font-weight: bold; color: #666;">Tipo de Permiso:</div>
            <div style="flex: 2; <?= $color ?>"><b><?= $tipo_permiso ?></b></div>
          </div>
          
          <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
            <div style="flex: 1; font-weight: bold; color: #666;">Tipo Empleado:</div>
            <div style="flex: 2;"><?= $notify["tipo_empleado"] ?></div>
          </div>
          
          <?php if ($notify["tipo_empleado"] == 'Sindicalizado') { ?>
            <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
              <div style="flex: 1; font-weight: bold; color: #666;">Goce de Sueldo:</div>
              <div style="flex: 2;"><?= $notify["goce_sueldo"] ?></div>
            </div>
            
            <?php if ($notify["goce_sueldo"] == 'SI') { ?>
              <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
                <div style="flex: 1; font-weight: bold; color: #666;">Tiempo:</div>
                <div style="flex: 2;"><?= ($notify["pago_deuda"] = 1) ? 'PAGADO' : 'POR PAGAR'; ?></div>
              </div>
            <?php } ?>
          <?php } ?>
          
          <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
            <div style="flex: 1; font-weight: bold; color: #666;">Número de Nómina:</div>
            <div style="flex: 2;"><?= $notify["num_nomina"] ?></div>
          </div>
          
          <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
            <div style="flex: 1; font-weight: bold; color: #666;">Departamento:</div>
            <div style="flex: 2;"><?= $notify["departamento"] ?></div>
          </div>
          
          <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
            <div style="flex: 1; font-weight: bold; color: #666;">Puesto:</div>
            <div style="flex: 2;"><?= session()->job_position ?></div>
          </div>
          
          <?php if ($notify["hora_salida"] != "") { ?>
            <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
              <div style="flex: 1; font-weight: bold; color: #666;">Fecha/Hora Salida:</div>
              <div style="flex: 2;"><?= $notify["fecha_salida"] ?> a las <?= $notify["hora_salida"] ?></div>
            </div>
          <?php } ?>
          
          <?php if ($notify["hora_entrada"] != "") { ?>
            <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
              <div style="flex: 1; font-weight: bold; color: #666;">Fecha/Hora Entrada:</div>
              <div style="flex: 2;"><?= $notify["fecha_entrada"] ?> a las <?= $notify["hora_entrada"] ?></div>
            </div>
          <?php } ?>
          
          <?php if ($notify["inasistencia_del"] != "") { ?>
            <div style="display: flex; margin-bottom: 12px; border-bottom: 1px solid #f0f0f0; padding-bottom: 12px;">
              <div style="flex: 1; font-weight: bold; color: #666;">Inasistencia:</div>
              <div style="flex: 2;">Del <?= $notify["inasistencia_del"] ?> al <?= $notify["inasistencia_al"] ?></div>
            </div>
          <?php } ?>
          
          <div style="display: flex; margin-bottom: 12px;">
            <div style="flex: 1; font-weight: bold; color: #666;">Observaciones:</div>
            <div style="flex: 2;"><?= $notify["observaciones"] ?></div>
          </div>
        </div>
      </div>
      
      <!-- Botón de acción -->
      <div style="text-align: center; margin-top: 25px;">
        <a href="<?php echo $link; ?>" target="_blank" style="
          display: inline-block;
          background-color: #11344b;
          color: white;
          text-decoration: none;
          font-weight: bold;
          padding: 12px 30px;
          border-radius: 4px;
          transition: background-color 0.3s;
          box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        " onmouseover="this.style.backgroundColor='#0a2536'" onmouseout="this.style.backgroundColor='#11344b'">
          Autorizar Permiso
        </a>
      </div>
    </div>
    
    <!-- Pie de página -->
    <div style="background: #f0f2f5; padding: 15px; text-align: center; font-size: 12px; color: #666;">
      <p style="margin: 0;">© <?= date('Y') ?> Walworth TI.</p>
    </div>
  </div>
</body>



</html>