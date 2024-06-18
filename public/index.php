<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px; /* Aumenté el ancho del contenedor para que la tabla se vea mejor */
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .inStock {
            color: green;
            font-weight: bold;
        }

        .outOfStock {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Formulario de Producto</h2>
        <?php
        // Array para almacenar los productos
        $productos = array();

        // Función para guardar producto
        function guardarProducto(&$productos, $nombre, $precio, $cantidad) {
            $productos[] = array(
                "nombre" => $nombre,
                "precio" => $precio,
                "cantidad" => $cantidad
            );
        }

        // Función para mostrar la tabla de productos corregida
        function mostrarTablaProductos($productos) {
            if (empty($productos)) {
                echo "<p>No hay productos registrados.</p>";
                return;
            }

            echo "<table>";
            echo "<tr>
                    <th>Nombre del Producto</th>
                    <th>Precio por Unidad</th>
                    <th>Cantidad en Inventario</th>
                    <th>Valor Total</th>
                    <th>Estado</th>
                  </tr>";

            foreach ($productos as $producto) {
                $nombre = $producto['nombre'];
                $precio = $producto['precio'];
                $cantidad = $producto['cantidad'];
                $valorTotal = $precio * $cantidad;
                $estado = ($cantidad == 0) ? '<span class="outOfStock">Agotado</span>' : '<span class="inStock">En stock</span>';

                echo "<tr>
                        <td>$nombre</td>
                        <td>$precio</td>
                        <td>$cantidad</td>
                        <td>$valorTotal</td>
                        <td>$estado</td>
                      </tr>";
            }

            echo "</table>";
        }

        $errors = array();
        $productName = $pricePerUnit = $quantityInStock = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["productName"])) {
                $errors['productName'] = "El nombre del producto es obligatorio.";
            } else {
                $productName = htmlspecialchars($_POST["productName"]);
            }

            if (empty($_POST["pricePerUnit"]) || !is_numeric($_POST["pricePerUnit"])) {
                $errors['pricePerUnit'] = "El precio por unidad debe ser un número.";
            } else {
                $pricePerUnit = floatval($_POST["pricePerUnit"]);
                if ($pricePerUnit <= 0) {
                    $errors['pricePerUnit'] = "El precio por unidad debe ser mayor que cero.";
                }
            }

            if (empty($_POST["quantityInStock"]) || !is_numeric($_POST["quantityInStock"])) {
                $errors['quantityInStock'] = "La cantidad en inventario debe ser un número.";
            } else {
                $quantityInStock = intval($_POST["quantityInStock"]);
                if ($quantityInStock < 0) {
                    $errors['quantityInStock'] = "La cantidad en inventario debe ser un número entero no negativo.";
                }
            }

            if (empty($errors)) {
                // Guardar producto en el array usando la función
                guardarProducto($productos, $productName, $pricePerUnit, $quantityInStock);
                echo "<p>Formulario enviado correctamente!</p>";
                // Mostrar los productos almacenados
                mostrarTablaProductos($productos);
            }
        }
        ?>

        <form id="productForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="productName">Nombre del Producto:</label>
            <input type="text" id="productName" name="productName" value="<?php echo $productName;?>">
            <div id="productNameError" class="error"><?php echo isset($errors['productName']) ? $errors['productName'] : ''; ?></div>

            <label for="pricePerUnit">Precio por Unidad:</label>
            <input type="text" id="pricePerUnit" name="pricePerUnit" value="<?php echo $pricePerUnit;?>">
            <div id="pricePerUnitError" class="error"><?php echo isset($errors['pricePerUnit']) ? $errors['pricePerUnit'] : ''; ?></div>

            <label for="quantityInStock">Cantidad en Inventario:</label>
            <input type="text" id="quantityInStock" name="quantityInStock" value="<?php echo $quantityInStock;?>">
            <div id="quantityInStockError" class="error"><?php echo isset($errors['quantityInStock']) ? $errors['quantityInStock'] : ''; ?></div>

            <button type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>
