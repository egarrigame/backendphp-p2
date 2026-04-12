<div class="container">

    <h1 class="mb-4">Panel de administración</h1>

    <p>Bienvenido, <strong><?= $_SESSION['user']['nombre']; ?></strong></p>

    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Calendario</h5>
                <p>Visualiza todas las incidencias por fecha.</p>
                <a href="/admin/calendario" class="btn btn-primary">
                    Ver calendario
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Gestión de incidencias</h5>
                <p>Consulta y asigna técnicos.</p>
                <a href="/admin/incidencia-detalle" class="btn btn-warning">
                    Ver incidencias
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h5>Sesión</h5>
                <p>Cerrar sesión actual.</p>
                <a href="/logout" class="btn btn-danger">
                    Cerrar sesión
                </a>
            </div>
        </div>

    </div>

</div>