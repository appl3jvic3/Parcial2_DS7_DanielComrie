<?php
// =============================================
// CONTROLADOR PARTICIPANTE - iTECH 2025
// =============================================

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Participante.php';
require_once __DIR__ . '/../models/Ubicacion.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../helpers/Validation.php';
require_once __DIR__ . '/../helpers/Sanitization.php';

class ParticipanteController extends Controller
{

    public function index()
    {
        // Inicializar variables con valores por defecto
        $ubicaciones = [];
        $categorias = [];
        $message = null;
        $old = isset($_SESSION['old']) ? $_SESSION['old'] : [];

        try {
            // Cargar países
            $ubicacionModel = new Ubicacion();
            $ubicaciones = $ubicacionModel->getAll();

            // Cargar categorías
            $categoriaModel = new Categoria();
            $categorias = $categoriaModel->getAll();
        } catch (Exception $e) {
            $message = ['text' => 'Error al cargar datos: ' . $e->getMessage(), 'type' => 'error'];
        }

        // Si hay mensaje de sesión, usarlo
        if (isset($_SESSION['message'])) {
            $message = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        // Limpiar old de sesión después de usarlo
        if (isset($_SESSION['old'])) {
            unset($_SESSION['old']);
        }

        // DEBUG: Verificar que los datos se cargaron
        // var_dump($ubicaciones);
        // var_dump($categorias);

        // Pasar variables a la vista
        $this->view('form', [
            'ubicaciones' => $ubicaciones,
            'categorias' => $categorias,
            'message' => $message,
            'old' => $old
        ]);
    }

    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('index.php');
        }

        // Sanitizar entradas
        $documento = Sanitization::limpiarString($_POST['documento_identidad'] ?? '');
        $primer_nombre = Sanitization::limpiarString($_POST['primer_nombre'] ?? '');
        $segundo_nombre = isset($_POST['segundo_nombre']) ? Sanitization::limpiarString($_POST['segundo_nombre']) : null;
        $primer_apellido = Sanitization::limpiarString($_POST['primer_apellido'] ?? '');
        $segundo_apellido = isset($_POST['segundo_apellido']) ? Sanitization::limpiarString($_POST['segundo_apellido']) : null;
        $edad = Sanitization::limpiarEntero($_POST['edad'] ?? 0);
        $genero = Sanitization::limpiarString($_POST['genero'] ?? '');
        $ubicacion_id = Sanitization::limpiarEntero($_POST['ubicacion_id'] ?? 0);
        $nacionalidad = Sanitization::limpiarString($_POST['nacionalidad_oficial'] ?? '');
        $correo = Sanitization::limpiarEmail($_POST['correo_electronico'] ?? '');
        $telefono = Sanitization::limpiarTelefono($_POST['telefono_movil'] ?? '');
        $telefono_fijo = isset($_POST['telefono_fijo']) ? Sanitization::limpiarTelefono($_POST['telefono_fijo']) : null;
        $nivel_educativo = Sanitization::limpiarString($_POST['nivel_educativo'] ?? '');
        $ocupacion = isset($_POST['ocupacion_actual']) ? Sanitization::limpiarString($_POST['ocupacion_actual']) : null;
        $experiencia_previa = isset($_POST['experiencia_previa']) ? 1 : 0;
        $anos_experiencia = isset($_POST['anos_experiencia']) ? Sanitization::limpiarEntero($_POST['anos_experiencia']) : 0;
        $comentarios = isset($_POST['comentarios_adicionales']) ? Sanitization::limpiarTexto($_POST['comentarios_adicionales']) : null;
        $categorias = isset($_POST['categorias']) ? $_POST['categorias'] : [];

        // Validar
        $errores = [];

        if (!Validation::requerido($documento)) $errores[] = "Documento de identidad es obligatorio.";
        if (!Validation::documento($documento)) $errores[] = "Documento inválido (5-25 caracteres alfanuméricos y guiones).";

        if (!Validation::requerido($primer_nombre)) $errores[] = "Nombre es obligatorio.";
        if (!Validation::requerido($primer_apellido)) $errores[] = "Apellido es obligatorio.";

        if (!Validation::edad($edad)) $errores[] = "Edad inválida (1-120 años).";
        if (!Validation::genero($genero)) $errores[] = "Género inválido.";
        if (!Validation::ubicacion($ubicacion_id)) $errores[] = "País de residencia inválido.";
        if (!Validation::requerido($nacionalidad)) $errores[] = "Nacionalidad es obligatoria.";
        if (!Validation::email($correo)) $errores[] = "Correo electrónico inválido.";
        if (!Validation::telefono($telefono)) $errores[] = "Teléfono móvil inválido (solo dígitos, espacios, guiones, paréntesis).";
        if (!Validation::nivelEducativo($nivel_educativo)) $errores[] = "Nivel educativo inválido.";

