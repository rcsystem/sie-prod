<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php 
$link = site_url() . "estacionamiento/movimientos-de-vehiculos"; 
$nombre = ($type == 2) ? $item->name : $item[0]->name;
$typo_movimiento = ($type == 2) ? $item->type_movem : $item[0]->type_movem;
?>

<body>
    <p><span style="color:rgb(37,37,37)">El Usuario <b><?= $nombre ?></b> a <b><?= $typo_movimiento ?></b></span></p>
    <table style="border-radius:2px;margin:2em 0 1em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
        <tbody>
            <?php if ($type == 2) { ?>
                <tr style="background:#eee">
                    <td colspan="2" style="font-weight:bold;color:black;text-align:center;">INFORMACION DEL VEHÍCULO</b></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">MODELO:</td>
                    <td style="color:black;"><?= $item->model; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">COLOR:</td>
                    <td style="color:black;"><?= $item->color ?></td>
                </tr>
                <?php if (!empty($item->placas)) { ?>
                    <tr style="background:#fbfbfb">
                        <td style="font-weight:bold;width:180px;text-align: right;color:black;">PLACAS:</td>
                        <td style="color:black;"><?= $item->placas ?></td>
                    </tr>
                <?php } ?>
                <?php } else {
                foreach ($item as $key) { ?>
                <tr style="background:#eee">
                    <td colspan="2" style="font-weight:bold;color:black;text-align:center;">INFORMACION DEL VEHÍCULO</b></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">MODELO:</td>
                    <td style="color:black;"><?= $key->model; ?></td>
                </tr>
                <tr style="background:#fbfbfb">
                    <td style="font-weight:bold;width:180px;text-align: right;color:black;">COLOR:</td>
                    <td style="color:black;"><?= $key->color; ?></td>
                </tr>
                <?php if (!empty($key->placas)) { ?>
                    <tr style="background:#fbfbfb">
                        <td style="font-weight:bold;width:180px;text-align: right;color:black;">PLACAS:</td>
                        <td style="color:black;"><?= $key->placas; ?></td>
                    </tr>
                <?php } ?>
            <?php }
            } ?>
        </tbody>
    </table>
    <table style="border:0" cellpadding="0" cellspacing="0">
        <tbody>
            <tr>
                <td>
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
                        text-transform: capitalize;" href="<?= $link; ?>" target="_blank"><i class="far fa-eye"></i> VER MOVIMIENTO</a>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>