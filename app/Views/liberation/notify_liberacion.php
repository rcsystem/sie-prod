<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitud de Liberación – Creada</title>
  <meta name="color-scheme" content="light only">
  <meta name="supported-color-schemes" content="light only">
  <style>
    @media screen and (max-width: 620px) {
      .container { width:100% !important; }
      .p-24 { padding:16px !important; }
      .text-center-sm { text-align:center !important; }
      .sm-block { display:block !important; width:100% !important; }
    }
  </style>
</head>
<body style="margin:0;padding:0;background:#f4f6f8;-webkit-font-smoothing:antialiased;font-family:Arial,Helvetica,sans-serif;">
  <!-- Preheader oculto -->
  <div style="display:none;max-height:0;overflow:hidden;opacity:0;">
    Se ha solicitado la liberación del usuario <?= htmlspecialchars($solicitud->name.' '.$solicitud->surname.' '.$solicitud->second_surname) ?>.
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f8;">
    <tr>
      <td align="center" style="padding:24px;">
        <table role="presentation" class="container" width="600" cellpadding="0" cellspacing="0" style="width:600px;max-width:600px;background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e6e9ee;">
          <!-- Header -->
          <tr>
            <td style="background:#0f62fe;padding:16px 24px;">
              <table width="100%" role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="text-center-sm" style="color:#ffffff;font-size:18px;font-weight:bold;">
                    <span style="vertical-align:middle;">Solicitud de Liberación</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Título -->
          <tr>
            <td class="p-24" style="padding:24px;">
              <h1 style="margin:0 0 8px 0;font-size:20px;color:#111827;">Solicitud creada</h1>
              <p style="margin:0;color:#4b5563;font-size:14px;line-height:1.6;">
                Se ha registrado una nueva solicitud de liberación para el empleado indicado. Los departamentos comenzarán el proceso de validación y firma.
              </p>
            </td>
          </tr>

          <!-- Resumen del empleado -->
          <?php
            $status = strtoupper($solicitud->request_status ?? '');
            $badgeBg = '#6b7280'; $badgeColor = '#ffffff';
            if ($status === 'FIRMADO') { $badgeBg = '#16a34a'; $badgeColor = '#ffffff'; }
            elseif ($status === 'PENDIENTE') { $badgeBg = '#f59e0b'; $badgeColor = '#111827'; }
            elseif ($status === 'ADEUDO') { $badgeBg = '#dc2626'; $badgeColor = '#ffffff'; }
            elseif ($status === 'EN PROGRESO') { $badgeBg = '#df0000'; $badgeColor = '#ffffff'; }
          ?>
          <tr>
            <td class="p-24" style="padding:0 24px 24px 24px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border:1px solid #e6e9ee;border-radius:6px;overflow:hidden;">
                <tr>
                  <th align="left" style="background:#f9fafb;padding:12px 16px;font-size:12px;color:#6b7280;border-bottom:1px solid #e6e9ee;">Nombre</th>
                  <th align="left" style="background:#f9fafb;padding:12px 16px;font-size:12px;color:#6b7280;border-bottom:1px solid #e6e9ee;">Email</th>
                  <th align="left" style="background:#f9fafb;padding:12px 16px;font-size:12px;color:#6b7280;border-bottom:1px solid #e6e9ee;">Nómina</th>
                  <th align="left" style="background:#f9fafb;padding:12px 16px;font-size:12px;color:#6b7280;border-bottom:1px solid #e6e9ee;">Estado</th>
                </tr>
                <tr>
                  <td style="padding:12px 16px;font-size:14px;color:#111827;"><?= htmlspecialchars($solicitud->name.' '.$solicitud->surname.' '.$solicitud->second_surname) ?></td>
                  <td style="padding:12px 16px;font-size:14px;color:#111827;"><?= htmlspecialchars($solicitud->user_email) ?></td>
                  <td style="padding:12px 16px;font-size:14px;color:#111827;"><?= htmlspecialchars($solicitud->payroll_number) ?></td>
                  <td style="padding:12px 16px;font-size:14px;">
                    <span style="display:inline-block;padding:4px 10px;border-radius:999px;background:<?= $badgeBg ?>;color:<?= $badgeColor ?>;font-weight:bold;font-size:12px;letter-spacing:.2px;">
                      <?= htmlspecialchars($status) ?>
                    </span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Jefe directo -->
          <tr>
            <td class="p-24" style="padding:0 24px 24px 24px;">
              <h2 style="margin:0 0 12px 0;font-size:16px;color:#111827;">Jefe directo</h2>
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border:1px solid #e6e9ee;border-radius:6px;overflow:hidden;">
                <tr>
                  <th align="left" style="background:#f9fafb;padding:12px 16px;font-size:12px;color:#6b7280;border-bottom:1px solid #e6e9ee;">Nombre</th>
                  <th align="left" style="background:#f9fafb;padding:12px 16px;font-size:12px;color:#6b7280;border-bottom:1px solid #e6e9ee;">Email</th>
                </tr>
                <tr>
                  <td style="padding:12px 16px;font-size:14px;color:#111827;"><?= $jefeDirecto ? htmlspecialchars($jefeDirecto->name.' '.$jefeDirecto->surname) : '-' ?></td>
                  <td style="padding:12px 16px;font-size:14px;color:#111827;"><?= $jefeDirecto ? htmlspecialchars($jefeDirecto->email) : '-' ?></td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Mensaje y CTA -->
          <tr>
            <td class="p-24" style="padding:0 24px 24px 24px;">
              <p style="margin:0 0 16px 0;color:#4b5563;font-size:14px;line-height:1.7;">
                Los diferentes departamentos darán seguimiento a la solicitud para liberar el equipo asignado. Puedes revisar el avance en cualquier momento desde el sistema.
              </p>
              <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0;">
                <tr>
                  <td>
                    <a href="<?= base_url('liberacion/solicitudes_departamento') ?>"
                       style="display:inline-block;background:#0f62fe;color:#ffffff;text-decoration:none;border-radius:6px;padding:10px 16px;font-size:14px;font-weight:bold;">
                      Ver solicitud en el sistema
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:16px 24px;background:#f9fafb;color:#6b7280;font-size:12px;text-align:center;">
              <div style="margin-bottom:4px;">Creado por Walworth IT</div>
              <div style="font-size:11px;line-height:1.6;">
                Este mensaje es informativo. No respondas a este correo.
              </div>
            </td>
          </tr>
        </table>

        <table role="presentation" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px;margin-top:8px;">
          <tr>
            <td style="text-align:center;color:#9ca3af;font-size:11px;padding:8px 4px;">
              © <?= date('Y') ?> Walworth TI. Todos los derechos reservados.
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
</body>
</html>
