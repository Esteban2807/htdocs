<?php
session_start(); // Iniciamos la sesión para almacenar errores y datos antiguos

if (isset($_POST['submit'])) {

    // Evitar inyecciones y caracteres especiales
    $id = htmlspecialchars(trim($_POST['id']));
    $estado = htmlspecialchars(trim($_POST['estado']));

    // Validación de campos
    $errors = array();
    if (empty($id)) {
        $errors[] = 'El ID es requerido.';
    }
    if (empty($estado)) {
        $errors[] = 'El estado es requerido.';
    }

    // Si no hay errores, buscamos el pedido y lo actualizamos
    if (empty($errors)) {
        // Leer el archivo CSV en modo lectura
        if (($file = fopen('pedidos.csv', 'r')) !== FALSE) {
            $data = array();
            $found = false;

            // Leer el archivo línea por línea
            while (($line = fgetcsv($file)) !== FALSE) {
                // Si el ID coincide, actualizamos el estado
                if ($line[0] == $id) {
                    $line[5] = $estado; // Solo actualizamos el estado (asumimos que está en la sexta columna)
                    $found = true;
                }
                // Añadimos la línea al array
                $data[] = $line;
            }
            fclose($file);

            if ($found) {
                // Reescribir el archivo CSV con los datos actualizados
                if (($file = fopen('pedidos.csv', 'w')) !== FALSE) {
                    foreach ($data as $line) {
                        fputcsv($file, $line);
                    }
                    fclose($file);
                    header('Location: index.php'); // Redirigir al índice después de actualizar
                    exit();
                } else {
                    $errors[] = 'Error al abrir el archivo CSV para escritura.';
                }
            } else {
                $errors[] = 'El ID proporcionado no existe en los pedidos.';
            }
        } else {
            $errors[] = 'Error al abrir el archivo CSV para lectura.';
        }
    }

    // Guardamos los errores y los datos antiguos en la sesión
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = $_POST;
    header('Location: actualizarpedido.php'); // Redirigir de vuelta al formulario
    exit();
} else {
    // Valores predeterminados para el formulario
    $id = '';
    $estado = '';

    // Arreglo vacío para los errores
    $errors = array();
}
?>

<head>
    <title>Actualizar Estado del Pedido</title>
    <?php include("phps/head.php"); ?>
</head>

<body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">
    <?php include("phps/topBar.php"); ?>
    <?php include("phps/sideBar.php"); ?>
    <main class="col-span-4 row-span-3 col-start-2 row-start-2 flex flex-col justify-center items-center">
        <h1 class="text-center font-bold text-2xl pt-2 ">Actualizar Estado del Pedido</h1>
        <?php if (!empty($_SESSION['errors'])): ?>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); // Limpiar errores después de mostrarlos ?>
        <?php endif; ?>
        <form method="post" action="actualizarpedido.php" class="mt-4 border border-gray-500 items-center w-96 flex flex-col gap-2">
            <label for="id" class="mt-2">ID: </label>
            <input class="mt-2 inline border border-[#000]" type="text" name="id" id="id" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['id']) ? $_SESSION['old_data']['id'] : ''); ?>">
            
            <label for="estado" class="mt-2">Estado:</label>
            <input type="text" class="border border-[#000]" name="estado" id="estado" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['estado']) ? $_SESSION['old_data']['estado'] : ''); ?>">
            
            <input type="submit" name="submit" value="Actualizar Estado" class="bg-[afa] border border-[#000] p-2 rounded-lg mb-4">
        </form>
    </main>

    <?php include("phps/footer.php"); ?>
</body>