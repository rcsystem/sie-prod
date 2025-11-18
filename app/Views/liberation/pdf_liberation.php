<style>
  /* ====== Layout compacto para caber en una sola hoja ====== */
  body {
    font-family: 'Arial', sans-serif;
    color: #333;
    font-size: 10.5px;
    margin: 0;
    padding: 0;
    transform: scale(0.85);
    transform-origin: top left;
  }


  .header {
    width: 100%;
    display: block;
    padding-bottom: 2px;
  }

  .header .img img {
    width: 120px;
    height: auto;
  }

  .title {
    width: 100%;
    text-align: center;
    padding: 2px 0;
    font-size: 12px;
    font-weight: bold;
    color: #000;
  }

  .subtitle {
    width: 100%;
    text-align: center;
    margin: 2px 0 2px 0;
    background-color: #c00000;
    color: #fff;
    padding: 2px;
    font-size: 14px;
    font-weight: bold;

  }

  /* ====== Tablas ====== */
  .tab1,
  .tab2,
  .tab3 {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
    page-break-inside: auto;
    table-layout: fixed;
    page-break-inside: avoid;
  }

  .tab1 td,
  .tab2 td,
  .tab3 td {
    padding: 3px;
    font-size: 10.5px;
    border: 1px solid #ccc;
    vertical-align: middle;
  }

  .thead-gray {
    background-color: #d9d9d9;
    font-weight: bold;
  }

  /* ====== Columna de firma ====== */
  .firma-cell {
    text-align: center;
    vertical-align: middle;
    padding: 2px;
    white-space: normal;
    word-break: break-word;
  }

  .firma-box {
    height: 80px;
    display: block;
    overflow: hidden;
    margin: -25px 100px 2px auto;
  }

  .firma-img {
    max-height: 100%;
    max-width: 100%;
    width: auto;
    height: auto;
    display: inline-block;
  }

  .firma-nombre {
    width: auto;
    font-size: 9px;
    line-height: 1.1;
    margin: -28px 100px 2px -12px;
  }

  /* Datos generales */
  .tab-datos {
    width: 70%;
    border-collapse: collapse;
    margin: 4px 0 6px 0;
    table-layout: fixed;
    page-break-inside: avoid;
  }

  .tab-datos td {
    border: 0 !important;
    padding: 2px 3px;
    font-size: 10.5px;
  }

  .footer {
    text-align: right;
    font-size: 9px;
    color: #777;
  }
</style>

