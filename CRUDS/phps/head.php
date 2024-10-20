    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        // Pasar errores PHP a JavaScript para mostrar en una ventana emergente
        <?php if (!empty($_SESSION['errors'])): ?>
            let errors = <?php echo json_encode($_SESSION['errors']); ?>;
            let errorMessage = errors.join("\n"); // Combinar errores en una sola cadena
            alert(errorMessage); // Mostrar la ventana emergente con los errores
            <?php unset($_SESSION['errors']); // Limpiar errores después de mostrarlos ?>
        <?php endif; ?>
    </script>
    <script src="https://cdn.tailwindcss.com"></script>

