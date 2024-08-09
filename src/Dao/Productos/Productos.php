<?php

namespace Dao\Productos;

use Dao\Table;

class Productos extends Table
{
    // getAll
    public static function getAll()
    {
        return self::obtenerRegistros("SELECT * FROM Productos", []);
    }

    // getById
    public static function getById($id)
    {
        return self::obtenerUnRegistro(
            "SELECT * FROM Productos WHERE productid = :id",
            ["id" => $id]
        );
    }
}
