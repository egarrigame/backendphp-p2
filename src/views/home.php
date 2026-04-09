<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/">ReparaYa</a>
        <div class="d-flex">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <span class="text-white me-3">
                    Hola, <b><?= htmlspecialchars($_SESSION['usuario_nombre']) ?></b> 
                    <span class="badge bg-info text-dark ms-1"><?= htmlspecialchars($_SESSION['usuario_rol']) ?></span>
                </span>
                <a href="/logout" class="btn btn-danger">Cerrar sesión</a>
            <?php else: ?>
                <a href="/login" class="btn btn-outline-light me-2">Iniciar sesión</a>
                <a href="/registro" class="btn btn-light">Registrarse</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 fw-bold text-primary"><?= htmlspecialchars($titulo) ?></h1>
            <p class="lead mt-3"><?= htmlspecialchars($mensaje) ?></p>
            <hr class="my-4">
            <p>Gestiona tus averías en un solo lugar.</p>
            <a class="btn btn-primary btn-lg mt-3" href="/registro" role="button">Solicitar un Técnico</a>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row text-center">
        <div class="col-md-4">
            <h3>Rápido</h3>
            <p>Resolvemos tu problema cuanto antes.</p>
        </div>
        <div class="col-md-4">
            <h3>Profesional</h3>
            <p>Especialistas de todo tipo.</p>
        </div>
        <div class="col-md-4">
            <h3>Transparente</h3>
            <p>Seguimiento en tiempo real.</p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>