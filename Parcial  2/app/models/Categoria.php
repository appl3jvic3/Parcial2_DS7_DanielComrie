<?php

require_once __DIR__ . '/Model.php';

class Categoria extends Model
{
    protected $table = 'categorias_tecnologicas';
    protected $primaryKey = 'id_categoria';

    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY nombre_categoria";
        return $this->db->fetchAll($sql);
    }

    public function getByNivel($nivel)
    {
        $sql = "SELECT * FROM {$this->table} WHERE nivel_dificultad = ?";
        return $this->db->fetchAll($sql, [$nivel]);
    }
}