<page backtop="2mm" backbottom="2mm" backleft="2mm" backright="2mm" footer="page">
  <page_footer>
    <div class="footer">FAP-20 Rev B</div>
  </page_footer>

  <!-- Encabezado -->
  <div class="title" style="position: relative; padding:0; margin:0; text-align:center;">
    <img src="./images/logo_Walworth.png"
      alt="Grupo Walworth"
      style="position: absolute; top: 0; left: 0; max-height: 30px;">
    <h5 style="margin:0; padding:0; position: absolute; top: 0;"><?= strtoupper(esc($request->name_company)); ?></h5>
    <h5 style="margin:0; padding:0; position: absolute; top: 10;">HOJA DE LIBERACIÓN</h5>
  </div>

  <!-- Datos generales -->
  <table class="tab-datos">
    <colgroup>
      <col style="width:50%;">
      <col style="width:50%;">
    </colgroup>
    <tbody>
      <tr>
        <td style="font-size: 9px;">
          <span>Fecha: <?= date('d/m/Y', strtotime($request->date)); ?></span>
        </td>
        <td style="font-size: 9px;"><span>Núm. Empleado: <?= esc($request->payroll_number); ?></span></td>
        <td style="font-size: 9px;"><span>Solicitante: <?= esc($request->employee_full_name); ?></span></td>

      </tr>
      <tr>
        <td style="font-size: 9px;"><span>Núm. Telefónico: <?= esc($request->phone_number); ?></span></td>
        <td style="font-size: 9px;"><span>Departamento: <?= esc($request->employee_department); ?></span></td>
        <td style="font-size: 9px;"><span>Jefe Inmediato: <?= esc($request->manager_full_name); ?></span></td>
      </tr>
    </tbody>
  </table>

  <?php
  // ====== Catálogo local de firmas autorizadas (id_user => nombre/firma) ======
  $firmasUsuarios = [
    // ALMACEN
    328   => ['nombre' => 'German Velazquez', 'firma' => './images/firmas_users/328/328.png'],
    272   => ['nombre' => 'Abraham Sernas',   'firma' => './images/firmas_users/272/272.png'],
    // HSE
    75   => ['nombre' => 'Luis Dominguez',    'firma' => './images/firmas_users/75/75.png'],
    // Soporte técnico e infraestructura
    1390 => ['nombre' => 'Alan Landa',        'firma' => './images/firmas_users/1390/1390.png'],
    356  => ['nombre' => 'Guillermo Garcia',  'firma' => './images/firmas_users/356/356.png'],
    1    => ['nombre' => 'Alan Landa',        'firma' => './images/firmas_users/1390/1390.png'],
    // Contabilidad y Servicios generales
    294  => ['nombre' => 'David Prado',       'firma' => './images/firmas_users/294/294.png'],
    1283 => ['nombre' => 'Gerardo Mendoza',   'firma' => './images/firmas_users/1283/1283.png'],
    // Mercadotecnia
    44   => ['nombre' => 'Hector Garcia',     'firma' => './images/firmas_users/44/44.png'],
    152  => ['nombre' => 'David Navarrete',   'firma' => './images/firmas_users/152/152.png'],
    // Metrología
    42   => ['nombre' => 'Luis Serrano',      'firma' => './images/firmas_users/42/42.png'],
    259  => ['nombre' => 'Sergio Tlatelpa',      'firma' => './images/firmas_users/259/259.png'],
    // Caja de ahorros Sindicalizados
    455  => ['nombre' => 'Eduardo Florida',   'firma' => './images/firmas_users/455/455.png'],
    // Caja de ahorros No Sindicalizados
    267  => ['nombre' => 'Elda Olanda',       'firma' => './images/firmas_users/267/267.png'],
    50   => ['nombre' => 'Guadalupe Martinez', 'firma' => './images/firmas_users/50/50.png'],
    265  => ['nombre' => 'Alejandra Enriquez', 'firma' => './images/firmas_users/62779/62779.png'],
    // Gestión de talento
    27   => ['nombre' => 'Karen Rubio',       'firma' => './images/firmas_users/27/27.png'],
    903  => ['nombre' => 'Berenice Martine',  'firma' => './images/firmas_users/903/903.png'],
    // Vigilancia
    710  => ['nombre' => 'Vigilancia',        'firma' => ''],
    // Servicio Médico
    1292 => ['nombre' => 'Karla Viridiana',   'firma' => './images/firmas_users/1292/1292.png'],
    // Jefes de área varios
    262 => ['nombre' => 'Anibal Molina',   'firma' => './images/firmas_users/262/262.png'],
    151 => ['nombre' => 'Monserrat Sanchez',   'firma' => './images/firmas_users/151/151.png'],

  ];

  // Control para no repetir imagen de firma en múltiples apartados
  $firmasMostradas = []; // keyed por id de usuario (signed_by)
  $firstHeader = true;
  $JEFE_AREA = 'Jefe de área';
  ?>

  <?php foreach ($departments as $deptName => $deptData): ?>
    <?php
    $items      = $deptData['items'];
    $signedById = $deptData['signed_by'];

    // ¿Hay al menos un ítem con check o con adeudo?
    $hasAny = false;
    foreach ($items as $it) {
      $entregado = !empty($it['entregado']);
      $adeudo    = isset($it['adeudo']) ? (float)$it['adeudo'] : 0.0;
      if ($entregado || $adeudo > 0) {
        $hasAny = true;
        break;
      }
    }

    $signedName  = 'No';
    $signedFirma = null;

    if ($hasAny && $signedById && isset($firmasUsuarios[$signedById])) {
      $signedName  = $firmasUsuarios[$signedById]['nombre'];
      $signedFirma = $firmasUsuarios[$signedById]['firma'];
    } elseif ($hasAny) {
      $signedName = 'Firmado (no autorizado)';
    } else {
      $signedName = 'No';
    }

    // imagen solo primera vez
    $mostrarImagenFirma = ($hasAny && $signedFirma);
    if ($hasAny && $signedFirma) {
      if (empty($firmasMostradas[$signedById])) {
        $mostrarImagenFirma = true;
        $firmasMostradas[$signedById] = true;
      }
    }
    ?>


    <div class="subtitle" style="margin:0; padding:0;">
      <span style="text-align:left; font-size:11px"><?= esc(mb_convert_case($deptName, MB_CASE_UPPER, 'UTF-8')); ?></span>
    </div>

    <table class="tab1" style="margin:0; padding:0;">
      <colgroup>
        <!-- 4 columnas: Descripción | Entregado Sí/No | Adeudo ($) | Firma -->
        <col style="width:38%;">
        <col style="width:18%;">
        <col style="width:14%;">
        <col style="width:30%;">
      </colgroup>
      <tbody>
        <?php if ($firstHeader): ?>
          <tr class="thead-gray" style="font-size:9px">
            <td style="font-size:9px"><span>Descripción</span></td>
            <td style="text-align:center;font-size:9px"><span>Entregado Sí/No</span></td>
            <td style="text-align:center;font-size:9px"><span>Adeudo ($)</span></td>
            <td style="text-align:center;font-size:9px"><span>Firma de liberación</span></td>
          </tr>
          <?php $firstHeader = false; ?>
        <?php endif; ?>

        <?php
        // Total de adeudo del departamento
        $totalAdeudoDept = 0.0;
        foreach ($items as $it) {
          $totalAdeudoDept += isset($it['adeudo']) ? (float)$it['adeudo'] : 0;
        }

        // +1 para que la columna de firma abarque el renglón de TOTAL
        $rowCount = count($items) + 1;
        ?>

        <?php foreach ($items as $index => $item): ?>
          <?php
          // valores que vienen del controller
          $entregado = !empty($item['entregado']);
          $adeudo    = isset($item['adeudo']) ? (float)$item['adeudo'] : 0.0;
          ?>
          <tr>
            <td style="margin:0; padding:0; font-size:9px">
              <span><?= esc($item['name']); ?></span>
            </td>

            <td style="margin:0; padding:0; text-align:center; font-size:9px">
              <span><?= $entregado ? 'Sí' : 'No'; ?></span>
            </td>

            <td style="margin:0; padding:0; text-align:center; font-size:9px">
              <?php if ($adeudo > 0): ?>
                <span><strong>$<?= number_format($adeudo, 2) ?></strong></span>
              <?php else: ?>
                <span>-</span>
              <?php endif; ?>
            </td>

            <?php if ($index === 0): ?>
              <!-- Columna de firma con rowspan que incluye el renglón TOTAL -->
              <td class="firma-cell" rowspan="<?= $rowCount ?>">
                <div class="firma-box">
                  <?php if ($mostrarImagenFirma): ?>
                    <img src="<?= esc($signedFirma); ?>" alt="Firma" class="firma-img">
                    <?php if (strtoupper($signedName) == 'VIGILANCIA'): ?>
                      <p><strong>Vigilancia</strong></p>
                    <?php endif; ?>
                  <?php endif; ?>
                </div>
                <div class="firma-nombre">
                  <?= esc($signedName); ?>
                </div>
              </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>


        <!-- Renglón TOTAL DE ADEUDO del departamento -->
        <tr>
          <td style="margin:0; padding:2px 2px; font-size:9px; text-align:right;" colspan="2">
            <strong>TOTAL ADEUDO</strong>
          </td>
          <td style="margin:0; padding:2px 2px; font-size:9px; text-align:center;">
            <strong>
              $<?= number_format($totalAdeudoDept, 2) ?>
            </strong>
          </td>
          <!-- La 4ª columna (Firma) ya está cubierta por el rowspan del primer renglón -->
        </tr>
      </tbody>
    </table>
  <?php endforeach; ?>
