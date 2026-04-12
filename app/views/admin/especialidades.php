<div class="container mt-4">

    <h2 class="mb-4">Gestión de especialidades</h2>

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

    <!-- Crear nueva -->
    <div class="mb-4">
        <form method="POST" action="/especialidades/guardar" class="d-flex gap-2">
            <input type="text" name="nombre_especialidad" class="form-control" placeholder="Nueva especialidad" required>
            <button class="btn btn-primary">Añadir</button>
        </form>
    </div>

    <!-- Tabla -->
    <?php if (empty($especialidades)): ?>
        <p>No hay especialidades registradas.</p>
    <?php else: ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th style="width: 250px;">Acciones</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($especialidades as $e): ?>
                    <tr>

                        <td><?= $e['id'] ?></td>

                        <td>
                            <form method="POST" action="/especialidades/actualizar" class="d-flex gap-2">
                                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                                <input type="text" name="nombre_especialidad" class="form-control"
                                       value="<?= $e['nombre_especialidad'] ?>" required>
                                <button class="btn btn-warning btn-sm">Guardar</button>
                            </form>
                        </td>

                        <td>
                            <form method="POST" action="/especialidades/eliminar" 
                                  onsubmit="return confirm('¿Eliminar esta especialidad?');">
                                <input type="hidden" name="id" value="<?= $e['id'] ?>">
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

</div>