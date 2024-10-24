<?php
session_start();

// Leer los clientes
$clientes = [];
if (($file = fopen('clientes.csv', 'r')) !== FALSE) {
    while (($line = fgetcsv($file)) !== FALSE) {
        $clientes[$line[0]] = $line; // Usar el ID del cliente como clave
    }
    fclose($file);
} else {
    $_SESSION['errors'] = ['No se pudo abrir el archivo de clientes.'];
}

// Leer los pedidos
$pedidos = [];
if (($file = fopen('pedidos.csv', 'r')) !== FALSE) {
    while (($line = fgetcsv($file)) !== FALSE) {
        $pedidos[] = $line; // Almacenar todos los pedidos
    }
    fclose($file);
} else {
    $_SESSION['errors'] = ['No se pudo abrir el archivo de pedidos.'];
}

// Inicializar variables
$pedidosCliente = [];
$clienteId = null;

// Procesar la solicitud si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'])) {
    $clienteId = htmlspecialchars(trim($_POST['cliente_id']));

    if ($clienteId && isset($clientes[$clienteId])) {
        foreach ($pedidos as $pedido) {
            if ($pedido[1] == $clienteId) { // Comparar el ClienteID
                $pedidosCliente[] = $pedido;
            }
        }
    } else {
        $_SESSION['errors'] = ['Cliente no encontrado.'];
    }
}

?>

<head>
    <title>Pedidos del Cliente</title>
    <?php include("phps/head.php"); ?>
</head>

<body class="grid grid-cols-5 grid-rows-5 gap-0.5 h-screen">

<?php include("phps/topBar.php"); ?>
<?php include("phps/sideBar.php"); ?>

<main class="col-span-4 row-span-3 col-start-2 row-start-2 flex flex-col justify-center items-center">

    <h1 class="font-semibold text-xl mb-4">Consultar Pedidos por Cliente</h1>
    
    <!-- Formulario para ingresar cliente_id -->
    <form method="POST" action="" class="flex flex-col">
        <label for="cliente_id">Cliente ID: <input type="text" name="cliente_id" id="cliente_id" class="border border-[#000]" required></label>
        
        <input type="submit" value="Consultar" class="bg-[afa] border border-[#000] p-2 rounded-lg mb-4">
    </form>

    <?php if (!empty($_SESSION['errors'])): ?>
        <ul>
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    
    <?php if ($clienteId): ?>
        <h2 class="font-bold text-2xl">Pedidos del Cliente: <?php echo htmlspecialchars($clientes[$clienteId][1] ?? ''); ?></h2>
        
        <table class="mt-4 w-full border-collapse border border-[#000]">
            <thead>
                <tr>
                    <th class="border border-[#000] px-4 py-2">ID</th>
                    <th class="border border-[#000] px-4 py-2">Producto</th>
                    <th class="border border-[#000] px-4 py-2">Cantidad</th>
                    <th class="border border-[#000] px-4 py-2">Fecha</th>
                    <th class="border border-[#000] px-4 py-2">Estado</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pedidosCliente)): ?>
                    <tr>
                        <td colspan="4">No hay pedidos para este cliente.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pedidosCliente as $pedido): ?>
                        <tr>
                            <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[0]); ?></td>
                            <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[2]); ?></td>
                            <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[3]); ?></td>
                            <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[4]); ?></td>
                            <td class="border border-[#000] px-4 py-2"><?php echo htmlspecialchars($pedido[5]); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
<?php include("phps/footer.php"); ?>

</body>
