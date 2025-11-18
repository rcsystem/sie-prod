<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Helvetica'
        }

        .tab1 {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: none;
        }

        .tab1 tr td {
            padding: 4px;
            font-size: 16px;
        }

        .tab1 span {
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php if ($tipo == 1) { ?>
        <table class="tab1">
            <tbody>
                <tr>
                    <td style="width: 100%;text-align:center;">
                        <img src="https://sie.grupowalworth.com/public/images/WW-180Logo.png" style="width: 30% !important;">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p style="margin-left:15px;font-family: Century Gothic;margin-top:-15px;">Sé ha generado un contrato de planta para el usuario:</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <ul style="margin-top:-15px;margin-bottom:-15px;">
                            <li style="padding: 2px;">Fin de Contrato Temporal: <b><?= $contracTempData->last_contract; ?></b></li>
                            <li style="padding: 2px;">Nombre: <b><?= $usuario->user_name ?></b></li>
                            <li style="padding: 2px;">Número de Nómina: <b><?= $usuario->payroll_number; ?></b></li>
                            <li style="padding: 2px;">Departamento: <b><?= $usuario->depto; ?></b></li>
                            <li style="padding: 2px;">Puesto: <b><?= $usuario->job_position; ?></b></li>
                            <?php if ($usuario->observations != null && !empty($usuario->observations)) { ?>
                                <li style="padding: 2px;">Observaciones: </li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                <?php if ($usuario->observations != null && !empty($usuario->observations)) { ?>
                    <tr>
                        <td>
                            <p style="margin-left:15px;font-family: Century Gothic;margin-top:-15px;"><b><?= $usuario->observations ?>.</b></p>
                        </td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="text-align: star;">
                        <a style="
                        background-color: #11344b;
                        border: solid 1px #11344b;
                        border-radius: 5px;
                        box-sizing: border-box;
                        color: #fff;
                        cursor: pointer;
                        display: inline-block;
                        font-size: 14px;
                        font-weight: bold;
                        margin: 0;
                        padding: 10px 19px;
                        margin-top:-15px;
                        text-decoration: none;
                        text-transform: capitalize;" href="https://sie.grupowalworth.com/usuarios/autorizar-planta" target="_blank">Ir</a>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php } else { ?>
        <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10" border="0">
            <tbody>
                <tr style="background:#eee">
                    <td colspan="2" style="font-weight:bold">Información del Contrato</td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Nombre Empleado</td>
                    <td><?= $usuario->user_name?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Numero de Empleado</td>
                    <td><?= $usuario->payroll_number; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Departamento</td>
                    <td><?= $usuario->depto; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Puesto</td>
                    <td><?= $usuario->job_position; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px">Nombre de Jefe</td>
                    <td><?= $usuario->manager_name; ?></td>
                </tr>
                <tr style="<?= $colorBg = ($usuario->direct_authorization == 1) ? 'background:#28A745' : 'background:#DC3545'; ?>">
                    <td colspan="2" style="font-weight:bold;width:180px;text-align:center;color:#FBFBFB"><?= $text = ($usuario->direct_authorization == 1) ? 'AUTORIZADO' : 'RECHAZADO'; ?></td>
                </tr>
                <tr style="background:#eee">
                    <td colspan="2" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
                </tr>
            </tbody>
        </table>
    <?php } ?>

</body>

</html>