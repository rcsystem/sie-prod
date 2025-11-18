<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
    <p><span style="color:rgb(37,37,37)">
            <p>Productos prontos a Agotarse</p>
        </span></p>
    <table style="border-radius:2px;margin:2em 0 3em;min-width:400px;border:1px solid #eee;border-collapse:separate;border-spacing:1px" cellspacing="0" cellpadding="10">
        <tbody>
            <?php for ($i = 0; $i < count($producto); $i++) { ?>
                <tr style="background:#fbfbfb">
                    <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Producto</td>
                    <td style="width: auto;"><?= $producto[$i]->product; ?></td>
                    <td style="background-color:#EEEEEE;font-weight:bold;width:auto;">Cantidad</td>
                    <td style="width: auto;"><?= $producto[$i]->amount; ?></td>
                </tr>
            <?php }; ?>
            <tr style="background:#fbfbfb">
                <td colspan="4" style="font-weight:bold;color:black;"><b>Create by Walworth IT</b></td>
            </tr>
        </tbody>
    </table>
</body>

</html>