<?php
// Asegurar que las variables estén definidas
if (!isset($registros)) {
    $registros = [];
}
if (!isset($estadisticas)) {
    $estadisticas = [];
}

// Incluir modelos necesarios para validación
require_once __DIR__ . '/../models/Participante.php';
require_once __DIR__ . '/../helpers/Validation.php';
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

        <div class="actions-bar">
            <a href="index.php" class="btn-info">🏠 Volver al Formulario</a>
            <a href="index.php?action=exportar" class="btn-success">📥 Exportar a Excel</a>
        </div>

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
                            // Verificar integridad del registro
                            $esValido = $participante->verificarIntegridad($row);

                            // Verificar cada campo individualmente (usando los campos disponibles)
                            $camposValidos = true;

                            // Para la vista, verificamos los campos que tenemos disponibles
                            if (isset($row['documento_identidad']) && !Validation::documento($row['documento_identidad'])) {
                                $camposValidos = false;
                            }
                            if (isset($row['correo_electronico']) && !Validation::email($row['correo_electronico'])) {
                                $camposValidos = false;
                            }
                            if (isset($row['telefono_movil']) && !Validation::telefono($row['telefono_movil'])) {
                                $camposValidos = false;
                            }
                            if (isset($row['genero']) && !Validation::genero($row['genero'])) {
                                $camposValidos = false;
                            }

                            $integro = ($esValido && $camposValidos);
                            $badgeClass = $integro ? 'verde' : 'rojo';
                            $badgeText = $integro ? '✅ Válido' : '❌ Inválido';

                            // Determinar experiencia
                            $tieneExperiencia = isset($row['experiencia_previa']) && $row['experiencia_previa'] == 1;
                            $anosExp = isset($row['anos_experiencia']) ? $row['anos_experiencia'] : 0;
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
                                <td>
                                    <?php
                                    $temas = isset($row['temas_interes']) ? $row['temas_interes'] : '';
                                    echo htmlspecialchars($temas ?: 'Sin temas');
                                    ?>
                                </td>
                                <td>
                                    <?php if ($tieneExperiencia): ?>
                                        <span class="badge-experiencia"><?= $anosExp ?> años</span>
                                    <?php else: ?>
                                        <span class="badge-sin-experiencia">Sin experiencia</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                    $estado = isset($row['estado_participante']) ? $row['estado_participante'] : 'Activo';
                                    $estadoClass = strtolower(str_replace(' ', '-', $estado));
                                    ?>
                                    <span class="badge-estado badge-<?= $estadoClass ?>">
                                        <?= htmlspecialchars($estado) ?>
                                    </span>
                                </td>
                                <td><span class="badge badge-<?= $badgeClass ?>"><?= $badgeText ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer-info">
            <p>Total de registros: <?= count($registros) ?></p>
            <p>© 2025 iTECH. All rights reserved. | Versión 2.0</p>
        </div>
    </div>
</body>

</html>