</page>

<?php if ($hayComentarios): ?>

<page backtop="2mm" backbottom="2mm" backleft="2mm" backright="2mm" footer="page">
  <page_footer>
    <div class="footer">FAP-20 Rev B</div>
  </page_footer>

  <!-- Encabezado segunda hoja -->
  <div class="title" style="position: relative; padding:0; margin:0; text-align:center;">
    <img src="./images/logo_Walworth.png"
      alt="Grupo Walworth"
      style="position: absolute; top: 0; left: 0; max-height: 30px;">
    <h5 style="margin:0; padding:0; position: absolute; top: 0;"><?= strtoupper(esc($request->name_company)); ?></h5>
    <h5 style="margin:0; padding:0; position: absolute; top: 10;">COMENTARIOS</h5>
  </div>

  <style>
    .bloque-comentario { border:1px solid #ccc; border-radius:4px; padding:6px; margin:6px 0; }
    .bloque-comentario h4 { font-size: 11px; margin:0 0 4px 0; }
    .comentario-html { font-size: 10.5px; }
    .meta { font-size: 9px; color:#666; text-align:right; margin-top:4px; }
  </style>

  <?php
  // Orden igual que la primera hoja
  $ordenParaComentarios = array_keys($departments);

  // Bandera para saber si imprimimos algo
  $imprimio = false;

  foreach ($ordenParaComentarios as $deptName):
      if (empty($comentarios[$deptName])) { continue; }

      // Puede venir como 1 registro (array asociativo) o como lista de registros
      $lista = $comentarios[$deptName];

      // Normaliza a lista
      if (isset($lista['comentario_html'])) {
          $lista = [ $lista ];
      }

      foreach ($lista as $c):
          if (empty($c['comentario_html'])) { continue; }
          $imprimio = true;
  ?>
    <div class="bloque-comentario">
      <h4><?= esc(mb_convert_case($deptName, MB_CASE_TITLE, 'UTF-8')); ?></h4>

      <!-- OJO: este HTML ya debe venir purificado; no uses esc() aquí -->
      <div class="comentario-html"><?= $c['comentario_html']; ?></div>

      <?php if (!empty($c['created_at'])): ?>
        <div class="meta">Fecha: <?= date('d/m/Y H:i', strtotime($c['created_at'])); ?></div>
      <?php endif; ?>
    </div>
  <?php
      endforeach;
  endforeach;

  if (!$imprimio):
  ?>
    <div class="bloque-comentario">
      <h4>Comentarios</h4>
      <div class="comentario-html"><em>No hay comentarios registrados para esta solicitud.</em></div>
    </div>
  <?php endif; ?>
</page>
<?php endif; ?>
