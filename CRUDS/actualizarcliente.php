<?php
session_start(); // Iniciar la sesión para mostrar posibles errores

// Leer el archivo CSV
$clientes = array();
if (($file = fopen('clientes.csv', 'r')) !== FALSE) {
    // Leer línea por línea
    while (($line = fgetcsv($file)) !== FALSE) {
        // Almacenar cada línea como un cliente
        $clientes[] = $line;
    }
    fclose($file);
} else {
    // Si no se puede abrir el archivo, mostrar un error
    $_SESSION['errors'] = ['No se pudo abrir el archivo CSV.'];
    header('Location: index.php'); // Redirigir al inicio si ocurre un error
    exit();
}

// Actualizar datos del cliente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $cliente_id = intval($_POST['id']);
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $telefono = htmlspecialchars($_POST['telefono']);
    $direccion = htmlspecialchars($_POST['direccion']);

    // Validar si el cliente existe en el array
    if (isset($clientes[$cliente_id])) {
        // Actualizar el cliente en el array
        $clientes[$cliente_id] = [$cliente_id, $nombre, $email, $telefono, $direccion];

        // Escribir de nuevo en el archivo CSV
        if (($file = fopen('clientes.csv', 'w')) !== FALSE) {
            foreach ($clientes as $cliente) {
                fputcsv($file, $cliente);
            }
            fclose($file);
            $_SESSION['success'] = 'Datos actualizados correctamente.';
        } else {
            $_SESSION['errors'] = ['Error al guardar los cambios en el archivo.'];
        }
    } else {
        $_SESSION['errors'] = ['Cliente no encontrado.'];
    }
    header('Location: index.php'); // Redirigir al inicio
    exit();
}

// Mostrar el formulario de actualización si se selecciona un cliente
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $clienteSeleccionado = $clientes[$id] ?? null;

    if (!$clienteSeleccionado) {
        $_SESSION['errors'] = ['Cliente no encontrado.'];
        header('Location: index.php');
        exit();
    }
}
?>

<head>
    <title>Actualizar Cliente</title>
    <?php include("phps/head.php"); ?>
</head>

<body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">
    <?php include("phps/topBar.php"); ?>
    <?php include("phps/sideBar.php"); ?>
    
    <main class="col-span-4 row-span-3 col-start-2 row-start-2">
        <h1 class="text-center font-bold text-2xl pt-2">Actualizar Cliente</h1>
        
        <!-- Mostrar errores -->
        <?php if (!empty($_SESSION['errors'])): ?>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Mostrar mensajes de éxito -->
        <?php if (!empty($_SESSION['success'])): ?>
            <p><?php echo htmlspecialchars($_SESSION['success']); ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Formulario de actualización -->
        <?php if ($clienteSeleccionado): ?>
            <form method="POST">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($clienteSeleccionado[1]); ?>" required>
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($clienteSeleccionado[2]); ?>" required>
                </div>
                <div>
                    <label>Teléfono:</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($clienteSeleccionado[3]); ?>" required>
                </div>
                <div>
                    <label>Dirección:</label>
                    <input type="text" name="direccion" value="<?php echo htmlspecialchars($clienteSeleccionado[4]); ?>" required>
                </div>
                <button type="submit" name="update">Actualizar</button>
            </form>
        <?php endif; ?>
    </main>

    <?php include("phps/footer.php"); ?>
</body>
