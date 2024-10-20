<?php
session_start(); // con esto iniciamos la sesion directamente por si al abrir la pagina quedan datos flotando
if (isset($_POST['submit'])) {

    //el htmlspecialchars es para que no se metan caracteres especiales y  asi  cuando tengas que codificar
    // en la base de datos no se metan caracteres especiales por si modificamos el arreglo
    $id = htmlspecialchars(trim($_POST['id']));
    $nombre = htmlspecialchars(trim($_POST['nombre']));
    $email = htmlspecialchars(trim($_POST['email']));
    $telefono = htmlspecialchars(trim($_POST['telefono']));
    $direccion = htmlspecialchars(trim($_POST['direccion']));

    // con el siguiente codigo se verifica que los datos esten puestos y no queden vacio cuando se confirme 
    // asi evitamos directamente que que hayan espacios en blanco
    $errors = array();
    if (empty($id)) {
        $errors[] = 'El ID es requerido.';
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
        $errors[] = 'La cantidad debe ser un número.';
    }
    if (empty($direccion)) {
        $errors[] = 'La dirección es requerida.';
    }

    // si no hay errores se insertan los datos en la base de datos 
    if (empty($errors)) {
        //este es el arreglo con los datos del nuevo registro
        $record = array($id, $nombre, $email, $telefono, $direccion);

        // Abrir el archivo CSV en modo escritura
        if (($file = fopen('clientes.csv', 'a')) !== FALSE) {
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
    header('Location: agregarcliente.php'); // Redirigir de vuelta al formulario
    exit();
} else {
    // Establecer valores predeterminados para los campos del formulario
    $id = '';
    $nombre = '';
    $email = '';
    $telefono = '';
    $direccion = '';

    // Establecer un arreglo vacío para los errores
    $errors = array();
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
        <h1 class="text-center font-bold text-2xl pt-2 ">Agregar Cliente</h1>
        <?php if (!empty($_SESSION['errors'])): ?>
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['errors']); // Limpiar errores después de mostrarlos 
            ?>
        <?php endif; ?>
        <form method="post" action="agregarcliente.php" class="mt-4 border border-gray-500 justify-center items-center w-96  flex flex-col gap-2">
            <label for="id" class="mt-2" >ID: <input class="mt-2 inline border border-[#000]" type="text" name="id" id="id" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['id']) ? $_SESSION['old_data']['id'] : ''); ?>"></label>
            
            <label for="nombre" class="mt-2">Nombre: <input type="text" class="border border-[#000]" name="nombre" id="nombre" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['nombre']) ? $_SESSION['old_data']['nombre'] : ''); ?>"></label>
            
            <label for="email" class="mt-2">Email: <input type="email" class="border border-[#000]" name="email" id="email" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['email']) ? $_SESSION['old_data']['email'] : ''); ?>"></label>
            
            <label for="telefono" class="mt-2">Telefono: <input type="number" class="border border-[#000]" name="telefono" id="telefono" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['telefono']) ? $_SESSION['old_data']['telefono'] : ''); ?>"></label>
            
            <label for="direccion" class="mt-2">Dirección:  <input type="text" class="border border-[#000]" name="direccion" id="direccion" value="<?php echo htmlspecialchars(isset($_SESSION['old_data']['direccion']) ? $_SESSION['old_data']['direccion'] : ''); ?>"></label>
            
            
            <input type="submit" name="submit" value="Agregar Cliente" class="bg-[afa] border border-[#000] p-2 rounded-lg mb-4">
        </form>
    </main>

    <?php include("phps/footer.php"); ?>
                
                        
                
</body>