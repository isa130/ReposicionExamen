<?php

namespace Controllers\Productos;

use Controllers\PublicController;
use Dao\Productos\Productos;
use Views\Renderer;

class ProductosList extends PublicController
{
    public function run(): void
    {
        $viewData["productos"] = Productos::getAll();
        Renderer::render("productos/list", $viewData);
    }
}
