<h2>{{modeDsc}}</h2>
<form action="index.php?page=Productos_ProductosForm&mode={{mode}}&productid={{productid}}" method="post">
    <div>
        <input type="hidden" name="mode" value="{{mode}}">
        <input type="hidden" name="cxfToken" value="{{cxfToken}}">
    </div>
    <div>
        <label for="productid">Código</label>
        <input type="text" name="productid" id="productid" value="{{productid}}" readonly>
    </div>
    <div>
        <label for="productname">Nombre</label>
        <input type="text" name="productname" id="productname" value="{{productname}}" {{isReadOnly}}>
    </div>
    <div>
        <label for="categoryid">Categoría</label>
        <select name="categoryid" id="categoryid" {{isReadOnly}}>
            {{foreach categoriesOptions}}
                <option value="{{key}}" {{selected}}>{{values}}</option>
                {{endfor categoriesOptions}}
            </select>
        </div>
        <div>
            <label for="price">Precio</label>
            <input type="text" name="price" id="price" value="{{price}}" {{isReadOnly}}>
        </div>
        <div>
            <label for="stockquantity">Stock</label>
            <input type="text" name="stockquantity" id="stockquantity" value="{{stockquantity}}" {{isReadOnly}}>
        </div>
        <div>
            {{if showActions}}
                <input type="submit" value="Guardar" {{isReadOnly}}>
                {{endif showActions}}
                <input type="button" value="Cancelar" onclick="location.href='index.php?page=Productos_ProductosList'">
            </div>
        </form>