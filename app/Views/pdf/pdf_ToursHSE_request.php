<style>
    body {
        font-family: 'Helvetica'
    }

    .norma {
        width: 98.5%;
        text-align: right;
    }

    .header {
        width: 100%;
        display: flex;
        height: 30px;
    }

    .header .img img {
        width: 200px;
        height: 39px;
        float: left;
        margin-right: auto;
        margin-left: 7px;
    }

    .header span {
        width: 300px;
        font-size: 13px;
        font-weight: bold;
        text-align: right;
        float: right;
        margin: 5px 0 0 350px;
    }

    .title {
        width: 100%;
        text-align: center;
        margin: 0 0 30px 0;
        background-color: rgb(218, 26, 34);
        color: white;
        display: flex;
        justify-content: center;
        align-content: center;
    }

    .tabla-1 {
        display: flex;
        width: 98%;
        border: 3px black solid;
        margin-top: 0px;
        margin-left: -10px;
    }

    .evidence {
        width: 350px;
        height: 300px;
        margin-right: auto;
    }

    .td-subtittle {
        font-size: 11px;
        padding-top: 8px;
        padding-bottom: 5px;
        padding-right: 5px;
        background-color: #D9D9D9;
        text-align: right;
        border: 1px solid black;
    }

    .td-subtittle-center {
        font-size: 11px;
        padding-top: 8px;
        padding-bottom: 5px;
        padding-right: 5px;
        background-color: #D4D4D4;
        text-align: center;
        border: 1px solid black;
    }

    .td-tittle {
        background-color: #8D8D8D;
        font-weight: bold;
        color: white;
        font-size: 14px;
        text-align: center;
        border: 1px solid black;
        padding-top: 12px;
        padding-bottom: 12px;
    }

    .td-data {
        font-size: 11px;
        padding-top: 8px;
        padding-bottom: 5px;
        text-align: center;
        border: 1px solid black;
    }

    /* .td-data-bg {
        width: 100%;
        padding: 10px 15px 10px 15px;
        text-align: left;
        vertical-align: top;
        border: 1px solid black;
    } */

    .td-data-md {
        padding: 10px 15px 10px 15px;
        width: 50%;
        text-align: left;
        vertical-align: top;
        border: 1px solid black;
        white-space: normal;
    }
</style>


<page backtop='20mm' backbottom='0mm' backleft='3mm' backright='3mm' footer='page'>
    <page_header>
        <div class="header">
            <div class="norma">
                <!--  <label for=""><i>FAT-01-4.3.2</i></label> -->
            </div>
            <div class="img">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
                <span style="margin-top:25px;margin-left:448px;font-size:16px">Folio:<?= $request->folio; ?></span>
            </div>
        </div>
    </page_header>


    <table class="tabla-1">
        <thead>
            <tr>
                <td class="title" colspan="6">
                    <h3>INCIDENCIA DE <?= $request->tipo; ?></h3>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?PHP if ($request->type == 1) { ?>
                    <td class="td-subtittle">
                        USUARIO:
                    </td>
                    <td class="td-data" colspan="2">
                        <?= $request->usuario; ?>
                    </td>
                    <td class="td-subtittle">
                        DEPARTAMENTO:
                    </td>
                    <td class="td-data" colspan="2">
                        <?= $request->departamento; ?>
                    </td>
                <?PHP } else { ?>
                    <td class="td-subtittle" colspan="2">
                        DEPARTAMENTO:
                    </td>
                    <td class="td-data" colspan="4">
                        <?= $request->departamento; ?>
                    </td>
                <?PHP } ?>
            </tr>
            <tr>
                <td class="td-subtittle" colspan="2">
                    CATEGORIA:
                </td>
                <td class="td-data" colspan="4">
                    <?= $request->categoria; ?>
                </td>
            </tr>
            <tr>
                <td class="td-subtittle">
                    FECHA:
                </td>
                <td class="td-data">
                    <?= $request->fecha_cracion; ?>
                </td>
                <td class="td-subtittle">
                    HORA:
                </td>
                <td class="td-data">
                    <?= $request->hora_cracion; ?>
                </td>
                <td class="td-subtittle">
                    GRAVEDAD DE INCIDENCIA:
                </td>
                <td class="td-data">
                    <?= $request->nivel; ?>
                </td>
            </tr>
            <tr>
                <td class="td-subtittle" colspan="2">
                    REQUIERE CONCIENTIZACIÓN:
                </td>
                <td class="td-data">
                    <?= $request->retro; ?>
                </td>
                <td class="td-subtittle" colspan="2">
                    REQUIERE SEGUIMIENTO:
                </td>
                <td class="td-data">
                    <?= $request->seguimiento; ?>
                </td>
            </tr>
            <tr>
                <td class="td-subtittle-center" colspan="3">
                    EVIDENCIA DE INCIDENCIA
                </td>
                <td class="td-subtittle-center" colspan="3">
                    DESCIPCION DE INCIDENCIA
                </td>
            </tr>
            <tr>
                <td class="td-data" colspan="3">
                    <img src="<?= $imgAlt->url_image; ?>" class="evidence">
                </td>
                <td class="td-data-md" colspan="3">
                    <?= $request->descripcion; ?>
                </td>
            </tr>
            <tr>
                <td class="td-subtittle-center" colspan="2">
                    USUARIO QUE LEVANTO INCIDENCIA
                </td>
                <td class="td-data" colspan="4">
                    <?= $request->alta; ?>
                </td>
            </tr>
            <?php if ($request->requiere_follow == 1) { ?>
                <tr>
                    <td class="td-tittle" colspan="6">
                        DETALLES DE SEGUIMIENTO
                    </td>
                </tr>
                <tr>
                    <td class="td-subtittle-center">
                        ¿SE SOLUCIONO?
                    </td>
                    <td class="td-data">
                        <?= $request->seguimiento_opc; ?>
                    </td>
                    <td class="td-subtittle-center" colspan="">
                        RESPONSABLE
                    </td>
                    <td class="td-data" colspan="3">
                        <?= $request->alta; ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-subtittle-center" colspan="3">
                        EVIDENCIA DE SEGUIMIENTO
                    </td>
                    <td class="td-subtittle-center" colspan="3">
                        DESCIPCION DE SEGUIMIENTO
                    </td>
                </tr>
                <tr>
                    <td class="td-data" colspan="3">
                        <img src="<?= $imgSeg->url_image; ?>" class="evidence">
                    </td>
                    <td class="td-data-md" colspan="3">
                        <?= $request->respsonce_msj; ?>
                    </td>
                </tr>
            <?php } ?>

            <tr class="tr-jump">
                <td style="width:15%;"></td>
                <td style="width:17.5%;"></td>
                <td style="width:17.5%;"></td>

                <td style=" width:15%;"></td>
                <td style="width:17.5%;"></td>
                <td style="width:17.5%;"></td>

            </tr>
        </tbody>
    </table>

    <page_footer>
    </page_footer>
</page>