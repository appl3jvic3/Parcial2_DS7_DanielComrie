<?php
class Sanitization {
    public static function limpiarString($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function limpiarEmail($email) {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    public static function limpiarTelefono($telefono) {
        return preg_replace('/[^0-9+\-\s\(\)]/', '', $telefono);
    }

    public static function limpiarEntero($input) {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function limpiarTexto($texto) {
        return htmlspecialchars(strip_tags(trim($texto)), ENT_QUOTES, 'UTF-8');
    }

    public static function limpiarBooleano($input) {
        return filter_var($input, FILTER_VALIDATE_BOOLEAN);
    }

    public static function limpiarArray($array) {
        if (!is_array($array)) return [];
        return array_map(function($item) {
            return self::limpiarEntero($item);
        }, $array);
    }
}
?>