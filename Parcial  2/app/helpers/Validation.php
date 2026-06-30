<?php
class Validation
{
    public static function requerido($campo)
    {
        return !empty(trim($campo));
    }

    public static function email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function telefono($telefono)
    {
        return preg_match('/^[0-9+\-\s\(\)]+$/', $telefono);
    }

    public static function edad($edad)
    {
        return is_numeric($edad) && $edad >= 1 && $edad <= 120;
    }

    public static function genero($genero)
    {
        return in_array($genero, ['Masculino', 'Femenino', 'No binario', 'Prefiero no decirlo']);
    }

    public static function documento($documento)
    {
        return preg_match('/^[a-zA-Z0-9\-]{5,25}$/', $documento);
    }

    public static function ubicacion($id)
    {
        return is_numeric($id) && $id > 0;
    }

    public static function nivelEducativo($nivel)
    {
        return in_array($nivel, ['Primaria', 'Secundaria', 'Técnico', 'Universitario', 'Posgrado', 'Doctorado']);
    }

    public static function experiencia($valor)
    {
        return is_numeric($valor) && $valor >= 0 && $valor <= 50;
    }

    public static function textoLargo($texto, $max = 500)
    {
        return strlen(trim($texto)) <= $max;
    }
}
