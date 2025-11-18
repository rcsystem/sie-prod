        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f4f6f8;
                color: #333;
                margin: 0;
                padding: 2rem;
            }

            .header {
                width: 100%;
                display: flex;
                align-items: center;
                padding-bottom: 15px;
                border-bottom: 2px solid #ccc;
                margin-bottom: 25px;
            }

            .header .img img {
                width: 240px;
                height: auto;
                margin-right: auto;
            }

            .header .info {
                font-size: 12px;
                text-align: right;
                margin-right: 10px;
            }

            .title {
                width: 100%;
                text-align: center;
                margin: 40px 0;
                background-color: #D61F2A;
                color: white;
                padding: 10px;
                font-size: 18px;
                font-weight: bold;
            }

            .title-tab {
                /* background-color: #bdc3c7; */
                font-weight: bold;
                padding: 10px;
                text-align: right;
            }

            .celd-tab {
                font-size: 12px;
                padding: 8px;
            }

            .observaciones {
                font-size: 12px;
                margin: 0 10px;
            }

            .footer {
                text-align: right;
                font-size: 10px;
                color: #777;
            }

            .tab1,
            .tab2,
            .tab3,
            .tab4 {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .tab1 td,
            .tab2 td,
            .tab3 td,
            .tab4 td {
                padding: 10px;
                font-size: 12px;
                border: 1px solid #ccc;
            }

            .tab1 span,
            .tab2 span {
                font-weight: bold;
            }

            .label {
                width: 30%;
            }


            .value {
                width: 70%;
            }

            .label2 {
                width: 30%;
            }

            .value2 {
                width: 70%;
            }






            .table-pdf {
                width: 100%;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
                margin-bottom: 20px;
                table-layout: fixed;
            }

            .table-pdf td {
                border: 1px solid #000;
                /* Aplica bordes como en la imagen */
                padding: 8px 12px;
                vertical-align: top;
                page-break-inside: avoid;
                word-wrap: break-word;
                /* Corta palabras largas */
                white-space: pre-wrap;
                /* Mantiene saltos de línea y permite que el texto baje */
                overflow-wrap: break-word;
                /* Cubre navegadores modernos */
            }

            /* Primera columna (etiquetas) */
            .table-pdf td:first-child {
                font-weight: bold;
                width: 30%;
                color: #000;
                background-color: #f9f9f9;
            }

            /* Segunda columna (contenido) */
            .table-pdf td:nth-child(2) {
                color: #333;
            }

            /* Espacio para firmas */
            .table-pdf tr.firma td {
                padding-top: 40px;
                height: 60px;
            }

            .tabla {
                border-top: none;
                /* Elimina borde superior */
                border-right: none;
                /* Elimina borde derecho */
            }

            .tablas {
                border-top: none;
                /* Elimina borde superior */
                border-left: none;
                /* Elimina borde izquierdo */
            }
            .observaciones-cell {
        max-width: 200px; /* Ajusta según necesidad */
        white-space: pre-wrap; /* Mantiene saltos de línea */
    }
        </style>



        <page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
            <page_header>
                <div class="header">
                    <div class="norma">
                        <!--  <label for=""><i>FAT-01-4.3.2</i></label> -->
                    </div>
                    <div class="img">
                        <img src="<?= base_url(); ?>/public/images/logo_Walworth.png" alt="Grupo Walworth">
                        <label style="font-size: 10px;margin-left:310px; color: #565455;"><b><i>INDUSTRIAL DE VALVULAS, S.A. DE C.V.</i></b></label><br>
                        <label style="font-size: 10px;margin-left:300px; color: #565455;">Industria Lote 16 S/N, Fracc. Ind, El Trebol de Tepotzotlan, Tepotzotlán, Estado de México C.P. 54610</label>
                        <!--  <span>Fecha de creación: <label for="" style=""><?= date("d/m/Y", strtotime($request->created_at)); ?></label></span><br>
                        <span style="margin-left:320px">Hora de creación: <label for="" style=" margin-left:140px"><?= date('H:i', strtotime($request->created_at)); ?></label></span> -->
                    </div>
                </div>

            </page_header>
            <page_footer>
            </page_footer>
            <div style="margin-top: 40px;margin-bottom: 25px;">
                <h4>Formato de Registro de Mantenimiento.</h4>
            </div>
            <table class="table-pdf">
                <tr>
                    <td class="tablas" style="width: 30%;"><b>Fecha de Mantenimiento:</b></td>
                    <td class="tabla" style="width: 70%;"><?= $request->fecha_mantto ?? '' ?></td>
                </tr>
                <tr>
                    <td class="tablas"><b>Número de Inventario:</b></td>
                    <td class="tabla"><?= $request->label_equip ?? '' ?></td>
                </tr>
                <tr>
                    <td class="tablas"><b>Tipo de Mantenimiento:</b></td>
                    <td class="tabla"><?= ucfirst($request->status_mantto2) ?? '' ?></td>
                </tr>
                <tr>
                    <td class="tablas"><b>Nombre del Técnico:</b></td>
                    <td class="tabla"><?= ucfirst($request->nombre_tecnico) ?? '' ?></td>
                </tr>
                <tr>
                    <td class="tablas"><b>Usuario Responsable:</b></td>
                    <td class="tabla"><?= ucwords($request->pc_user) ?? '' ?></td>
                </tr>
                <tr>
                    <td class="tablas"><b>Área:</b></td>
                    <td class="tabla" style="font-size:11px;"><?= ucfirst($request->departamento) ?? '' ?></td>
                </tr>
                <tr>
                    <td class="tablas"><b>Actividades Realizadas:</b></td>
                    
                    <td class="tabla observaciones-cell"><?= nl2br( $request->mantto_obsv) ?? '' ?></td>
                </tr>
                <tr>
                    <td class="tablas"><b>Observaciones:</b></td>
                    <td class="tabla observaciones-cell"></td>
                </tr>
                <tr class="firma">
                    <td class="tablas"><b>Firma del Usuario / Acuse:</b></td>
                    <td class="tabla">Confirmación de que recibió el equipo</td>
                </tr>
                <tr class="firma">
                    <td class="tablas"><b>Firma del Técnico:</b></td>
                    <td class="tabla">Confirmación de quien ejecutó el trabajo</td>
                </tr>
            </table>

        </page>
<!-- 
        <td class="tabla"> <span><?php

                                                /* switch ($request->status_mantto2) {
                                                    case 'Preventivo':
                                                        $tareas = "1. Limpieza física (ventiladores, puertos, teclado, pantalla, carcasa).<br>
                                2. Verificación de cables, cargadores y periféricos.<br>
                                3. Comprobación del correcto encendido y apagado.<br>
                                4. Aplicación de actualizaciones del sistema operativo y controladores.<br>
                                5. Limpieza de archivos temporales y desfragmentación.<br>
                                6. Escanéo completo con antivirus institucional.<br>
                                7. Revisión de licencias, uso de recursos y rendimiento general.";
                                                        break;

                                                    case 'Correctivo':
                                                        $tareas = "1. Limpieza física (ventiladores, puertos, teclado, pantalla, carcasa).<br>
                                2. Verificación de cables, cargadores y periféricos.<br>
                                3. Comprobación del correcto encendido y apagado.<br>
                                4. Aplicación de actualizaciones del sistema operativo y controladores.<br>
                                5. Limpieza de archivos temporales y desfragmentación.<br>
                                6. Escanéo completo con antivirus institucional.<br>
                                7. Revisión de licencias, uso de recursos y rendimiento general.";
                                                        break;
                                                    case 'Correctivo que remplaza a Preventivo':
                                                        $tareas = "1. Limpieza física (ventiladores, puertos, teclado, pantalla, carcasa).<br>
                                2. Verificación de cables, cargadores y periféricos.<br>
                                3. Comprobación del correcto encendido y apagado.<br>
                                4. Aplicación de actualizaciones del sistema operativo y controladores.<br>
                                5. Limpieza de archivos temporales y desfragmentación.<br>
                                6. Escanéo completo con antivirus institucional.<br>
                                7. Revisión de licencias, uso de recursos y rendimiento general.";
                                                        break;
                                                    default:
                                                        $tareas = "No se especificaron actividades realizadas.";
                                                        break;
                                                }
                                                echo $tareas; */
                                                ?>
                        </span>
                    </td> -->