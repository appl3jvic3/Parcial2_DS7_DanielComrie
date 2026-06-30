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
            <!-- RESTA DEL FORMULARIO IGUAL QUE ANTES -->
            <!-- ... -->

            <div class="form-section">
                <h3>🌍 Ubicación</h3>
                <div class="form-group">
                    <label for="ubicacion_id">País de Residencia <span class="required">*</span></label>
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
                            <option value="" disabled>No hay países disponibles</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

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
                        <p>No hay categorías disponibles</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RESTA DEL FORMULARIO -->

        </form>

        <!-- ... -->
    </div>
</body>

</html>