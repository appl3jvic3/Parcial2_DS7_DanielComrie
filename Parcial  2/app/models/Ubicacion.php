<?php
// =============================================
// MODELO UBICACIÓN - iTECH 2025
// =============================================

require_once __DIR__ . '/Model.php';

class Ubicacion extends Model
{
    protected $table = 'ubicaciones_geograficas';
    protected $primaryKey = 'id_ubicacion';

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} WHERE activo = 1 ORDER BY nombre_pais";
        return $this->db->fetchAll($sql);
    }

    public function getByContinente($continente)
    {
        $sql = "SELECT * FROM {$this->table} WHERE continente = ? AND activo = 1";
        return $this->db->fetchAll($sql, [$continente]);
    }
}
