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
    // add
    public static function add(
        $productname,
        $categoryid,
        $price,
        $stockquantity
    ) {
        $insertSql = "INSERT INTO Productos (productname, categoryid, price, stockquantity) VALUES (:productname, :categoryid, :price, :stockquantity)";
        return self::executeNonQuery($insertSql, [
            "productname" => $productname,
            "categoryid" => $categoryid,
            "price" => $price,
            "stockquantity" => $stockquantity
        ]);
    }
    // update
    public static function update(
        $productname,
        $categoryid,
        $price,
        $stockquantity,
        $productid
    ) {
        $updateSql = "UPDATE Productos SET productname = :productname, categoryid = :categoryid, price = :price, stockquantity = :stockquantity WHERE productid = :productid";
        return self::executeNonQuery($updateSql, [
            "productname" => $productname,
            "categoryid" => $categoryid,
            "price" => $price,
            "stockquantity" => $stockquantity,
            "productid" => $productid
        ]);
    }
}
