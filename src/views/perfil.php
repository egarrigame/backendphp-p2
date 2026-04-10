<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="/~uocx1/">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-secondary">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="/">Cambiar datos del perfil</a>
        <div class="d-flex align-items-center">
            <a href="/panel" class="btn btn-outline-light btn-sm">Volver al panel</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            
            <?php if (!empty($mensajeExito)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($mensajeExito) ?></div>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-center py-3">
                    Modificar datos de usuario
                </div>
                <div class="card-body p-4">
                    <form action="/perfil" method="POST">
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase">Nombre</label>
                            <input type="text" class="form-control" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase">Correo</label>
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase">Nueva contraseña</label>
                            <input type="password" class="form-control" name="password" placeholder="Déjalo en blanco para mantener la actual">
                            <div class="form-text">Solo rellena este campo si deseas cambiar tu contraseña de acceso.</div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>