<?php
session_start(); // con esto iniciamos la sesion directamente por si al abrir la pagina quedan datos flotando
if (isset($_POST['submit'])) {

    //el htmlspecialchars es para que no se metan caracteres especiales y  asi  cuando tengas que codificar
    // en la base de datos no se metan caracteres especiales por si modificamos el arreglo
    $id = htmlspecialchars(trim($_POST['id']));
    $cliente_id = htmlspecialchars(trim($_POST['cliente_id']));
    $producto = htmlspecialchars(trim($_POST['producto']));
    $cantidad = htmlspecialchars(trim($_POST['cantidad']));
    $fecha = htmlspecialchars(trim($_POST['fecha']));
    $estado = htmlspecialchars(trim($_POST['estado']));

    // con el siguiente codigo se verifica que los datos esten puestos y no queden vacio cuando se confirme 
    // asi evitamos directamente que que hayan espacios en blanco
    $errors = array();
    if (empty($id)) {
        $errors[] = 'El ID es requerido.';
    }
    if (empty($cliente_id)) {
        $errors[] = 'El Cliente_ID es requerido.';
    }
    if (empty($producto)) {
        $errors[] = 'El producto es requerido.';
    }
    if (empty($cantidad)) {
        $errors[] = 'La cantidad es requerida.';
    } elseif (!is_numeric($cantidad)) {
        $errors[] = 'La cantidad debe ser un número.';
    }
    if (empty($fecha)) {
        $errors[] = 'La fecha es requerida.';
    }
    if (empty($estado)) {
        $errors[] = 'El estado es requerido.';
    }
    // si no hay errores se insertan los datos en la base de datos 
    if (empty($errors)) {
        //este es el arreglo con los datos del nuevo registro
        $record = array($id, $cliente_id, $producto, $cantidad, $fecha, $estado);

        // Abrir el archivo CSV en modo escritura
        if (($file = fopen('pedidos.csv', 'a')) !== FALSE) {
            // Escribir el registro en el archivo CSV
            fputcsv($file, $record);
            // Cerrar el archivo CSV
            fclose($file);
            // Redirigir al usuario a la página principal
            header('Location: index.php');
            exit();
        } else {
            $errors[] = 'Error al abrir el archivo CSV para escritura.';
        }
    }

    // Guardar errores y datos antiguos en la sesión
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = $_POST;
    header('Location: crearpedido.php'); // Redirigir de vuelta al formulario
    exit();
} else {
    // Establecer valores predeterminados para los campos del formulario
    $id = '';
    $cliente_id = '';
    $producto = '';
    $cantidad = '';
    $fecha = '';
    $estado = '';

    // Establecer un arreglo vacío para los errores
    $errors = array();
}
?>

<head>
    <title>Crear Pedido</title>
    <?php include("phps/head.php"); ?>
</head>

<body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">
    <?php include("phps/topBar.php"); ?>
    <?php include("phps/sideBar.php"); ?>
    <main class="col-span-4 row-span-3 col-start-2 row-start-2 flex flex-col justify-center items-center">
        <h1 class="text-center font-bold text-2xl pt-2 ">Crear Pedido</h1>
        <?php if (!empty($_SESSION['errors'])): ?>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); // Limpiar errores después de mostrarlos 
            ?>
        <?php endif; ?>
        <form method="post" action="crearpedido.php" class="mt-4 border border-gray-500 justify-center items-center w-96  flex flex-col gap-2">
            <label for="id" class="mt-2">ID: <input class="mt-2 inline border border-[#000]" type="text" name="id" id="id" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['id']) ? $_SESSION['old_data']['id'] : ''); ?>"></label>

            <label for="cliente_id" class="mt-2">Cliente ID: <input type="text" class="border border-[#000] name="cliente_id" id="cliente_id" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['cliente_id']) ? $_SESSION['old_data']['cliente_id'] : ''); ?>"></label>

            <label for="producto" class="mt-2">Producto: <input type="text" class="border border-[#000] name="producto" id="producto" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['producto']) ? $_SESSION['old_data']['producto'] : ''); ?>"></label>

            <label for="cantidad" class="mt-2">Cantidad: <input type="text" class="border border-[#000] name="cantidad" id="cantidad" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['cantidad']) ? $_SESSION['old_data']['cantidad'] : ''); ?>"></label>

            <label for="fecha" class="mt-2">Fecha:  <input type="date" class="border border-[#000] name="fecha" id="fecha" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['fecha']) ? $_SESSION['old_data']['fecha'] : ''); ?>"></label>

            <label for="estado" class="mt-2">Estado:  <input type="text" class="border border-[#000] name="estado" id="estado" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['estado']) ? $_SESSION['old_data']['estado'] : ''); ?>"></label>

            <input type="submit" name="submit" value="Crear Pedido" class="bg-[afa] border border-[#000] p-2 rounded-lg mb-4">
        </form>
    </main>

    <?php include("phps/footer.php"); ?>



</body>