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
        1 => "No definido",
        2 => "Frutas",
        3 => "Verduras",
        4 => "Lácteos",
        5 => "Carnes",
        6 => "Granos",
        7 => "Snacks"
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

    private function executePostAction()
    {
        switch ($this->mode) {
            case "INS":
                $result = DaoProducto::add(
                    $this->productname,
                    $this->categoryid,
                    $this->price,
                    $this->stockquantity
                );
                if ($result > 0) {
                    Site::redirectToWithMsg(
                        "index.php?page=Productos_ProductosList",
                        "Producto Creado"
                    );
                } else {
                    $this->addError("Error al Crear el Producto");
                }
                break;
                // case "UPD":
                //     $result = DaoProducto::update(
                //         $this->productname,
                //         $this->categoryid,
                //         $this->price,
                //         $this->stockquantity,
                //         $this->productid
                //     );
                //     if ($result > 0) {
                //         Site::redirectToWithMsg(
                //             "index.php?page=Productos_ProductosList",
                //             "Producto Actualizado"
                //         );
                //     } else {
                //         $this->addError("Error al Actualizar el Producto");
                //     }
                //     break;
                // case "DEL":
                //     $result = DaoProducto::delete(
                //         $this->productid
                //     );
                //     if ($result > 0) {
                //         Site::redirectToWithMsg(
                //             "index.php?page=Productos_ProductosList",
                //             "Producto Eliminado"
                //         );
                //     } else {
                //         $this->addError("Error al Eliminar el Producto");
                //     }
                //     break;
            default:
                $this->addError("Modo Invalido");
                break;
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
    private function getPostData()
    {
        if (isset($_POST["cxfToken"])) {
            $this->cxfToken = $_POST['cxfToken'];
            if (Validators::IsEmpty($this->cxfToken)) {
                $this->addError('Token Invalido');
            }
        }
        if (isset($_POST['mode'])) {
            $tmpMode = $_POST['mode'];
            if (!isset($this->modeDscArr[$tmpMode])) {
                $this->addError("Modo invalido");
            }
            if ($this->mode != $tmpMode) {
                $this->addError("Modo Invalido");
            }
        }
        if (isset($_POST['productname'])) {
            $this->productname = $_POST['productname'];
            if (Validators::IsEmpty($this->productname)) {
                $this->addError('Nombre Invalido', "productname_error");
            }
        }
        if (isset($_POST['categoryid'])) {
            $this->categoryid = $_POST['categoryid'];
            if (!isset($this->categoriesOptions[$this->categoryid])) {
                $this->addError('Categoría Invalida', "categoryid_error");
            }
        }
        if (isset($_POST['price'])) {
            $this->price = $_POST['price'];
            if (!is_numeric($this->price) || $this->price < 0) {
                $this->addError('Precio Invalido', "price_error");
            }
        }
        if (isset($_POST['stockquantity'])) {
            $this->stockquantity = $_POST['stockquantity'];
            if (!is_numeric($this->stockquantity) || $this->stockquantity < 0) {
                $this->addError('Stock Invalido', "stockquantity_error");
            }
        }
    }

    public function run(): void
    {
        $this->getGetData();
        if ($this->isPostBack()) {
            $this->getPostData();
            $this->executePostAction();
        }
        $this->prepareView();

        Renderer::render("productos/form", $this->viewData);
    }
}
