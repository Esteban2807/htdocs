<?php
session_start(); // Iniciar la sesión para mostrar posibles errores

// Leer el archivo CSV
$pedidos = array();
if (($file = fopen('pedidos.csv', 'r')) !== FALSE) {
    // Leer línea por línea
    while (($line = fgetcsv($file)) !== FALSE) {
        // Almacenar cada línea como un pedido
        $pedidos[] = $line;
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
    <title>Leer Pedidos</title>
    <?php include("phps/head.php"); ?>
</head>

<body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">
    <?php include("phps/topBar.php"); ?>
    <?php include("phps/sideBar.php"); ?>
    
    <main class="col-span-4 row-span-3 col-start-2 row-start-2">
        <h1 class="text-center font-bold text-2xl pt-2 ">Pedidos Registrados</h1>
        
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
                    <th class="border border-[#000] px-4 py-2">Cliente ID</th>
                    <th class="border border-[#000] px-4 py-2">Producto</th>
                    <th class="border border-[#000] px-4 py-2">Cantidad</th>
                    <th class="border border-[#000] px-4 py-2">Fecha</th>
                    <th class="border border-[#000] px-4 py-2">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[0]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[1]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[2]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[3]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[4]); ?></td>
                        <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[5]); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <?php include("phps/footer.php"); ?>
</body>