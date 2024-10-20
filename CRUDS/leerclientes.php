<?php
session_start(); // Iniciar la sesión para mostrar posibles errores

// Leer el archivo CSV
$clientes = array();
if (($file = fopen('clientes.csv', 'r')) !== FALSE) {
    // Leer línea por línea
    while (($line = fgetcsv($file)) !== FALSE) {
        // Almacenar cada línea como un pedido
        $clientes[] = $line;
    }
    fclose($file);
} else {
    // Si no se puede abrir el archivo, mostrar un error
    $_SESSION['errors'] = ['No se pudo abrir el archivo CSV.'];
    header('Location: index.php'); // Redirigir al inicio si ocurre un error
    exit();
}
?>

<head>
    <title>Leer Clientes</title>
    <?php include("phps/head.php"); ?>
</head>

<body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">
    <?php include("phps/topBar.php"); ?>
    <?php include("phps/sideBar.php"); ?>
    
    <main class="col-span-4 row-span-3 col-start-2 row-start-2">
        <h1 class="text-center font-bold text-2xl pt-2 ">Clientes Registrados</h1>
        
        <?php if (!empty($_SESSION['errors'])): ?>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); ?>
        <?php endif; ?>

        <!-- Mostrar los pedidos en una tabla -->
        <table class="mt-4 w-full border-collapse border border-[#000]">
            <thead>
                <tr>
                    <th class="border border-[#000] px-4 py-2">ID</th>
                    <th class="border border-[#000] px-4 py-2">Nombre</th>
                    <th class="border border-[#000] px-4 py-2">Email</th>
                    <th class="border border-[#000] px-4 py-2">Telefono</th>
                    <th class="border border-[#000] px-4 py-2">Dirección</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($cliente[0]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($cliente[1]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($cliente[2]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($cliente[3]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($cliente[4]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <?php include("phps/footer.php"); ?>
</body>