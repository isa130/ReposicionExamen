<h2>Listado de Productos</h2>
<section class="WWList">
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th><a href="index.php?page=Productos_ProductosForm&mode=INS&productid=0">Nuevo</a></th>
            </tr>
        </thead>
        <tbody>
            {{foreach productos}}
                <tr>
                    <th>{{productid}}</th>
                    <th><a
                            href="index.php?page=Productos_ProductosForm&mode=DSP&productid={{productid}}">{{productname}}</a>
                    </th>
                    <th>{{categoryid}}</th>
                    <th>{{price}}</th>
                    <th>{{stockquantity}}</th>
                    <th>
                        <a href="index.php?page=Productos_ProductosForm&mode=UPD&productid={{productid}}">Editar</a>
                        &nbsp;
                        <a href="index.php?page=Productos_ProductosForm&mode=DEL&productid={{productid}}">Eliminar</a>
                    </th>
                </tr>
                {{endfor productos}}
            </tbody>
        </table>
    </section>