<?php
// Asegurar que las variables estén definidas
if (!isset($ubicaciones)) {
    $ubicaciones = [];
}
if (!isset($categorias)) {
    $categorias = [];
}
if (!isset($message)) {
    $message = null;
}
if (!isset($old)) {
    $old = [];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Participantes - iTECH 2025</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Registro de Participantes</h1>
            <p class="subtitle">Conferencia Tecnológica iTECH 2025</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?= $message['type'] ?? 'info' ?>">
                <?= htmlspecialchars($message['text'] ?? '') ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['errores'])): ?>
            <div class="alert alert-error">
                <h4>⚠️ Por favor corrige los siguientes errores:</h4>
                <ul>
                    <?php foreach ($_SESSION['errores'] as $error): ?>
                        <li>❌ <?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php unset($_SESSION['errores']); ?>
        <?php endif; ?>

        <form action="index.php?action=guardar" method="POST" class="formulario">
            <!-- SECCIÓN 1: DATOS PERSONALES -->
            <div class="form-section">
                <h3>📋 Datos Personales</h3>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="documento_identidad">🆔 Documento de Identidad <span class="required">*</span></label>
                        <input type="text" id="documento_identidad" name="documento_identidad"
                            value="<?= isset($old['documento_identidad']) ? htmlspecialchars($old['documento_identidad']) : '' ?>"
                            placeholder="Ej: 8-123-456" required>
                    </div>

                    <div class="form-group half">
                        <label for="edad">📅 Edad <span class="required">*</span></label>
                        <input type="number" id="edad" name="edad" min="1" max="120"
                            value="<?= isset($old['edad']) ? htmlspecialchars($old['edad']) : '' ?>"
                            placeholder="Ej: 25" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="primer_nombre">👤 Primer Nombre <span class="required">*</span></label>
                        <input type="text" id="primer_nombre" name="primer_nombre"
                            value="<?= isset($old['primer_nombre']) ? htmlspecialchars($old['primer_nombre']) : '' ?>"
                            placeholder="Ej: Juan" required>
                    </div>

                    <div class="form-group half">
                        <label for="segundo_nombre">👤 Segundo Nombre</label>
                        <input type="text" id="segundo_nombre" name="segundo_nombre"
                            value="<?= isset($old['segundo_nombre']) ? htmlspecialchars($old['segundo_nombre']) : '' ?>"
                            placeholder="Ej: Carlos">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="primer_apellido">👤 Primer Apellido <span class="required">*</span></label>
                        <input type="text" id="primer_apellido" name="primer_apellido"
                            value="<?= isset($old['primer_apellido']) ? htmlspecialchars($old['primer_apellido']) : '' ?>"
                            placeholder="Ej: Pérez" required>
                    </div>

                    <div class="form-group half">
                        <label for="segundo_apellido">👤 Segundo Apellido</label>
                        <input type="text" id="segundo_apellido" name="segundo_apellido"
                            value="<?= isset($old['segundo_apellido']) ? htmlspecialchars($old['segundo_apellido']) : '' ?>"
                            placeholder="Ej: García">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="genero">⚤ Género <span class="required">*</span></label>
                        <select id="genero" name="genero" required>
                            <option value="">Seleccione</option>
                            <option value="Masculino" <?= (isset($old['genero']) && $old['genero'] === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
                            <option value="Femenino" <?= (isset($old['genero']) && $old['genero'] === 'Femenino') ? 'selected' : '' ?>>Femenino</option>
                            <option value="No binario" <?= (isset($old['genero']) && $old['genero'] === 'No binario') ? 'selected' : '' ?>>No binario</option>
                            <option value="Prefiero no decirlo" <?= (isset($old['genero']) && $old['genero'] === 'Prefiero no decirlo') ? 'selected' : '' ?>>Prefiero no decirlo</option>
                        </select>
                    </div>

                    <div class="form-group half">
                        <label for="ubicacion_id">🌍 País de Residencia <span class="required">*</span></label>
                        <select id="ubicacion_id" name="ubicacion_id" required>
                            <option value="">Seleccione un país</option>
                            <?php if (!empty($ubicaciones)): ?>
                                <?php foreach ($ubicaciones as $ubicacion): ?>
                                    <option value="<?= $ubicacion['id_ubicacion'] ?>"
                                        <?= (isset($old['ubicacion_id']) && $old['ubicacion_id'] == $ubicacion['id_ubicacion']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($ubicacion['nombre_pais']) ?>
                                        (<?= htmlspecialchars($ubicacion['continente']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>⚠️ No hay países disponibles</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="nacionalidad_oficial">🛂 Nacionalidad <span class="required">*</span></label>
                    <input type="text" id="nacionalidad_oficial" name="nacionalidad_oficial"
                        value="<?= isset($old['nacionalidad_oficial']) ? htmlspecialchars($old['nacionalidad_oficial']) : '' ?>"
                        placeholder="Ej: Panameña" required>
                </div>
            </div>

            <!-- SECCIÓN 2: INFORMACIÓN DE CONTACTO -->
            <div class="form-section">
                <h3>📱 Información de Contacto</h3>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="correo_electronico">📧 Correo Electrónico <span class="required">*</span></label>
                        <input type="email" id="correo_electronico" name="correo_electronico"
                            value="<?= isset($old['correo_electronico']) ? htmlspecialchars($old['correo_electronico']) : '' ?>"
                            placeholder="Ej: juan.perez@email.com" required>
                    </div>

                    <div class="form-group half">
                        <label for="telefono_movil">📱 Teléfono Móvil <span class="required">*</span></label>
                        <input type="text" id="telefono_movil" name="telefono_movil"
                            value="<?= isset($old['telefono_movil']) ? htmlspecialchars($old['telefono_movil']) : '' ?>"
                            placeholder="Ej: 507-6123-4567" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="telefono_fijo">🏠 Teléfono Fijo</label>
                    <input type="text" id="telefono_fijo" name="telefono_fijo"
                        value="<?= isset($old['telefono_fijo']) ? htmlspecialchars($old['telefono_fijo']) : '' ?>"
                        placeholder="Ej: 507-222-1234">
                </div>
            </div>

            <!-- SECCIÓN 3: FORMACIÓN Y EXPERIENCIA -->
            <div class="form-section">
                <h3>🎓 Formación y Experiencia</h3>

                <div class="form-row">
                    <div class="form-group half">
                        <label for="nivel_educativo">🎓 Nivel Educativo <span class="required">*</span></label>
                        <select id="nivel_educativo" name="nivel_educativo" required>
                            <option value="">Seleccione</option>
                            <option value="Primaria" <?= (isset($old['nivel_educativo']) && $old['nivel_educativo'] === 'Primaria') ? 'selected' : '' ?>>Primaria</option>
                            <option value="Secundaria" <?= (isset($old['nivel_educativo']) && $old['nivel_educativo'] === 'Secundaria') ? 'selected' : '' ?>>Secundaria</option>
                            <option value="Técnico" <?= (isset($old['nivel_educativo']) && $old['nivel_educativo'] === 'Técnico') ? 'selected' : '' ?>>Técnico</option>
                            <option value="Universitario" <?= (isset($old['nivel_educativo']) && $old['nivel_educativo'] === 'Universitario') ? 'selected' : '' ?>>Universitario</option>
                            <option value="Posgrado" <?= (isset($old['nivel_educativo']) && $old['nivel_educativo'] === 'Posgrado') ? 'selected' : '' ?>>Posgrado</option>
                            <option value="Doctorado" <?= (isset($old['nivel_educativo']) && $old['nivel_educativo'] === 'Doctorado') ? 'selected' : '' ?>>Doctorado</option>
                        </select>
                    </div>

                    <div class="form-group half">
                        <label for="ocupacion_actual">💼 Ocupación Actual</label>
                        <input type="text" id="ocupacion_actual" name="ocupacion_actual"
                            value="<?= isset($old['ocupacion_actual']) ? htmlspecialchars($old['ocupacion_actual']) : '' ?>"
                            placeholder="Ej: Ingeniero de Software">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group half">
                        <label>
                            <input type="checkbox" name="experiencia_previa" value="1"
                                <?= isset($old['experiencia_previa']) ? 'checked' : '' ?>>
                            ✅ ¿Tiene experiencia previa en tecnología?
                        </label>
                    </div>

                    <div class="form-group half">
                        <label for="anos_experiencia">📊 Años de Experiencia</label>
                        <input type="number" id="anos_experiencia" name="anos_experiencia" min="0" max="50"
                            value="<?= isset($old['anos_experiencia']) ? htmlspecialchars($old['anos_experiencia']) : 0 ?>">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 4: TEMAS DE INTERÉS -->
            <div class="form-section">
                <h3>💻 Temas de Interés</h3>
                <p class="help-text">Selecciona los temas tecnológicos que te gustaría aprender</p>

                <div class="checkbox-group">
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="categorias[]" value="<?= $categoria['id_categoria'] ?>"
                                    <?= (isset($old['categorias']) && in_array($categoria['id_categoria'], $old['categorias'])) ? 'checked' : '' ?>>
                                <span class="checkbox-label">
                                    <strong><?= htmlspecialchars($categoria['nombre_categoria']) ?></strong>
                                    <small>(<?= htmlspecialchars($categoria['nivel_dificultad']) ?>)</small>
                                </span>
                                <?php if ($categoria['certificacion']): ?>
                                    <span class="badge-cert">🏆 Certificación</span>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="color: #e74c3c; font-weight: bold;">⚠️ No hay categorías disponibles. Verifica la conexión a la base de datos.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SECCIÓN 5: COMENTARIOS -->
            <div class="form-section">
                <h3>📝 Comentarios Adicionales</h3>
                <div class="form-group">
                    <label for="comentarios_adicionales">¿Alguna observación o consulta sobre el evento?</label>
                    <textarea id="comentarios_adicionales" name="comentarios_adicionales" rows="4"
                        placeholder="Escribe tus comentarios aquí..."><?= isset($old['comentarios_adicionales']) ? htmlspecialchars($old['comentarios_adicionales']) : '' ?></textarea>
                    <small>Máximo 500 caracteres</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">✅ Registrar Participante</button>
                <button type="reset" class="btn-secondary">🗑️ Limpiar Formulario</button>
            </div>
        </form>

        <div class="actions-bar">
            <a href="index.php?action=reporte" class="btn-info">📊 Ver Reporte Completo</a>
        </div>

        <footer>
            <p>© 2025 iTECH. All rights reserved. | Versión 2.0</p>
        </footer>
    </div>
    <?php
    // Limpiar datos antiguos de sesión
    if (isset($_SESSION['old'])) {
        unset($_SESSION['old']);
    }
    ?>
</body>

</html>