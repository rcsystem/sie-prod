<style>
    body {
        font-family: 'Helvetica'
    }


    .tabla-1 {
        display: flex;
        width: 100%;
        border: 3px black solid;
        margin-top: -55px;
        margin-left: -10px;
    }

    img {
        width: 80%;
        height: 70px;
        float: left;
        margin-right: auto;
        margin-left: 7px;
    }

    .td-subtittle {
        font-size: 11px;
        padding-top: 8px;
        padding-bottom: 5px;
        padding-right: 5px;
        background-color: #D4D4D4;
        text-align: right;
        border: 1px solid black;
    }

    .td-tittle {
        background-color: #8D8D8D;
        font-weight: bold;
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

    .td-data-bg {
        padding: 10px 5px 10px 5px;
        padding-bottom: 10px;
        text-align: left;
        border: 1px solid black;
    }

    .td-jump{
        height: 125px;
    }
</style>
<?php
// $sistema = ($request->system == "OTRO") ? $request->other_system : $request->system;
?>
<page backtop='17mm' backbottom='3mm' backleft='13mm' backright='3mm' footer='page'>

    <table class="tabla-1">
        <tr>
            <td colspan="3" style="text-align: center;padding-top:10px; padding-bottom:10px;">
                <img src="./images/GrupoWalworth-Registrada.png" alt="Grupo Walworth">
            </td>
            <td style="text-align: center;border-left: 1px solid black;border-bottom: 1px solid black;">
                <span>Folio:</span><br><b><?= $request->folio; ?></b>
            </td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;">
                <H3>RESPONSIVA DE ENTREGA DE SUMINISTRO</H3>
            </td>
        </tr>
        <tr>
            <td class="td-subtittle">
                RESPONSABLE:
            </td>
            <td class="td-data" colspan="3">
                <?= $request->responsable; ?>
            </td>
        </tr>
        <tr>
            <td class="td-subtittle">
                DEPARTAMENTO:
            </td>
            <td class="td-data" colspan="3">
                <?= $request->departamento; ?>
            </td>
        </tr>
        <tr>
            <td class="td-subtittle">
                EMPRESA:
            </td>
            <td class="td-data" colspan="3">
                <?= $request->empresa; ?>
            </td>
        </tr>
        <tr>
            <td class="td-tittle" colspan="4">
                ESPECIFICACIONES
            </td>
        </tr>
        <tr>
            <td class="td-subtittle">
                PRODUCTO:
            </td>
            <td class="td-data" colspan="3">
                <?= $request->product ?>
            </td>
        </tr>
        <tr>
            <td class="td-subtittle">
                CANTIDAD:
            </td>
            <td class="td-data">
                <?= $request->amount; ?>
            </td>
            <td class="td-subtittle">
                COSTO UNITARIO:
            </td>
            <td class="td-data">
                <?= $request->cost ?? '0.00'; ?>
            </td>
        </tr>
        <tr>
            <td class="td-subtittle">
                VENCIMIENTO:
            </td>
            <td class="td-data">
                <?= $request->vencimiento; ?>
            </td>
            <td class="td-subtittle">
                FECHA DE ENTREGA:
            </td>
            <td class="td-data">
                <?= $request->fecha_entrega; ?>
            </td>
        </tr>
        <tr>
            <td class="td-jump" colspan="4"></td>
        </tr>
        <tr>
            <td class="td-tittle " colspan="4">
                POLITICAS DE USO, NOTAS DE CUIDADOS Y CASOS
            </td>
        </tr>
        <tr>
            <td class="td-data-bg" colspan="4">
                Me comprometo a cuidar y hacer buen uso del equipo asi como conservarlo en buen estado, devolverlo cuando cause baja en la empresa, o me sea solicitado. En caso contrario PAGARE y ACEPTARE el descuento via NOMINA del IMPORTE que corresponda a su reposición, (el costo del equipo se expresa en la lista actual de la marca en su sitio web con base en la devaluación o costo actual de modelos y marcas "pagarlo el costo a la fecha") En caso de daño o ruptura de alguna pieza imputable al uso por trabajo u operación el usuario debera realizar el reporte correspondiente "por escrito" via email o impreso y no tratar de hacer ninguna reparación, de lo contrario el usuario sera responsable de la falla y debera cubrir el costo total de la reparación o sustitución del equipo. Cuando el daño, ruptura o falla de alguna pieza sea por accidente o negligencia; el usuario se compromete a pagar un monto que va del 30% al 70 % del precio por reparación o sustitución del equipo: (Este monto sera designado tomando en cuenta la causa del imprevisto) El usuario se compromete a hacer uso exclusivo del equipo para la acciones de la Empresa, pueden durante su poder descargar aplicaciones que no impidan la correcta operacion sin embargo; "El incumplimiento de esta clausula derivara en las sanciones que la Dirección General considere pertinentes". Al finalizar el periodo el usuario debera devolver el equipo anterior en óptimas condiciones que el tiempo de uso lo amerite, si el equipo se entrega con daño severo o sin alguna pieza o parte; debera pagar una penalización que va del 30 al 70% del costo de la reparación del equipo. (Dicho monto sera designado tomando en cuenta la causa del daño "Accidente o negligencia) NOTA: EN CASO DE ROBO O EXTRAVIO SE DEBE REPORTAR DE INMEDIATO A LAS OFICINAS O EL PERSONAL QUE ADMINISTRA LOS EQUIPOS, ASI COMO LEVANTAR EL ACTA ANTE MINISTERIO PÚBLICO, PRESENCIAL O DIGITAL; http://www.edomex.gob.mx/sis/pgjem/sistema/msai0.asp https://www.pgj.cdmx.gob.mx/nuestros-servicios/en-linea SIN LA DENUNCIA DE ROBO O EXTRAVIO EL USUARIO DEBERA PAGAR EL COSTO DEL EQUIPO, LAS CUOTAS O PAGOS DE DEDUCIBLE SE ANALIZARaN SEGUN EL TIPO DE EVENTO.
            </td>
        </tr>
        <tr>
            <td colspan="2" class="td-data">
                FIRMA:
                <br><br>
                <br><br>
                <br><br>
            </td>
            <td colspan="2" class="td-data">
                FIRMA:
                <br><br>
                <br><br>
                <br><br>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="td-data">
                <b>RECIBE</b><br> <?= $request->responsable ?>
            </td>
            <td colspan="2" class="td-data">
                <b>ENTREGA</b><br> <?= $request->entrega ?>
            </td>
        </tr>

        <tr class="tr-jump">
            <td style="width:35%;"></td>
            <td style=" width:15%;"></td>
            <td style=" width:35%;"></td>
            <td style=" width:15%;"></td>
        </tr>
    </table>
    <page_footer>
    </page_footer>
</page>