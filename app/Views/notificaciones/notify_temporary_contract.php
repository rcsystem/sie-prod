<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php $text = ($tipo == 2) ? $informacion[0]->type_employe  : ""; ?>

<body>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
        <tbody>
            <tr style="background:#5C636A; color: #fff; width: min-content;text-align: center;">
                <td colspan="6" style="font-weight:bold">
                    <p>Contratos Teporales prontos a expirar. <br> DATOS DE CONTRATOS <?= $text; ?></p>
                </td>
            </tr>
            <?php if ($tipo == 1) {
                for ($i = 0; $i < count($informacion); $i++) {
                    $name = $informacion[$i]->name . " " . $informacion[$i]->surname . " " . $informacion[$i]->second_surname;
            ?>
                    <?php if ($i > 0) { ?>
                        <tr style="background:#BDBDBD;width: min-content;">
                            <td colspan="6" style="padding: 0px;"></td>
                        </tr>
                    <?php } ?>
                    <tr style="background:#fbfbfb">
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Nombre</td>
                        <td style="width: auto;"><?= $name; ?></td>
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Puesto</td>
                        <td><?= $informacion[$i]->job; ?></td>
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Fecha de Termino</td>
                        <td><?= $informacion[$i]->date_expiration; ?></td>
                    </tr>
                <?php
                }
            } else if ($tipo == 2) {
                for ($i = 0; $i < count($informacion); $i++) {
                    $name = $informacion[$i]->name . " " . $informacion[$i]->surname . " " . $informacion[$i]->second_surname;
                    $name_m = $informacion[$i]->name_m . " " . $informacion[$i]->surname_m . " " . $informacion[$i]->second_surname_m;
                ?>
                    <tr style="background:#BDBDBD;width: min-content;">
                        <td colspan="6" style="font-weight:bold">Folio de Contrato: <?= $informacion[$i]->id_contract; ?></td>
                    </tr>
                    <tr style="background:#fbfbfb">
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Nombre</td>
                        <td style="width:auto;"><?= $name; ?></td>
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Puesto</td>
                        <td><?= $informacion[$i]->job; ?></td>
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Jefe Directo</td>
                        <td style="width:auto;"><?= $name_m; ?></td>
                    </tr>
                    <tr style="background:#fbfbfb">
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Departamento</td>
                        <td><?= $informacion[$i]->departament; ?></td>
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Tipo de Contrato</td>
                        <td><?= $informacion[$i]->type_of_contract; ?></td>
                        <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Fecha de Termino</td>
                        <td><?= $informacion[$i]->date_expiration; ?></td>
                    </tr>
            <?php
                }
            } ?>
            <tr></tr>
            <tr style="background:#5C636A; color: #fff;">
                <td colspan="6" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>
</body>

</html>