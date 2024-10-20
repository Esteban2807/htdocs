<?php
session_start(); // Iniciar sesión para almacenar errores y datos

if (isset($_POST['submit'])) {

    // Con esta parte se evitan inyecciones al cvs
    $id = htmlspecialchars(trim($_POST['id']));

    // Con este se valida si el id esta vacio
    $errors = array();
    if (empty($id)) {
        $errors[] = 'El ID es requerido para eliminar la cuenta.';

    }

    if (empty($errors)) {
        // Leer el archivo CSV en modo lectura
        if (($file = fopen('clientes.csv', 'r')) !== FALSE) {
            $data = array();
            $found = false;

            // Leer el archivo línea por línea
            while (($line = fgetcsv($file)) !== FALSE) {
                // Si el ID no coincide, lo conservamos
                if ($line[0] != $id) {
                    $data[] = $line;
                } else {
                    $found = true;
                }
            }
            fclose($file);

            if ($found) {
                // Reescribir el archivo CSV sin el pedido eliminado
                if (($file = fopen('clientes.csv', 'w')) !== FALSE) {
                    foreach ($data as $line) {
                        fputcsv($file, $line);
                    }
                    fclose($file);
                    header('Location: index.php'); // Redirigir a la página principal
                    exit();
                } else {
                    $errors[] = 'Error al abrir el archivo CSV para escritura.';
                }
            } else {
                $errors[] = 'El ID proporcionado no existe entre los clientes.';
            }
        } else {
            $errors[] = 'Error al abrir el archivo CSV para lectura.';
        }
    }

        // Guardar errores y datos antiguos en la sesión
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $_POST;
        header('Location: eliminarcliente.php'); // Redirigir de vuelta al formulario
        exit();
    } else {
        // Valores predeterminados para el formulario
        $id = '';
        $errors = array();
    }
    ?>
    
    <head>
        <title>Borrar Cuenta</title>
        <?php include("phps/head.php"); ?>
    </head>
    
    <body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">
        <?php include("phps/topBar.php"); ?>
        <?php include("phps/sideBar.php"); ?>
        <main class="col-span-4 row-span-3 col-start-2 row-start-2 flex flex-col justify-center items-center">
            <h1 class="text-center font-bold text-2xl pt-2 ">Eliminar Cliente</h1>
            <?php if (!empty($_SESSION['errors'])): ?>
                <ul>
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php unset($_SESSION['errors']); // Limpiar errores después de mostrarlos ?>
            <?php endif; ?>
    
            <form method="post" action="eliminarcliente.php" class="mt-4 border border-gray-500 justify-center items-center w-96 flex flex-col gap-2">
                <label for="id">ID del cliente a eliminar:</label>
                <input type="text" name="id" class="border border-[#000]" id="id" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['id']) ? $_SESSION['old_data']['id'] : ''); ?>"><br>
                <input type="submit" name="submit" value="Borrar Cliente" class="bg-[afa] border border-[#000] p-2 rounded-lg mb-4">
            </form>
        </main>
    
        <?php include("phps/footer.php"); ?>
    
    </body>