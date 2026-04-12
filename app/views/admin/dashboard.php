<div class="container mt-4">

    <h1 class="mb-4">Panel de Administración</h1>

    <p class="mb-4">
        Bienvenido, <strong><?= $_SESSION['user']['nombre']; ?></strong>
    </p>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="row">

        <!-- Crear incidencia -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>Crear incidencia</h5>
                <p>Registrar una nueva incidencia manualmente.</p>
                <a href="/admin/crear-incidencia" class="btn btn-primary">
                    Crear
                </a>
            </div>
        </div>

        <!-- Gestionar incidencias -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>Gestión de incidencias</h5>
                <p>Ver, asignar técnicos y controlar estados.</p>
                <a href="/admin/incidencia-detalle" class="btn btn-warning">
                    Gestionar
                </a>
            </div>
        </div>

        <!-- Calendario -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>Calendario</h5>
                <p>Visualizar incidencias por fecha y urgencia.</p>
                <a href="/admin/calendario" class="btn btn-success">
                    Ver calendario
                </a>
            </div>
        </div>

        <!-- Técnicos -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>Técnicos</h5>
                <p>Gestionar técnicos disponibles.</p>
                <a href="/tecnicos" class="btn btn-secondary">
                    Ver técnicos
                </a>
            </div>
        </div>

        <!-- Especialidades -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>Especialidades</h5>
                <p>Configurar tipos de servicio.</p>
                <a href="/especialidades" class="btn btn-info">
                    Gestionar
                </a>
            </div>
        </div>

        <!-- Logout -->
        <div class="col-md-4 mb-3">
            <div class="card h-100 p-3">
                <h5>Sesión</h5>
                <p>Cerrar sesión del sistema.</p>
                <a href="/logout" class="btn btn-danger">
                    Cerrar sesión
                </a>
            </div>
        </div>

    </div>

</div>