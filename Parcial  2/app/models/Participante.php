<?php
// =============================================
// MODELO PARTICIPANTE - iTECH 2025
// =============================================

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/../config/database.php';

class Participante extends Model
{
    protected $table = 'participantes_evento';
    protected $primaryKey = 'id_participante';

    public function guardar($data, $categoriasSeleccionadas)
    {
        // Capitalizar nombres y apellidos
        $data['primer_nombre'] = ucwords(strtolower(trim($data['primer_nombre'])));
        $data['segundo_nombre'] = !empty($data['segundo_nombre']) ? ucwords(strtolower(trim($data['segundo_nombre']))) : null;
        $data['primer_apellido'] = ucwords(strtolower(trim($data['primer_apellido'])));
        $data['segundo_apellido'] = !empty($data['segundo_apellido']) ? ucwords(strtolower(trim($data['segundo_apellido']))) : null;

        // Verificar que SECRET_KEY esté definida
        if (!defined('SECRET_KEY')) {
            define('SECRET_KEY', 'ClaveSuperSecreta2025ParaFirmaDigital');
        }

        // Calcular firma digital con OpenSSL (hash_hmac)
        $datosFirma = $data['documento_identidad'] . $data['primer_nombre'] . $data['primer_apellido'] .
            $data['correo_electronico'] . $data['telefono_movil'] . $data['genero'];
        $firma = hash_hmac('sha256', $datosFirma, SECRET_KEY);
        $data['firma_digital'] = $firma;

        // Construir la consulta SQL dinámicamente
        $campos = [];
        $valores = [];
        $params = [];

        foreach ($data as $campo => $valor) {
            if ($campo !== 'categorias' && $campo !== 'experiencia_previa_check') {
                $campos[] = $campo;
                $valores[] = '?';
                $params[] = $valor;
            }
        }

        $sql = "INSERT INTO participantes_evento (" . implode(', ', $campos) . ") 
                VALUES (" . implode(', ', $valores) . ")";

        $participanteId = $this->db->insert($sql, $params);

        // Insertar categorías seleccionadas
        if (!empty($categoriasSeleccionadas)) {
            foreach ($categoriasSeleccionadas as $categoriaId) {
                $sqlCat = "INSERT INTO participante_categoria (participante_id, categoria_id, prioridad) 
                           VALUES (?, ?, 'Media')";
                $this->db->execute($sqlCat, [$participanteId, $categoriaId]);
            }
        }

        return $participanteId;
    }

    public function getTodosConCategorias()
    {
        // Usar la vista creada en la base de datos
        $sql = "SELECT * FROM reporte_completo_participantes ORDER BY fecha_inscripcion DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Verificar integridad de un registro
     * IMPORTANTE: Esta función recibe un registro de la vista reporte_completo_participantes
     * que tiene campos diferentes a la tabla original
     */
    public function verificarIntegridad($registro)
    {
        if (!defined('SECRET_KEY')) {
            define('SECRET_KEY', 'ClaveSuperSecreta2025ParaFirmaDigital');
        }

        // Verificar si el registro tiene los campos necesarios
        // Para la vista reporte_completo_participantes, usamos los campos disponibles
        if (
            isset($registro['documento_identidad']) &&
            isset($registro['correo_electronico']) &&
            isset($registro['telefono_movil']) &&
            isset($registro['genero']) &&
            isset($registro['firma_digital'])
        ) {

            // Para registros de la vista, necesitamos obtener los nombres originales
            // Si tenemos nombre_completo, lo dividimos para obtener primer_nombre y primer_apellido
            $primer_nombre = '';
            $primer_apellido = '';

            if (isset($registro['nombre_completo'])) {
                $partes = explode(' ', trim($registro['nombre_completo']));
                $primer_nombre = $partes[0] ?? '';
                // El apellido podría ser el último o una combinación
                $primer_apellido = end($partes) ?? '';
            }

            // Si no tenemos nombre_completo, intentamos con los campos individuales
            if (empty($primer_nombre) && isset($registro['primer_nombre'])) {
                $primer_nombre = $registro['primer_nombre'];
                $primer_apellido = $registro['primer_apellido'] ?? '';
            }

            // Construir datos para la firma
            $datosFirma = ($registro['documento_identidad'] ?? '') .
                $primer_nombre .
                $primer_apellido .
                ($registro['correo_electronico'] ?? '') .
                ($registro['telefono_movil'] ?? '') .
                ($registro['genero'] ?? '');

            $firmaCalculada = hash_hmac('sha256', $datosFirma, SECRET_KEY);
            return ($firmaCalculada === ($registro['firma_digital'] ?? ''));
        }

        // Si no tenemos todos los campos, devolvemos false
        return false;
    }

    /**
     * Verificar integridad para registros de la tabla original (no vista)
     */
    public function verificarIntegridadOriginal($registro)
    {
        if (!defined('SECRET_KEY')) {
            define('SECRET_KEY', 'ClaveSuperSecreta2025ParaFirmaDigital');
        }

        $datos = ($registro['documento_identidad'] ?? '') .
            ($registro['primer_nombre'] ?? '') .
            ($registro['primer_apellido'] ?? '') .
            ($registro['correo_electronico'] ?? '') .
            ($registro['telefono_movil'] ?? '') .
            ($registro['genero'] ?? '');

        $firmaCalculada = hash_hmac('sha256', $datos, SECRET_KEY);
        return ($firmaCalculada === ($registro['firma_digital'] ?? ''));
    }

    public function buscar($termino)
    {
        $sql = "CALL buscar_participantes(?)";
        return $this->db->fetchAll($sql, [$termino]);
    }

    public function getEstadisticas()
    {
        $sql = "SELECT 
                    COUNT(*) as total_participantes,
                    COUNT(DISTINCT ubicacion_id) as total_paises,
                    AVG(edad) as edad_promedio,
                    SUM(CASE WHEN genero = 'Masculino' THEN 1 ELSE 0 END) as total_masculino,
                    SUM(CASE WHEN genero = 'Femenino' THEN 1 ELSE 0 END) as total_femenino,
                    SUM(CASE WHEN experiencia_previa = 1 THEN 1 ELSE 0 END) as con_experiencia,
                    AVG(anos_experiencia) as promedio_experiencia
                FROM participantes_evento";
        return $this->db->fetchOne($sql);
    }
}
