<?php

namespace Controllers\Productos;

use Controllers\PublicController;
use Dao\Productos\Productos as DaoProducto;
use Utilities\Validators;
use Utilities\Site;
use Utilities\ArrUtils;
use Views\Renderer;

class ProductosForm extends PublicController
{
    private $viewData = [];
    private $productname = "";
    private $categoryid = "NDF";
    private $price = 0.00;
    private $stockquantity = 0;
    private $productid = 0;
    private $mode = "DSP";

    private $modeDscArr = [
        "DSP" => "Mostrar %s",
        "INS" => "Crear Nuevo",
        "UPD" => "Actualizar %s",
        "DEL" => "Eliminar %s"
    ];
    private $error = [];
    private $has_errors = false;
    private $isReadOnly = "readonly";
    private $showActions = true;
    private $cxfToken = "";

    private $categoriesOptions = [
        "NDF" => "No definido",
        "FRT" => "Frutas",
        "VGT" => "Verduras",
        "DIA" => "Lácteos",
        "MEA" => "Carnes",
        "GRN" => "Granos",
        "SNA" => "Snacks"
    ];


    private function addError($errorMsg, $origin = "global")
    {
        if (!isset($this->error[$origin])) {
            $this->error[$origin] = [];
        }
        $this->error[$origin][] = $errorMsg;
        $this->has_errors = true;
    }

    private function getGetData()
    {
        if (isset($_GET['mode'])) {
            $this->mode = $_GET['mode'];
            if (!isset($this->modeDscArr[$this->mode])) {
                $this->addError('Modo Invalido');
            }
        }
        if (isset($_GET["productid"])) {
            $this->productid = intval($_GET["productid"]);
            $tmpProductFromDb = DaoProducto::getById($this->productid);
            if ($tmpProductFromDb) {
                $this->productname = $tmpProductFromDb['productname'];
                $this->categoryid = $tmpProductFromDb['categoryid'];
                $this->price = $tmpProductFromDb['price'];
                $this->stockquantity = $tmpProductFromDb['stockquantity'];
            } else {
                $this->addError("Producto No Encontrado");
            }
        }
    }



    private function prepareView()
    {
        $this->viewData["modeDsc"] = sprintf($this->modeDscArr[$this->mode], $this->productname);
        $this->viewData["mode"] = $this->mode;
        $this->viewData["productname"] = $this->productname;
        $this->viewData["categoryid"] = $this->categoryid;
        $this->viewData["price"] = $this->price;
        $this->viewData["stockquantity"] = $this->stockquantity;
        $this->viewData["productid"] = $this->productid;
        $this->viewData["error"] = $this->error;
        $this->viewData["has_errors"] = $this->has_errors;

        if ($this->mode == "DSP" || $this->mode == "DEL") {
            $this->isReadOnly = "readonly";
            if ($this->mode == "DSP") {
                $this->showActions = false;
            }
        } else {
            $this->isReadOnly = "";
            $this->showActions = true;
        }
        $this->viewData["isReadOnly"] = $this->isReadOnly;
        $this->viewData["showActions"] = $this->showActions;
        $this->viewData["cxfToken"] = $this->cxfToken;
        $this->viewData["categoriesOptions"] = ArrUtils::toOptionsArray(
            $this->categoriesOptions,
            "key",
            "values",
            "selected",
            $this->categoryid
        );
    }

    public function run(): void
    {
        $this->getGetData();
        $this->prepareView();

        Renderer::render("productos/form", $this->viewData);
    }
}
