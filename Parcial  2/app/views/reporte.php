<?php
// Asegurar que las variables estén definidas
if (!isset($registros)) {
    $registros = [];
}
if (!isset($estadisticas)) {
    $estadisticas = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Participantes - iTECH 2025</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📊 Reporte de Participantes</h1>
            <p class="subtitle">Conferencia Tecnológica iTECH 2025</p>
        </div>

        <?php if (!empty($estadisticas)): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number"><?= $estadisticas['total_participantes'] ?? 0 ?></div>
                    <div class="stat-label">Total Participantes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $estadisticas['total_paises'] ?? 0 ?></div>
                    <div class="stat-label">Países Representados</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= round($estadisticas['edad_promedio'] ?? 0, 1) ?></div>
                    <div class="stat-label">Edad Promedio</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $estadisticas['total_femenino'] ?? 0 ?> / <?= $estadisticas['total_masculino'] ?? 0 ?></div>
                    <div class="stat-label">Femenino / Masculino</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= $estadisticas['con_experiencia'] ?? 0 ?></div>
                    <div class="stat-label">Con Experiencia Previa</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?= round($estadisticas['promedio_experiencia'] ?? 0, 1) ?></div>
                    <div class="stat-label">Promedio Años Experiencia</div>
                </div>
            </div>
        <?php endif; ?>

        <!-- ... -->

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Edad</th>
                        <th>Género</th>
                        <th>País</th>
                        <th>Continente</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Temas</th>
                        <th>Experiencia</th>
                        <th>Estado</th>
                        <th>Integridad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($registros)): ?>
                        <tr>
                            <td colspan="13" class="empty-message">No hay participantes registrados.</td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $participante = new Participante();
                        foreach ($registros as $row):
                            $esValido = $participante->verificarIntegridad($row);

                            // Verificar cada campo individualmente
                            $camposValidos = true;
                            if (!Validation::documento($row['documento_identidad'] ?? '')) $camposValidos = false;
                            if (!Validation::requerido($row['primer_nombre'] ?? '')) $camposValidos = false;
                            if (!Validation::email($row['correo_electronico'] ?? '')) $camposValidos = false;
                            if (!Validation::telefono($row['telefono_movil'] ?? '')) $camposValidos = false;
                            if (!Validation::genero($row['genero'] ?? '')) $camposValidos = false;

                            $integro = ($esValido && $camposValidos);
                            $badgeClass = $integro ? 'verde' : 'rojo';
                            $badgeText = $integro ? '✅ Válido' : '❌ Inválido';
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_participante'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['documento_identidad'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['nombre_completo'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['edad'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['genero'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['pais_residencia'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['continente'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['correo_electronico'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['telefono_movil'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['temas_interes'] ?? 'Sin temas') ?></td>
                                <td>
                                    <?php if ($row['experiencia_previa'] ?? false): ?>
                                        <span class="badge-experiencia"><?= $row['anos_experiencia'] ?? 0 ?> años</span>
                                    <?php else: ?>
                                        <span class="badge-sin-experiencia">Sin experiencia</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge-estado badge-<?= strtolower(str_replace(' ', '-', $row['estado_participante'] ?? 'activo')) ?>">
                                        <?= htmlspecialchars($row['estado_participante'] ?? 'Activo') ?>
                                    </span>
                                </td>
                                <td><span class="badge badge-<?= $badgeClass ?>"><?= $badgeText ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- ... -->
    </div>
</body>

</html>