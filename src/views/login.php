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

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/">ReparaYa</a>
        <div class="d-flex">
            <a href="/registro" class="btn btn-outline-light">Registrarse</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            
            <?php if (!empty($mensajeExito)): ?>
                <div class="alert alert-success text-center" role="alert">
                    <?= htmlspecialchars($mensajeExito) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-header bg-white text-center py-3">
                    <h4 class="mb-0 text-primary">Iniciar sesión</h4>
                </div>
                <div class="card-body p-4">
                    
                    <form action="/login" method="POST">
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
                        </div>

                    </form>

                </div>
                <div class="card-footer bg-white text-center py-3">
                    <small>¿No tienes cuenta? <a href="/registro">Regístrate</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>