        if ($experiencia_previa && !Validation::experiencia($anos_experiencia)) {
            $errores[] = "Años de experiencia inválido (0-50).";
        }

        if ($comentarios && !Validation::textoLargo($comentarios, 500)) {
            $errores[] = "Los comentarios no pueden exceder 500 caracteres.";
        }

        foreach ($categorias as $c) {
            if (!is_numeric($c)) $errores[] = "Categoría inválida.";
        }

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old'] = $_POST;
            $this->redirect('index.php');
        }

        // Preparar datos para guardar
        $data = [
            'documento_identidad' => $documento,
            'primer_nombre' => $primer_nombre,
            'segundo_nombre' => $segundo_nombre,
            'primer_apellido' => $primer_apellido,
            'segundo_apellido' => $segundo_apellido,
            'edad' => $edad,
            'genero' => $genero,
            'ubicacion_id' => $ubicacion_id,
            'nacionalidad_oficial' => $nacionalidad,
            'correo_electronico' => $correo,
            'telefono_movil' => $telefono,
            'telefono_fijo' => $telefono_fijo,
            'nivel_educativo' => $nivel_educativo,
            'ocupacion_actual' => $ocupacion,
            'experiencia_previa' => $experiencia_previa,
            'anos_experiencia' => $anos_experiencia,
            'comentarios_adicionales' => $comentarios
        ];

        try {
            $participante = new Participante();
            $participante->guardar($data, $categorias);
            $_SESSION['message'] = ['text' => '✅ ¡Participante inscrito exitosamente!', 'type' => 'success'];
        } catch (Exception $e) {
            $_SESSION['message'] = ['text' => '❌ Error al guardar: ' . $e->getMessage(), 'type' => 'error'];
        }

        $this->redirect('index.php');
    }

    public function reporte()
    {
        try {
            $participante = new Participante();
            $registros = $participante->getTodosConCategorias();
            $estadisticas = $participante->getEstadisticas();
        } catch (Exception $e) {
            $registros = [];
            $estadisticas = [];
            $_SESSION['message'] = ['text' => 'Error al cargar reporte: ' . $e->getMessage(), 'type' => 'error'];
        }

        $this->view('reporte', [
            'registros' => $registros,
            'estadisticas' => $estadisticas
        ]);
    }

    public function exportar()
    {
        try {
            $participante = new Participante();
            $registros = $participante->getTodosConCategorias();

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="reporte_participantes_' . date('Y-m-d') . '.csv"');
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($output, [
                'ID',
                'Documento',
                'Nombre Completo',
                'Edad',
                'Género',
                'País',
                'Continente',
                'Nacionalidad',
                'Correo',
                'Teléfono',
                'Nivel Educativo',
                'Ocupación',
                'Temas de Interés',
                'Cantidad Temas',
                'Experiencia Previa',
                'Años Experiencia',
                'Comentarios',
                'Fecha Inscripción',
                'Días Inscrito',
                'Estado',
                'Integridad'
            ]);

            foreach ($registros as $row) {
                $esValido = $participante->verificarIntegridad($row);

                fputcsv($output, [
                    $row['id_participante'] ?? '',
                    $row['documento_identidad'] ?? '',
                    $row['nombre_completo'] ?? '',
                    $row['edad'] ?? '',
                    $row['genero'] ?? '',
                    $row['pais_residencia'] ?? '',
                    $row['continente'] ?? '',
                    $row['nacionalidad_oficial'] ?? '',
                    $row['correo_electronico'] ?? '',
                    $row['telefono_movil'] ?? '',
                    $row['nivel_educativo'] ?? '',
                    $row['ocupacion_actual'] ?? '',
                    $row['temas_interes'] ?? '',
                    $row['cantidad_temas'] ?? 0,
                    $row['experiencia_previa'] ? 'Sí' : 'No',
                    $row['anos_experiencia'] ?? 0,
                    $row['comentarios_adicionales'] ?? '',
                    $row['fecha_inscripcion'] ?? '',
                    $row['dias_inscrito'] ?? 0,
                    $row['estado_participante'] ?? 'Activo',
                    $esValido ? 'Válido' : 'Inválido'
                ]);
            }
            fclose($output);
            exit;
        } catch (Exception $e) {
            die('Error al exportar: ' . $e->getMessage());
        }
    }

    public function buscar()
    {
        if (isset($_GET['termino']) && !empty($_GET['termino'])) {
            $termino = Sanitization::limpiarString($_GET['termino']);
            $participante = new Participante();
            $resultados = $participante->buscar($termino);

            header('Content-Type: application/json');
            echo json_encode($resultados);
            exit;
        }
    }

    public function estadisticas()
    {
        $participante = new Participante();
        $estadisticas = $participante->getEstadisticas();

        header('Content-Type: application/json');
        echo json_encode($estadisticas);
        exit;
    }
}
