<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vale de Salida de Materias Primas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .vale {
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .vale h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .vale h2 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .vale table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .vale table th,
        .vale table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            font-size: 12px;
        }

        .vale table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .vale .footer {
            text-align: right;
            font-size: 12px;
            margin-top: 20px;
        }

        .vale .logo {
            width: 180px;
            height: 38px;
        }

        .vale .header-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .vale .header-table td {
            text-align: center;
            vertical-align: middle;
        }

        /******************* */
        .tabla-contenedor {
            border-radius: 15px;
            /* Redondea las esquinas */
            /* Oculta el contenido que sobresale */
            border: 1px solid #000;
            /* Borde para ver el efecto */
            width: 100%;
            margin: 0 auto;
            height: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 0.8px solid #000;
            padding: 9px;
            text-align: center;
        }

        th {
            background-color: #fff;
        }

        .vale-container {
            width: 100%;
            border: 1px solid #000;
            border-radius: 5px;
            padding: 10px;
        }

        .logo {
            width: 180px;
            height: 38px;
        }

        .titulo {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .folio-box {

            font-size: 16px;
            font-weight: bold;
            color: red;

        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: middle;
        }
       
    </style>
</head>

<body>

    <table class="vale-container" >
        <tr>
            <!-- Logo -->
            <td style="width: 200px;">
                <img src="<?= base_url('public/images/logo_Walworth.png'); ?>" class="logo" alt="Walworth Logo">
            </td>

            <!-- Título -->
            <td class="titulo">
                VALE DE SALIDA DE MATERIAS PRIMAS
            </td>

            <!-- Folio -->
            <td style="width: 150px;" class="folio-box">

                <span>FOLIO: <strong>0701</strong></span>

            </td>
        </tr>
    </table>




    <table>
        <thead>
            <tr>
                <th style="font-size: 8.5px;">CANT.</th>
                <th style="font-size: 8.5px;">CÓDIGO</th>
                <th style="font-size: 8.5px;">DESCRIPCIÓN</th>
                <th style="font-size: 8.5px;">NOMBRE DEL TRABAJADOR</th>
                <th style="font-size: 8.5px;">NO. DE EMPLEADO</th>
                <th style="font-size: 8.5px;">ÁREA</th>
                <th style="font-size: 8.5px;">UBICACIÓN</th>
                <th style="font-size: 8.5px;">FIRMA</th>
                <th style="font-size: 8.5px;">FECHA</th>
            </tr>        </thead>
        <tbody>
            <?php
            $contador = 0; // Inicializamos un contador

            foreach ($tabla1 as $registro):
                // Alternamos el color de fondo basado en el contador
                $colorFondo = ($contador % 2 == 0) ? '' : 'background-color:#d3d2d9;';
            ?>
                <tr style="<?= $colorFondo ?>">
                    <td style="font-size: 9px; padding:2px; overflow: hidden; height: 1px;"><?= $registro['cantidad'] ?></td>
                    <td style="font-size: 9px; padding:2px; overflow: hidden; height: 1px;"><?= $registro['codigo'] ?></td>
                    <td style="font-size: 8px; padding:2px; overflow: hidden; height: 1px; width:25%"><?= strtolower($registro['descripcion']);  ?></td>
                    <td style="font-size: 8px; padding:2px; overflow: hidden; height: 1px; width:18%"><?= strtolower($registro['nombre_trabajador']); ?></td>
                    <td style="font-size: 9px; padding:2px; overflow: hidden; height: 1px;"><?= $registro['no_empleado'] ?></td>
                    <td style="font-size: 9px; padding:2px; overflow: hidden; height: 1px;"><?= $registro['area'] ?></td>
                    <td style="font-size: 9px; padding:2px; overflow: hidden; height: 1px;"><?= $registro['ubicacion'] ?></td>
                    <td style="font-size: 9px; padding:2px; overflow: hidden; height: 1px;"><?= $registro['firma'] ?></td>
                    <td style="font-size: 9px; padding:2px; overflow: hidden; height: 1px;"><?= $registro['fecha'] ?></td>
                </tr>
            <?php
                $contador++;
            endforeach;
            ?>
        </tbody>
    </table>

    <div class="footer" style="text-align: right;font-size:10px;">
        FAL-02 Rev. A
    </div>
    <!--  <table>
            <thead>
                <tr>
                    <th>CANT.</th>
                    <th>CÓDIGO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>NOMBRE DEL TRABAJADOR</th>
                    <th>NO. DE EMPLEADO</th>
                    <th>ÁREA</th>
                    <th>UBICACIÓN</th>
                    <th>FIRMA</th>
                    <th>FECHA</th>
                </tr>
            </thead>
            <tbody>
                <?php // foreach ($tabla2 as $registro): 
                ?>
                <tr>
                    <td><?php // $registro['cantidad'] 
                        ?></td>
                    <td><?php // $registro['codigo'] 
                        ?></td>
                    <td><?php // $registro['descripcion'] 
                        ?></td>
                    <td><?php // $registro['nombre_trabajador'] 
                        ?></td>
                    <td><?php // $registro['no_empleado'] 
                        ?></td>
                    <td><?php // $registro['area'] 
                        ?></td>
                    <td><?php // $registro['ubicacion'] 
                        ?></td>
                    <td><?php // $registro['firma'] 
                        ?></td>
                    <td><?php // $registro['fecha'] 
                        ?></td>
                </tr>
                <?php //endforeach; 
                ?>
            </tbody>
        </table> -->
    <!-- <div class="footer">
            FAL-02 Rev. A
        </div> -->


</body>

</html>