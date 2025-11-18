<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<?php
if($datas["status"] == 2){ $estado = "Autorizada"; }else{  $estado = "Rechazada"; }
?>
<body>
    <p>Su solicitud con folio <?= $datas["id_request"]; ?> ha sido <?=$estado?>.<br><?= $datas["coment"]?></p>
</body>

</html>