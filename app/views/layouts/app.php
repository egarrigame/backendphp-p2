<?php extract($data ?? []); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ReparaYa</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="/">ReparaYa</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">

                <?php if (isset($_SESSION['user'])): ?>

                    <?php if ($_SESSION['user']['rol'] === 'admin'): ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">Dashboard</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/incidencias">Incidencias</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/calendario">Calendario</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/tecnicos">Técnicos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/especialidades">Servicios</a>
                        </li>

                    <?php elseif ($_SESSION['user']['rol'] === 'tecnico'): ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/tecnico/agenda">Mi agenda</a>
                        </li>

                    <?php else: ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/cliente/dashboard">Mis avisos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="/cliente/nueva-incidencia">Nueva incidencia</a>
                        </li>

                    <?php endif; ?>

                <?php endif; ?>

            </ul>

            <ul class="navbar-nav">

                <?php if (isset($_SESSION['user'])): ?>

                    <li class="nav-item">
                        <span class="nav-link">
                            <?= htmlspecialchars($_SESSION['user']['nombre']) ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/perfil">Perfil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/logout">Salir</a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>

<div class="container mt-4">
    <?php require $viewPath; ?>
</div>

</body>
</html>