<?php
// Cargar utilidades y clases
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/classes/Book.php';

// Procesar POST para agregar libro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_book') {
    // validar (muy simple)
    $title = trim((string)($_POST['title'] ?? ''));
    $author = trim((string)($_POST['author'] ?? ''));
    $year = isset($_POST['year']) ? (int)$_POST['year'] : '';
    $available = isset($_POST['available']) ? true : false;

    if ($title !== '' && $author !== '') {
        $ok = Book::create([
            'title' => $title,
            'author' => $author,
            'year' => $year,
            'available' => $available,
        ]);
        if ($ok) {
            // evitar re-envío del formulario
            header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
            exit;
        } else {
            $error = "No se pudo guardar el libro. Verificá permisos en data/data.json";
        }
    } else {
        $error = "Completá título y autor.";
    }
}

// Obtener lista actual de libros
$books = Book::all();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Biblioteca</title>
    <?php include 'includes/header.php'; ?>
</head>
<body>

<?php if (!empty($error)): ?>
    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="row">

    <!-- LISTADO DE LIBROS -->
    <div class="col-md-8">
        <h3>Listado de libros (<?php echo count($books); ?>)</h3>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Año</th>
                    <th>Disponible</th>
                </tr>
            </thead>

            <tbody id="booksTable">
                <?php foreach ($books as $i => $b) { ?>
                    <tr>
                        <td><?php echo $i + 1; ?></td>
                        <td><?php echo htmlspecialchars($b['title']); ?></td>
                        <td><?php echo htmlspecialchars($b['author']); ?></td>
                        <td><?php echo htmlspecialchars($b['year']); ?></td>
                        <td><?php echo (!empty($b['available']) ? 'Sí' : 'No'); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- FORMULARIO -->
    <div class="col-md-4">
        <h3>Agregar libro</h3>

        <form method="POST" id="addBookForm" novalidate>
            <input type="hidden" name="action" value="add_book">

            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">Autor</label>
                <input type="text" class="form-control" id="author" name="author" required>
            </div>

            <div class="mb-3">
                <label for="year" class="form-label">Año</label>
                <input type="text" class="form-control" id="year" name="year">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="available" name="available">
                <label class="form-check-label" for="available">Disponible</label>
            </div>

            <button type="submit" class="btn btn-primary">Agregar</button>
        </form>

    </div>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
