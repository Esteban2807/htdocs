<?php
session_start(); // Iniciar la sesión
if (isset($_POST['submit'])) {

    // Evitar caracteres especiales en los datos ingresados
    $cliente_id = htmlspecialchars(trim($_POST['cliente_id']));
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $direccion = htmlspecialchars(trim($_POST['direccion']));

    // Validaciones de los campos
    $errors = array();
    if (empty($cliente_id)) {
        $errors[] = 'El Cliente_ID es requerido.';
    }
    if (empty($nombre)) {
        $errors[] = 'El nombre es requerido.';
    }
    if (empty($email)) {
        $errors[] = 'El correo electrónico es requerido.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'El correo electrónico no es válido.';
    }
    if (empty($telefono)) {
        $errors[] = 'EL número telefónico es requerido.';
    } elseif (!is_numeric($telefono)) {
        $errors[] = 'El teléfono debe ser un número.';
    }
    if (empty($direccion)) {
        $errors[] = 'La dirección es requerida.';
    }

    // Si no hay errores, se escribe el registro en el CSV
    if (empty($errors)) {
        $record = array($cliente_id, $nombre, $email, $telefono, $direccion);

        // Abrir el archivo CSV en modo escritura
        if (($file = fopen('clientes.csv', 'a')) !== FALSE) {
            fputcsv($file, $record); // Escribir el registro
            fclose($file); // Cerrar el archivo
            header('Location: index.php'); // Redirigir a la página principal
            exit();
        } else {
            $errors[] = 'Error al abrir el archivo CSV para escritura.';
        }
    }

    // Guardar errores y datos antiguos en la sesión
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = $_POST;
    header('Location: agregarcliente.php'); // Redirigir de vuelta al formulario
    exit();
} else {
    // Valores predeterminados
    $cliente_id = '';
    $nombre = '';
    $email = '';
    $telefono = '';
    $direccion = '';

    $errors = array(); // Errores vacíos
}
?>

<head>
    <title>Agregar Cliente</title>
    <?php include("phps/head.php"); ?>
</head>

<body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">
    <?php include("phps/topBar.php"); ?>
    <?php include("phps/sideBar.php"); ?>
    <main class="col-span-4 row-span-3 col-start-2 row-start-2 flex flex-col justify-center items-center">
        <h1 class="text-center font-bold text-2xl pt-2">Agregar Cliente</h1>
        <?php if (!empty($_SESSION['errors'])): ?>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); // Limpiar errores después de mostrarlos ?>
        <?php endif; ?>
        
        <form method="post" action="agregarcliente.php" class="mt-4 border border-gray-500 justify-center items-center w-96 flex flex-col gap-2">
            <label for="cliente_id" class="mt-2">Cliente ID: 
                <input type="text" class="border border-[#000]" name="cliente_id" id="cliente_id" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['cliente_id']) ? $_SESSION['old_data']['cliente_id'] : ''); ?>">
            </label>

            <label for="nombre" class="mt-2">Nombre: 
                <input type="text" class="border border-[#000]" name="nombre" id="nombre" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['nombre']) ? $_SESSION['old_data']['nombre'] : ''); ?>">
            </label>

            <label for="email" class="mt-2">Email: 
                <input type="email" class="border border-[#000]" name="email" id="email" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['email']) ? $_SESSION['old_data']['email'] : ''); ?>">
            </label>

            <label for="telefono" class="mt-2">Telefono: 
                <input type="number" class="border border-[#000]" name="telefono" id="telefono" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['telefono']) ? $_SESSION['old_data']['telefono'] : ''); ?>">
            </label>

            <label for="direccion" class="mt-2">Dirección:  
                <input type="text" class="border border-[#000]" name="direccion" id="direccion" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['direccion']) ? $_SESSION['old_data']['direccion'] : ''); ?>">
            </label>

            <input type="submit" name="submit" value="Agregar Cliente" class="bg-[afa] border border-[#000] p-2 rounded-lg mb-4">
        </form>
    </main>

    <?php include("phps/footer.php"); ?>
</body>